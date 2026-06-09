<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\InstrumentSet;
use App\Models\InstrumentStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class InstrumentSetController extends Controller
{
    /** Relasi yang dimuat saat menampilkan detail set. */
    private const DETAIL_RELATIONS = [
        'room',
        'items.instrumentStock.instrument',
    ];

    public function index(Request $request): JsonResponse
    {
        $data = InstrumentSet::with(['room'])
            ->withCount('items')
            ->when($request->status, fn ($q, $s) => $q->where('status', $s))
            ->when($request->room_id, fn ($q, $id) => $q->where('room_id', $id))
            ->when(
                $request->search,
                fn ($q, $s) => $q->where('name', 'like', "%{$s}%")
                    ->orWhere('code', 'like', "%{$s}%")
            )
            ->paginate(20);

        return $this->success('Data set instrumen berhasil diambil.', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'room_id' => 'nullable|integer|exists:rooms,id',
            'status' => ['nullable', Rule::in(InstrumentStock::STATUSES)],
            'note' => 'nullable|string',
            'items' => 'nullable|array',
            'items.*.instrument_stock_id' => 'required|integer|distinct|exists:instrument_stocks,id',
        ]);

        try {
            $set = DB::transaction(function () use ($validated) {
                $set = InstrumentSet::create([
                    'name' => $validated['name'],
                    'room_id' => $validated['room_id'] ?? null,
                    'status' => $validated['status'] ?? InstrumentStock::STATUS_TERSEDIA,
                    'note' => $validated['note'] ?? null,
                ]);

                foreach ($validated['items'] ?? [] as $item) {
                    $set->items()->create(['instrument_stock_id' => $item['instrument_stock_id']]);
                }

                return $set;
            });

            $set->load(self::DETAIL_RELATIONS);

            return $this->success('Set instrumen berhasil dibuat.', $set, 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(InstrumentSet $instrumentSet): JsonResponse
    {
        $instrumentSet->load(self::DETAIL_RELATIONS);

        return $this->success('Detail set instrumen berhasil diambil.', $instrumentSet);
    }

    public function update(Request $request, InstrumentSet $instrumentSet): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'room_id' => 'sometimes|nullable|integer|exists:rooms,id',
            'status' => ['sometimes', Rule::in(InstrumentStock::STATUSES)],
            'note' => 'sometimes|nullable|string',
            'items' => 'sometimes|array',
            'items.*.instrument_stock_id' => 'required_with:items|integer|distinct|exists:instrument_stocks,id',
        ]);

        try {
            DB::transaction(function () use ($validated, $instrumentSet) {
                $instrumentSet->fill(array_intersect_key($validated, array_flip(['name', 'room_id', 'status', 'note'])));
                $instrumentSet->save();

                // Bila "items" dikirim, sinkronkan keanggotaan unit dalam set.
                if (array_key_exists('items', $validated)) {
                    $this->syncItems($instrumentSet, $validated['items'] ?? []);
                }
            });

            $instrumentSet->load(self::DETAIL_RELATIONS);

            return $this->success('Set instrumen berhasil diperbarui.', $instrumentSet);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(InstrumentSet $instrumentSet): JsonResponse
    {
        try {
            $instrumentSet->delete();

            return $this->success('Set instrumen berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    /**
     * Samakan daftar unit dalam set dengan yang dikirim: tambah yang baru, hapus yang tidak ada lagi.
     */
    private function syncItems(InstrumentSet $set, array $items): void
    {
        $desired = collect($items)->pluck('instrument_stock_id')->unique();
        $existing = $set->items()->pluck('instrument_stock_id');

        // Hapus unit yang tidak lagi ada di daftar.
        $set->items()
            ->whereIn('instrument_stock_id', $existing->diff($desired))
            ->get()
            ->each
            ->delete();

        // Tambah unit baru.
        foreach ($desired->diff($existing) as $stockId) {
            $set->items()->create(['instrument_stock_id' => $stockId]);
        }
    }
}
