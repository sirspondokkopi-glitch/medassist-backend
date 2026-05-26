<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        $menus = [
            [
                'name'       => 'Dashboard',
                'url'        => '/dashboard',
                'icon'       => 'dashboard',
                'sort_order' => 1,
            ],
            [
                'name'       => 'Master Data',
                'url'        => null,
                'icon'       => 'database',
                'sort_order' => 2,
                'children'   => [
                    ['name' => 'Authority',        'url' => '/master/otoritas',       'icon' => 'shield',        'sort_order' => 1],
                    ['name' => 'Menu',             'url' => '/master/menu',             'icon' => 'menu',          'sort_order' => 2],
                    ['name' => 'User',             'url' => '/master/user',             'icon' => 'users',         'sort_order' => 3],
                ],
            ],
        ];

        foreach ($menus as $menuData) {
            $children = $menuData['children'] ?? [];
            unset($menuData['children']);

            $parent = Menu::create($menuData);

            foreach ($children as $child) {
                Menu::create(array_merge($child, ['parent_id' => $parent->id]));
            }
        }
    }
}
