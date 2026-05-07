<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        if (Menu::query()->exists()) {
            return;
        }

        $home = Menu::query()->create([
            'label' => 'Home',
            'url' => '/',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        SubMenu::query()->create([
            'menu_id' => $home->id,
            'label' => 'About Us',
            'url' => '/about-us',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        SubMenu::query()->create([
            'menu_id' => $home->id,
            'label' => 'WHERE WE ARE',
            'url' => '/where-we-are',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        $top = [
            ['label' => 'Ship Supply', 'url' => '/ship-supply', 'sort_order' => 10],
            ['label' => 'Our Services', 'url' => '/our-services', 'sort_order' => 20],
            ['label' => 'Award', 'url' => '/award', 'sort_order' => 30],
            ['label' => 'Contact', 'url' => '/contact', 'sort_order' => 40],
            ['label' => 'GET A QUOTE', 'url' => '/get-a-quote', 'sort_order' => 50],
        ];

        foreach ($top as $row) {
            Menu::query()->create([
                'label' => $row['label'],
                'url' => $row['url'],
                'sort_order' => $row['sort_order'],
                'is_active' => true,
            ]);
        }
    }
}
