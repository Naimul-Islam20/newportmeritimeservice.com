<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreNewsletterSubscriptionRequest;
use App\Models\NewsletterSubscription;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;

class NewsletterSubscriptionController extends Controller
{
    public function store(StoreNewsletterSubscriptionRequest $request): RedirectResponse
    {
        return $this->subscribe(strtolower(trim((string) $request->validated('email'))), $request);
    }

    public function storeFromRequest(Request $request): RedirectResponse
    {
        $validated = Validator::make(
            $request->all(),
            (new StoreNewsletterSubscriptionRequest)->rules(),
        )->validate();

        return $this->subscribe(strtolower(trim((string) $validated['email'])), $request);
    }

    private function subscribe(string $email, Request $request): RedirectResponse
    {
        if (! Schema::hasTable('newsletter_subscriptions')) {
            return back()->with('newsletter_status', 'Newsletter registration is temporarily unavailable. Please try again later.');
        }

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
