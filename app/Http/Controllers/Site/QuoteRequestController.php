<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Models\QuoteRequest;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(): View
    {
        return view('site.pages.quote', [
            'title' => 'Get a quote — '.config('app.name'),
            'metaDescription' => 'Request a quote for ship supply, port services, or logistics support.',
        ]);
    }

    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only([
            'full_name',
            'email',
            'phone',
            'company',
            'vessel_or_reference',
            'request_details',
            'timeline',
        ]);

        $quoteRequest = QuoteRequest::create($payload);
        AuditLogger::log('quote.submitted.web', $quoteRequest, [], $request);

        return back()->with('status', 'Thank you! Your quote request has been received. We will get back to you soon.');
    }
}
