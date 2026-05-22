<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\InstrumentStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstrumentStockController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = InstrumentStock::with(['instrument', 'condition'])
            ->when(
                $request->search,
                fn($q, $s) => $q->where('code', 'like', "%{$s}%")
                    ->orWhereHas('instrument', fn($q) => $q->where('name', 'like', "%{$s}%"))
            )
            ->paginate(20);

        return $this->success('Data stok instrumen berhasil diambil.', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'instrument_id' => 'required|integer|exists:instruments,id',
            'condition_id'  => 'nullable|integer|exists:conditions,id',
            'is_available'  => 'boolean',
        ]);

        try {
            $stock = InstrumentStock::create($validated);
            $stock->load(['instrument', 'condition']);
            return $this->success('Stok instrumen berhasil ditambahkan.', $stock, 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(InstrumentStock $instrumentStock): JsonResponse
    {
        $instrumentStock->load(['instrument', 'condition']);
        return $this->success('Detail stok instrumen berhasil diambil.', $instrumentStock);
    }

    public function update(Request $request, InstrumentStock $instrumentStock): JsonResponse
    {
        $validated = $request->validate([
            'instrument_id' => 'required|integer|exists:instruments,id',
            'condition_id'  => 'nullable|integer|exists:conditions,id',
            'is_available'  => 'boolean',
        ]);

        try {
            $instrumentStock->update($validated);
            $instrumentStock->load(['instrument', 'condition']);
            return $this->success('Stok instrumen berhasil diperbarui.', $instrumentStock);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(InstrumentStock $instrumentStock): JsonResponse
    {
        try {
            $instrumentStock->delete();
            return $this->success('Stok instrumen berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
