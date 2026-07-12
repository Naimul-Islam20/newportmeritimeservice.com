<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscription;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriptionRequest $request): RedirectResponse
    {
        $email = strtolower(trim((string) $request->validated('email')));

        $subscription = NewsletterSubscription::query()->where('email', $email)->first();

        if ($subscription) {
            return back()
                ->withInput()
                ->with('newsletter_status', 'This email is already registered for our newsletter.');
        }

        $subscription = NewsletterSubscription::create([
            'email' => $email,
            'status' => 'unread',
        ]);

        AuditLogger::log('newsletter.subscribed.web', $subscription, [], $request);

        return back()->with('newsletter_status', 'Thank you! You have been registered for our newsletter.');
    }
}
