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

        $menus = [
            [
                'title_menu_id' => $dashboard?->id,
                'name'          => 'Dashboard',
                'url'           => '/dashboard',
                'sort_order'    => 1,
            ],
            [
                'title_menu_id' => $masterData?->id,
                'name'          => 'Authority',
                'url'           => '/master/otoritas',
                'sort_order'    => 1,
            ],
            [
                'title_menu_id' => $masterData?->id,
                'name'          => 'Menu',
                'url'           => '/master/menu',
                'sort_order'    => 2,
            ],
            [
                'title_menu_id' => $masterData?->id,
                'name'          => 'User',
                'url'           => '/master/user',
                'sort_order'    => 3,
            ],
        ];

        foreach ($menus as $menuData) {
            Menu::create($menuData);
        }
    }
}
