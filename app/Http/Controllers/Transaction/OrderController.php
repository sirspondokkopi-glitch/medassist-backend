<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Models\InstrumentStock;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class OrderController extends Controller
{
    /** Relasi yang dimuat saat menampilkan detail order. */
    private const DETAIL_RELATIONS = [
        'room',
        'user',
        'items.instrumentStock.instrument',
        'items.conditionOut',
        'items.conditionIn',
    ];

    public function index(Request $request): JsonResponse
    {
        $data = Order::with(['room', 'user'])
            ->withCount('items')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when(
                $request->search,
                fn ($q, $s) => $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('room', fn ($q) => $q->where('name', 'like', "%{$s}%"))
            )
            ->latest()
            ->paginate(20);

        return $this->success('Data peminjaman berhasil diambil.', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'user_id' => 'nullable|integer|exists:users,id',
            'order_date' => 'required|date',
            'return_plan_date' => 'nullable|date',
            'note' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.instrument_stock_id' => 'required|integer|distinct|exists:instrument_stocks,id',
            'items.*.condition_out_id' => 'nullable|integer|exists:conditions,id',
        ]);

        try {
            $order = DB::transaction(function () use ($validated) {
                $order = Order::create([
                    'room_id' => $validated['room_id'],
                    'user_id' => $validated['user_id'] ?? null,
                    'order_date' => $validated['order_date'],
                    'return_plan_date' => $validated['return_plan_date'] ?? null,
                    'note' => $validated['note'] ?? null,
                    'status' => Order::STATUS_DIAJUKAN,
                ]);

                foreach ($validated['items'] as $item) {
                    $order->items()->create([
                        'instrument_stock_id' => $item['instrument_stock_id'],
                        'condition_out_id' => $item['condition_out_id'] ?? null,
                    ]);
                }

                return $order;
            });

            $order->load(self::DETAIL_RELATIONS);

            return $this->success('Peminjaman berhasil dibuat.', $order, 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Order $order): JsonResponse
    {
        $order->load(self::DETAIL_RELATIONS);

        return $this->success('Detail peminjaman berhasil diambil.', $order);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $validated = $request->validate([
            'status' => ['sometimes', Rule::in(Order::STATUSES)],
            'return_plan_date' => 'sometimes|nullable|date',
            'note' => 'sometimes|nullable|string',
            'items' => 'sometimes|array',
            'items.*.id' => 'required_with:items|integer',
            'items.*.is_returned' => 'sometimes|boolean',
            'items.*.condition_in_id' => 'sometimes|nullable|integer|exists:conditions,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $order) {
                $order->fill(array_intersect_key($validated, array_flip(['return_plan_date', 'note'])));

                // Perubahan status header + sinkronisasi status unit instrumen.
                if (isset($validated['status'])) {
                    $this->applyStatusTransition($order, $validated['status']);
                }

                $order->save();

                // Pengembalian per-unit.
                if (! empty($validated['items'])) {
                    $this->processReturns($order, $validated['items']);
                }
            });

            $order->load(self::DETAIL_RELATIONS);

            return $this->success('Peminjaman berhasil diperbarui.', $order);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Order $order): JsonResponse
    {
        try {
            $order->delete();

            return $this->success('Peminjaman berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Terapkan perubahan status order pada model + sinkronkan status unit instrumen terkait.
     * Tidak melakukan save() pada $order (dilakukan pemanggil).
     */
    private function applyStatusTransition(Order $order, string $status): void
    {
        $order->status = $status;
        $stockIds = $order->items()->pluck('instrument_stock_id');
        $meta = ['context' => 'order', 'reference' => $order->code];

        switch ($status) {
            case Order::STATUS_DIPINJAM:
                InstrumentStock::transitionMany($stockIds, InstrumentStock::STATUS_DIPINJAM, $meta);
                break;

            case Order::STATUS_DIKEMBALIKAN:
                InstrumentStock::transitionMany($stockIds, InstrumentStock::STATUS_TERSEDIA, $meta);
                $order->items()->update(['is_returned' => true]);
                $order->return_actual_date ??= now()->toDateString();
                break;

            case Order::STATUS_DIBATALKAN:
                // Kembalikan unit yang sempat dipinjam ke status tersedia.
                InstrumentStock::transitionMany($stockIds, InstrumentStock::STATUS_TERSEDIA, $meta);
                break;
        }
    }

    /**
     * Proses pengembalian per unit. Bila seluruh unit sudah kembali,
     * order otomatis menjadi "dikembalikan".
     */
    private function processReturns(Order $order, array $items): void
    {
        foreach ($items as $data) {
            $item = $order->items()->find($data['id']);
            if (! $item) {
                continue;
            }

            $item->fill(array_intersect_key($data, array_flip(['is_returned', 'condition_in_id'])));
            $item->save();

            if (! empty($data['is_returned'])) {
                InstrumentStock::transitionMany([$item->instrument_stock_id], InstrumentStock::STATUS_TERSEDIA, [
                    'context' => 'order',
                    'reference' => $order->code,
                ]);
            }
        }

        if ($order->items()->where('is_returned', false)->doesntExist()) {
            $order->status = Order::STATUS_DIKEMBALIKAN;
            $order->return_actual_date ??= now()->toDateString();
            $order->save();
        }
    }
}
