<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\TitleMenus;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $dashboard  = TitleMenus::where('title', 'Dashboard')->first();
        $masterData = TitleMenus::where('title', 'Master Data')->first();
        $cssd       = TitleMenus::where('title', 'Cssd')->first();

        // Dashboard — langsung link, tidak ada sub_menu
        Menu::create([
            'title_menu_id' => $dashboard?->id,
            'name'          => 'Dashboard',
            'url'           => '/dashboard',
            'icon'          => 'dashboard',
            'sort_order'    => 1,
            'is_open'       => false,
        ]);

        // Master Data — parent (tidak punya url), anak-anaknya yang punya url
        $masterParent = Menu::create([
            'title_menu_id' => $masterData?->id,
            'name'          => 'Master Data',
            'url'           => null,
            'icon'          => 'database',
            'sort_order'    => 1,
            'is_open'       => false,
        ]);

        $children = [
            ['name' => 'Authority', 'url' => '/master/otoritas', 'icon' => 'shield', 'sort_order' => 1],
            ['name' => 'Menu',      'url' => '/master/menu',     'icon' => 'menu',   'sort_order' => 2],
            ['name' => 'User',      'url' => '/master/user',     'icon' => 'users',  'sort_order' => 3],
        ];

        foreach ($children as $child) {
            Menu::create([
                'title_menu_id' => $masterData?->id,
                'parent_id'     => $masterParent->id,
                'name'          => $child['name'],
                'url'           => $child['url'],
                'icon'          => $child['icon'],
                'sort_order'    => $child['sort_order'],
                'is_open'       => false,
            ]);
        }

        // Cssd — parent (tidak punya url)
        $cssdParent = Menu::create([
            'title_menu_id' => $cssd?->id,
            'name'          => 'Cssd',
            'url'           => null,
            'icon'          => 'box',
            'sort_order'    => 1,
            'is_open'       => false,
        ]);

        $cssdChildren = [
            ['name' => 'Ruangan',   'url' => '/master/ruangan',   'sort_order' => 1],
            ['name' => 'Instrumen', 'url' => '/master/instrumen', 'sort_order' => 2],
        ];

        foreach ($cssdChildren as $child) {
            Menu::create([
                'title_menu_id' => $cssd?->id,
                'parent_id'     => $cssdParent->id,
                'name'          => $child['name'],
                'url'           => $child['url'],
                'sort_order'    => $child['sort_order'],
                'is_open'       => false,
            ]);
        }

        // Transaksi — parent (tidak punya url)
        $transaksiParent = Menu::create([
            'title_menu_id' => $cssd?->id,
            'name'          => 'Transaksi',
            'url'           => null,
            'icon'          => 'activity',
            'sort_order'    => 2,
            'is_open'       => false,
        ]);

        Menu::create([
            'title_menu_id' => $cssd?->id,
            'parent_id'     => $transaksiParent->id,
            'name'          => 'Monitoring',
            'url'           => 'cssd/monitoring',
            'sort_order'    => 1,
            'is_open'       => false,
        ]);
    }
}
