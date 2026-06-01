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
            'label' => 'WHO WE ARE',
            'url' => '/',
            'sort_order' => 0,
            'is_active' => true,
        ]);

        $whoWeArePages = [
            ['label' => 'About Us', 'url' => '/about-us', 'sort_order' => 0],
            ['label' => 'Where We Are', 'url' => '/where-we-are', 'sort_order' => 1],
            ['label' => 'Our Story', 'url' => '/our-story', 'sort_order' => 2],
            ['label' => 'Message from the CEO', 'url' => '/message-from-ceo', 'sort_order' => 3],
            ['label' => 'Our Team', 'url' => '/our-team-management', 'sort_order' => 4],
            ['label' => 'Contact Us', 'url' => '/contact', 'sort_order' => 5],
        ];

        foreach ($whoWeArePages as $page) {
            SubMenu::query()->create([
                'menu_id' => $home->id,
                'label' => $page['label'],
                'url' => $page['url'],
                'sort_order' => $page['sort_order'],
                'is_active' => true,
            ]);
        }

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
