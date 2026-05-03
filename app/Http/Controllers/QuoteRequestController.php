<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\QuoteRequest;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function create(): View
    {
        return view('quote.create');
    }

    public function store(StoreQuoteRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only([
            'name',
            'designation',
            'company_name',
            'employee_count',
            'modules_needed',
            'email',
            'mobile_no',
            'address',
            'description',
        ]);

        $quoteRequest = QuoteRequest::create($payload);
        AuditLogger::log('quote_request.submitted', $quoteRequest, [], $request);

        return back()->with('status', 'Thanks! Your quote request has been submitted successfully.');
    }
}
