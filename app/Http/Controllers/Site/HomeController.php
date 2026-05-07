<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('site.pages.home', [
            'heroSlides' => HeroSlide::query()->ordered()->get(),
        ]);
    }
}
