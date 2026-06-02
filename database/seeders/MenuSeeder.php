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

        $blog = Menu::query()->create([
            'label' => 'BLOG',
            'url' => '/blog',
            'sort_order' => 40,
            'is_active' => true,
        ]);

        $blogPages = [
            ['label' => 'News', 'url' => '/blog/news', 'sort_order' => 0],
            ['label' => 'Events', 'url' => '/blog/events', 'sort_order' => 1],
            ['label' => 'Gallery', 'url' => '/blog/gallery', 'sort_order' => 2],
            ['label' => 'Recipes', 'url' => '/blog/recipes', 'sort_order' => 3],
            ['label' => 'Newport TV', 'url' => '/blog/newport-tv', 'sort_order' => 4],
        ];

        foreach ($blogPages as $page) {
            $category = SubMenu::query()->create([
                'menu_id' => $blog->id,
                'label' => $page['label'],
                'url' => $page['url'],
                'sort_order' => $page['sort_order'],
                'is_active' => true,
            ]);

            $posts = match ($page['url']) {
                '/blog/news' => [
                    [
                        'label' => 'IMPA SAVE London Sustainability Run',
                        'slug' => 'impa-save-london-sustainability-run',
                        'published_at' => '2024-09-10',
                        'page_content' => "We participated in the IMPA SAVE Sustainability Run as GIMAS. Our vision is Green Responsibility.\n\nThis activity represents our commitment to sustainability and responsible maritime supply operations.",
                    ],
                    [
                        'label' => 'Republic Day 100th Anniversary',
                        'slug' => 'republic-day-100th-anniversary',
                        'published_at' => '2023-10-29',
                        'page_content' => "We proudly celebrated the Republic Day 100th Anniversary with our teams and partners.\n\nAs a maritime service company, we continue to build our future on strong national values.",
                    ],
                    [
                        'label' => 'Visit our stand at IMPA London 2023',
                        'slug' => 'visit-our-stand-at-impa-london-2023',
                        'published_at' => '2023-09-05',
                        'page_content' => "Thank you for visiting our stand during IMPA London 2023.\n\nWe showcased our operational strength, global network, and onboard service solutions.",
                    ],
                    [
                        'label' => 'Occupational Health and Safety',
                        'slug' => 'occupational-health-and-safety',
                        'published_at' => '2023-06-20',
                        'page_content' => "Occupational health and safety remains one of our core priorities.\n\nWe continuously improve our standards to ensure safe and reliable operations at every location.",
                    ],
                ],
                '/blog/events' => [
                    [
                        'label' => 'IMPA London 2024 Event Highlights',
                        'slug' => 'impa-london-2024-event-highlights',
                        'published_at' => '2024-09-12',
                        'page_content' => "A short roundup from IMPA London 2024, including supplier meetings and operational innovation showcases.",
                    ],
                    [
                        'label' => 'Posidonia 2024 Networking Day',
                        'slug' => 'posidonia-2024-networking-day',
                        'published_at' => '2024-06-06',
                        'page_content' => "Our team connected with global maritime stakeholders during Posidonia 2024 in Athens.",
                    ],
                ],
                '/blog/gallery' => [
                    [
                        'label' => 'London Event Gallery',
                        'slug' => 'london-event-gallery',
                        'published_at' => '2024-09-15',
                        'page_content' => "Photo gallery from London maritime events and team activities.",
                    ],
                    [
                        'label' => 'Port Operations Gallery',
                        'slug' => 'port-operations-gallery',
                        'published_at' => '2024-08-02',
                        'page_content' => "Snapshots from daily port operations, logistics coordination, and vessel support.",
                    ],
                ],
                '/blog/recipes' => [
                    [
                        'label' => 'Chef Special: Mediterranean Menu',
                        'slug' => 'chef-special-mediterranean-menu',
                        'published_at' => '2024-07-20',
                        'page_content' => "A practical, vessel-friendly Mediterranean meal plan prepared by our onboard catering partners.",
                    ],
                    [
                        'label' => 'Healthy Crew Breakfast Ideas',
                        'slug' => 'healthy-crew-breakfast-ideas',
                        'published_at' => '2024-06-11',
                        'page_content' => "Simple, energy-rich breakfast suggestions designed for long operations at sea.",
                    ],
                ],
                '/blog/newport-tv' => [
                    [
                        'label' => 'Newport TV: Behind the Supply Chain',
                        'slug' => 'newport-tv-behind-the-supply-chain',
                        'published_at' => '2024-05-25',
                        'page_content' => "Video story: how our teams coordinate procurement, warehousing, and rapid vessel delivery.",
                    ],
                    [
                        'label' => 'Newport TV: A Day in Rotterdam',
                        'slug' => 'newport-tv-a-day-in-rotterdam',
                        'published_at' => '2024-04-18',
                        'page_content' => "Operational walkthrough from our Rotterdam base, from request intake to onboard completion.",
                    ],
                ],
                default => [],
            };

            foreach ($posts as $index => $post) {
                SubMenu::query()->create([
                    'menu_id' => $blog->id,
                    'parent_sub_menu_id' => $category->id,
                    'label' => $post['label'],
                    'url' => "{$page['url']}/{$post['slug']}",
                    'page_content' => $post['page_content'],
                    'published_at' => $post['published_at'],
                    'sort_order' => $index,
                    'is_active' => true,
                ]);
            }
        }
    }
}
