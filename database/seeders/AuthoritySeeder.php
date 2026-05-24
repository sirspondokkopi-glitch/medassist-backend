<?php

namespace Database\Seeders;

use App\Models\Authority;
use App\Models\Menu;
use Illuminate\Database\Seeder;

class AuthoritySeeder extends Seeder
{
    public function run(): void
    {
        $allMenuIds = Menu::pluck('id')->toArray();

        $operatorMenuIds = Menu::where('name', 'Dashboard')->pluck('id')->toArray();

        $administrator = Authority::create([
            'name'        => 'Administrator',
            'description' => 'Akses penuh ke seluruh fitur sistem',
        ]);
        $administrator->menus()->attach($allMenuIds);

        $operator = Authority::create([
            'name'        => 'Operator',
            'description' => 'Akses terbatas pada fitur operasional',
        ]);
        $operator->menus()->attach($operatorMenuIds);
    }
}
