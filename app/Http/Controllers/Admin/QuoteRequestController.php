<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\QuoteRequest;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class QuoteRequestController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', QuoteRequest::class);

        return view('admin.quote-requests.index', [
            'quoteRequests' => QuoteRequest::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(QuoteRequest $quoteRequest): View
    {
        $this->authorize('view', $quoteRequest);

        if ($quoteRequest->status === 'new') {
            $quoteRequest->update(['status' => 'reviewed']);
            AuditLogger::log('admin.quote_request.reviewed', $quoteRequest);
        }

        return view('admin.quote-requests.show', [
            'quoteRequest' => $quoteRequest->fresh(),
        ]);
    }

    public function destroy(QuoteRequest $quoteRequest): RedirectResponse
    {
        $this->authorize('delete', $quoteRequest);

        $quoteRequest->delete();
        AuditLogger::log('admin.quote_request.deleted', $quoteRequest);

        return redirect()->route('admin.quote-requests.index')->with('status', 'Quote request deleted.');
    }
}
