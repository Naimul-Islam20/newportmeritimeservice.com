<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactFormController extends Controller
{
    public function create(): View
    {
        return view('contact.create');
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

        $contactMessage = ContactMessage::create($payload);
        AuditLogger::log('contact.submitted.web', $contactMessage, [], $request);

        return back()->with('status', 'Thank you! Your message has been sent successfully.');
    }
}
