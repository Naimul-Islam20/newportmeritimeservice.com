<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Mail\QuoteRequestSubmitted;
use App\Models\QuoteRequest;
use App\Models\SiteDetail;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;
use Throwable;

class QuoteRequestController extends Controller
{
    public function create(): View
    {
        return view('site.pages.quote', [
            'title' => SiteDetail::pageTitle('Get a quote'),
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

        try {
            Mail::send(new QuoteRequestSubmitted($quoteRequest));
        } catch (Throwable $e) {
            Log::error('Failed to email quote request notification.', [
                'quote_request_id' => $quoteRequest->id,
                'recipient' => config('mail.quote_notification_address'),
                'mailer' => config('mail.default'),
                'exception' => $e->getMessage(),
            ]);
        }

        return back()->with('status', 'Thank you! Your quote request has been received. We will get back to you soon.');
    }
}
