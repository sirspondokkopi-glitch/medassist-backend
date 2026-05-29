<?php

namespace Database\Seeders;

use App\Models\TitleMenus;
use Illuminate\Database\Seeder;

class TitleMenuSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['title' => 'Dashboard', 'icon' => 'dashboard', 'sort_order' => 1, 'is_open' => true],
            ['title' => 'Master Data', 'icon' => 'database',  'sort_order' => 2, 'is_open' => true],
        ];

        foreach ($items as $item) {
            TitleMenus::create($item);
        }
    }
}
