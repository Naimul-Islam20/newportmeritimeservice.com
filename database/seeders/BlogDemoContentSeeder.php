<?php

namespace Database\Seeders;

use App\Models\Menu;
use App\Models\SubMenu;
use Illuminate\Database\Seeder;

class BlogDemoContentSeeder extends Seeder
{
    public function run(): void
    {
        $blog = Menu::query()
            ->where(function ($query): void {
                $query->where('url', '/blog')
                    ->orWhere('url', 'blog');
            })
            ->first();

        if (! $blog) {
            return;
        }

        $categories = [
            [
                'label' => 'News',
                'url' => '/blog/news',
                'sort_order' => 0,
                'posts' => [
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
                ],
            ],
            [
                'label' => 'Events',
                'url' => '/blog/events',
                'sort_order' => 1,
                'posts' => [
                    [
                        'label' => 'IMPA London 2024 Event Highlights',
                        'slug' => 'impa-london-2024-event-highlights',
                        'published_at' => '2024-09-12',
                        'page_content' => 'A short roundup from IMPA London 2024, including supplier meetings and operational innovation showcases.',
                    ],
                ],
            ],
            [
                'label' => 'Gallery',
                'url' => '/blog/gallery',
                'sort_order' => 2,
                'posts' => [
                    [
                        'label' => 'London Event Gallery',
                        'slug' => 'london-event-gallery',
                        'published_at' => '2024-09-15',
                        'page_content' => 'Photo gallery from London maritime events and team activities.',
                    ],
                ],
            ],
            [
                'label' => 'Recipes',
                'url' => '/blog/recipes',
                'sort_order' => 3,
                'posts' => [
                    [
                        'label' => 'Chef Special: Mediterranean Menu',
                        'slug' => 'chef-special-mediterranean-menu',
                        'published_at' => '2024-07-20',
                        'page_content' => 'A practical, vessel-friendly Mediterranean meal plan prepared by our onboard catering partners.',
                    ],
                ],
            ],
            [
                'label' => 'Newport TV',
                'url' => '/blog/newport-tv',
                'sort_order' => 4,
                'posts' => [
                    [
                        'label' => 'Newport TV: Behind the Supply Chain',
                        'slug' => 'newport-tv-behind-the-supply-chain',
                        'published_at' => '2024-05-25',
                        'page_content' => 'Video story: how our teams coordinate procurement, warehousing, and rapid vessel delivery.',
                    ],
                ],
            ],
        ];

        foreach ($categories as $categoryData) {
            $category = SubMenu::query()->updateOrCreate(
                [
                    'menu_id' => $blog->id,
                    'url' => $categoryData['url'],
                    'parent_sub_menu_id' => null,
                ],
                [
                    'label' => $categoryData['label'],
                    'sort_order' => $categoryData['sort_order'],
                    'is_active' => true,
                ],
            );

            foreach ($categoryData['posts'] as $index => $post) {
                SubMenu::query()->updateOrCreate(
                    [
                        'menu_id' => $blog->id,
                        'url' => "{$categoryData['url']}/{$post['slug']}",
                    ],
                    [
                        'parent_sub_menu_id' => $category->id,
                        'label' => $post['label'],
                        'page_content' => $post['page_content'],
                        'published_at' => $post['published_at'],
                        'sort_order' => $index,
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}

