<?php

use App\Models\CeoMessagePage;
use App\Models\OurStoryPage;
use App\Models\OurTeamPage;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        if (! OurStoryPage::query()->exists()) {
            OurStoryPage::query()->create([
                'hero_title' => 'Our Story',
                'meta_description' => 'Our story since 1992 — maritime supply, provision and logistics across ports worldwide.',
                'eyebrow' => 'Our Story',
                'section_title' => 'Since 1992',
                'intro_paragraphs' => [
                    'Since the beginning of the story, many things have changed… Our experiences on this way have carried us to be a pioneer company that can provide all kinds of requirements of our clients.',
                    'Today, we are operating in three different countries and hundreds of ports with the same enthusiasm and motivation like the first day.',
                    'With our intense efforts, dynamic professional staff and primary principles of honesty and service quality, we are happy to be a part of maritime industry also in the future.',
                ],
                'milestones' => [
                    ['year' => '2020', 'title' => 'Mersin (new warehouse)', 'text' => 'A new warehouse very close to the Port of Mersin was opened in 2020. This has a 2000 m² capacity in order to service your needs efficiently.', 'image_path' => null],
                    ['year' => '2016', 'title' => 'Rotterdam', 'text' => 'We crowned decades of experience in ship supply with our Rotterdam operations centre.', 'image_path' => null],
                    ['year' => '2015', 'title' => 'Mersin', 'text' => 'Our services were rewarded by our customers and requests increased. We invested in an office, warehouse and entrepôt in 2015 to speed up services to vessels calling ports in the Mediterranean area.', 'image_path' => null],
                    ['year' => '2014', 'title' => 'Athens', 'text' => 'Our Athens marketing office has continued to serve the shipping industry with the support of our partners since 2014.', 'image_path' => null],
                    ['year' => '2005', 'title' => 'Tuzla', 'text' => 'In order to maintain and improve our services, we opened our Tuzla branch office close to the shipyards of this area.', 'image_path' => null],
                    ['year' => '1992', 'title' => 'Istanbul', 'text' => 'Our company was founded in 1992 with a commitment to honest service and quality supply for every vessel we support.', 'image_path' => null],
                ],
            ]);
        }

        if (! CeoMessagePage::query()->exists()) {
            CeoMessagePage::query()->create([
                'hero_title' => 'We believe in the future',
                'meta_description' => 'A message from our CEO on experience, trust, and our vision for maritime supply and logistics.',
                'eyebrow' => 'Message from the CEO',
                'salutation' => 'Dear Business Partners and Our Esteemed Colleagues;',
                'paragraphs' => [
                    'I am proud to express our Company with these qualifications; more than 30 years experience, deep rooted corporate culture, quality & trust based road map and global working vision.',
                    'We have been taking firm steps forward since our establishment. Currently, with the vision of “Global Reach Personal Touch”, we continue our operations in four different countries. Transparency, trust and customer satisfaction which are the basic principles of the Company, lead us as a leader in the Sector.',
                    'Our Company increases productivity in its logistic operations with advanced technology. Thus, smooth transportation and delivery process of the company carry the customer satisfaction to new high levels. Furthermore, our working principle is tailor made solutions to the Customers with the high qualified team and experience. Products are certified for using in the marine environment and supported with approvals from the major classification societies.',
                    'We are responsible for the impacts of all our activities, related with products, transportation, storage and operations, regionally and internationally. Awareness in sustainability is the first and most important step of the Company. We go on our road with synergy which is coming from devoted human resources.',
                    'As Newport Maritime Service, we are proud of our more than 30 years of experience which will be our light for the coming years. Moreover, service with high business ethics will continue to be our indispensable value.',
                    'We would like to thank our colleagues for their solidarity in all our achievements and our business partners & customers for their support and trust.',
                    'We wish many successful years together.',
                ],
                'signature_name' => 'Zihni Memisoglu',
                'signature_role' => 'CEO, Founder',
            ]);
        }

        if (! OurTeamPage::query()->exists()) {
            OurTeamPage::query()->create([
                'hero_title' => 'Our Team',
                'meta_description' => 'Meet our management team and leadership across maritime supply, provision and operations.',
                'breadcrumb_label' => 'Management',
                'page_title' => 'Management',
                'regional_nav' => [
                    ['label' => 'Rotterdam', 'url' => '#'],
                    ['label' => 'Mersin', 'url' => '#'],
                    ['label' => 'Tuzla', 'url' => '#'],
                    ['label' => 'Athens', 'url' => '#'],
                ],
                'category_nav' => [
                    ['label' => 'Sales', 'url' => '#'],
                    ['label' => 'Technical Stores', 'url' => '/technical-stores'],
                    ['label' => 'Provision', 'url' => '/ship-supply'],
                    ['label' => 'Procurement', 'url' => '#'],
                    ['label' => 'Customs', 'url' => '#'],
                    ['label' => 'Operations', 'url' => '/our-services'],
                    ['label' => 'Finance & Accounting', 'url' => '#'],
                    ['label' => 'Human Resources', 'url' => '#'],
                ],
                'team_sections' => [
                    [
                        'heading' => 'Management',
                        'members' => [
                            [
                                'name' => 'Zihni Memişoğlu',
                                'role' => 'Managing Director, CEO',
                                'email' => 'zihnim@gimas.com',
                                'phone' => null,
                                'photo_path' => null,
                            ],
                        ],
                    ],
                    [
                        'heading' => 'CEO Office Team',
                        'members' => [
                            [
                                'name' => 'Sema Mergen',
                                'role' => 'Strategic Analytics Manager',
                                'email' => 'sema.mergen@gimas.com',
                                'phone' => '+90 212 395 5121',
                                'photo_path' => null,
                            ],
                        ],
                    ],
                ],
            ]);
        }
    }

    public function down(): void
    {
        OurStoryPage::query()->delete();
        CeoMessagePage::query()->delete();
        OurTeamPage::query()->delete();
    }
};
