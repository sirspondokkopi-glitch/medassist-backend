<?php

namespace App\Http\Controllers\Master;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $data = Menu::with('parent')
            ->when($request->search, fn ($q, $s) => $q->where('name', 'like', "%{$s}%"))
            ->orderBy('sort_order')
            ->paginate(20);

        return $this->success('Berhasil mengambil data menu.', $data);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'title_menu_id' => 'nullable|integer|exists:title_menuses,id',
            'parent_id'     => 'nullable|integer|exists:menus,id',
            'name'          => 'required|string|max:100',
            'url'           => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        try {
            $menu = Menu::create($validated);
            $menu->load(['titleMenu', 'parent']);

            return $this->success('Menu berhasil dibuat.', $menu, 201);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function show(Menu $menu): JsonResponse
    {
        $menu->load(['parent', 'children']);

        return $this->success('Berhasil mengambil detail menu.', $menu);
    }

    public function update(Request $request, Menu $menu): JsonResponse
    {
        $validated = $request->validate([
            'title_menu_id' => 'nullable|integer|exists:title_menuses,id',
            'parent_id'     => 'nullable|integer|exists:menus,id',
            'name'          => 'sometimes|required|string|max:100',
            'url'           => 'nullable|string|max:255',
            'sort_order'    => 'nullable|integer|min:0',
        ]);

        try {
            $menu->update($validated);
            $menu->load(['titleMenu', 'parent', 'children']);

            return $this->success('Menu berhasil diperbarui.', $menu);
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }

    public function destroy(Menu $menu): JsonResponse
    {
        try {
            $menu->delete();

            return $this->success('Menu berhasil dihapus.');
        } catch (\Throwable $e) {
            return $this->error($e->getMessage(), 500);
        }
    }
}
