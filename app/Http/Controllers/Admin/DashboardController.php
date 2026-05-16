<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\QuoteRequest;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        $this->authorize('viewAny', User::class);

        return view('admin.dashboard', [
            'totalUsers' => User::count(),
            'unreadContactMessages' => ContactMessage::where('status', 'unread')->count(),
            'unreadQuoteRequests' => QuoteRequest::where('status', 'unread')->count(),
        ]);
    }
}
