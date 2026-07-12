<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\NewsletterSubscription;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NewsletterSubscriptionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', NewsletterSubscription::class);

        return view('admin.newsletter-subscriptions.index', [
            'subscriptions' => NewsletterSubscription::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(NewsletterSubscription $newsletterSubscription): View
    {
        $this->authorize('view', $newsletterSubscription);

        if ($newsletterSubscription->status === 'unread') {
            $newsletterSubscription->update(['status' => 'read']);
            AuditLogger::log('admin.newsletter_subscription.read', $newsletterSubscription);
        }

        return view('admin.newsletter-subscriptions.show', [
            'subscription' => $newsletterSubscription->fresh(),
        ]);
    }

    public function destroy(NewsletterSubscription $newsletterSubscription): RedirectResponse
    {
        $this->authorize('delete', $newsletterSubscription);

        $newsletterSubscription->delete();
        AuditLogger::log('admin.newsletter_subscription.deleted', $newsletterSubscription);

        return redirect()
            ->route('admin.newsletter-subscriptions.index')
            ->with('status', 'Newsletter registration deleted.');
    }
}
