<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class PageController extends Controller
{
    public function shipSupply(): View
    {
        return view('site.pages.simple', [
            'title' => 'Ship Supply — '.config('app.name'),
            'metaDescription' => 'Provisions, stores, and deck supplies for vessels and port operations.',
            'heading' => 'Ship Supply',
            'lead' => 'Reliable sourcing and delivery for your fleet—aligned with port schedules and compliance requirements.',
        ]);
    }

    public function ourServices(): View
    {
        return view('site.pages.simple', [
            'title' => 'Our Services — '.config('app.name'),
            'metaDescription' => 'Maritime logistics, documentation, and operational support services.',
            'heading' => 'Our Services',
            'lead' => 'From berth coordination to stakeholder communication, we support the full lifecycle of your port call.',
        ]);
    }

    public function award(): View
    {
        return view('site.pages.simple', [
            'title' => 'Award — '.config('app.name'),
            'metaDescription' => 'Recognition and milestones from our partners and the industry.',
            'heading' => 'Award',
            'lead' => 'We are proud of the trust our clients place in us. Details and highlights will appear here as we update this section.',
        ]);
    }

    public function quote(): View
    {
        return view('site.pages.quote', [
            'title' => 'Get a quote — '.config('app.name'),
            'metaDescription' => 'Request a quote for ship supply, port services, or logistics support.',
        ]);
    }
}
