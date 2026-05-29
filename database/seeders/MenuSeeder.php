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

        // Dashboard — langsung link, tidak ada sub_menu
        Menu::create([
            'title_menu_id' => $dashboard?->id,
            'name'          => 'Dashboard',
            'url'           => '/dashboard',
            'sort_order'    => 1,
        ]);

        // Master Data — parent (tidak punya url), anak-anaknya yang punya url
        $masterParent = Menu::create([
            'title_menu_id' => $masterData?->id,
            'name'          => 'Master Data',
            'url'           => null,
            'sort_order'    => 1,
        ]);

        $children = [
            ['name' => 'Authority', 'url' => '/master/otoritas', 'sort_order' => 1],
            ['name' => 'Menu',      'url' => '/master/menu',     'sort_order' => 2],
            ['name' => 'User',      'url' => '/master/user',     'sort_order' => 3],
        ];

        foreach ($children as $child) {
            Menu::create([
                'title_menu_id' => $masterData?->id,
                'parent_id'     => $masterParent->id,
                'name'          => $child['name'],
                'url'           => $child['url'],
                'sort_order'    => $child['sort_order'],
            ]);
        }
    }
}
