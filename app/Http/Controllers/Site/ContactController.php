<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\SiteDetail;
use App\Support\AuditLogger;
use App\Support\ContactOffices;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        $siteDetails = SiteDetail::query()->first();

        return view('site.pages.contact', [
            'siteDetails' => $siteDetails,
            'offices' => ContactOffices::forContactPage($siteDetails),
        ]);
    }

    public function store(StoreContactMessageRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only([
            'full_name',
            'email',
            'phone',
            'subject',
            'message',
        ]);

        if ($payload['phone'] === '') {
            $payload['phone'] = '—';
        }

        $contactMessage = ContactMessage::create($payload);
        AuditLogger::log('contact.submitted.web', $contactMessage, [], $request);

        return back()->with('status', 'Thank you! Your message has been sent successfully.');
    }
}
