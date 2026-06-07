<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Instrument;
use App\Models\InstrumentStock;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class InstrumentController extends Controller
{
    public function stats(): JsonResponse
    {
        return $this->success('Statistik instrumen berhasil diambil.', [
            'total_instruments' => Instrument::count(),
            'total_units'       => InstrumentStock::count(),
            'available_units'   => InstrumentStock::where('status', InstrumentStock::STATUS_TERSEDIA)->count(),
        ]);
    }

    public function index(Request $request): JsonResponse
    {
        $data = Instrument::withCount('stocks')
            ->when(
                $request->search,
                fn($q, $s) => $q->where('name', 'like', "%{$s}%")->orWhere('code', 'like', "%{$s}%")
            )->paginate(20);

        return $this->success('Data instrumen berhasil diambil.', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $instrument = Instrument::create($validated);
            return $this->success('Instrumen berhasil ditambahkan.', $instrument, 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Instrument $instrument): JsonResponse
    {
        return $this->success('Detail instrumen berhasil diambil.', $instrument);
    }

    public function update(Request $request, Instrument $instrument): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        try {
            $instrument->update($validated);
            return $this->success('Instrumen berhasil diperbarui.', $instrument);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Instrument $instrument): JsonResponse
    {
        try {
            $instrument->delete();
            return $this->success('Instrumen berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
