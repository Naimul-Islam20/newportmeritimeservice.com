<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Models\SiteDetail;
use App\Support\AuditLogger;
use App\Support\ContactOffices;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContactController extends Controller
{
    public function create(): View
    {
        $siteDetails = SiteDetail::query()->first();
        
        $menu = \App\Models\Menu::where('url', '/contact')->orWhere('url', 'contact')->first();
        $heroImageUrl = $menu && $menu->cover_image_path 
            ? $menu->coverImageUrl() 
            : 'https://images.unsplash.com/photo-1586528116311-ad8ed7c80bc2?q=80&w=2070&auto=format&fit=crop';

        return view('site.pages.contact', [
            'siteDetails' => $siteDetails,
            'offices' => ContactOffices::forContactPage($siteDetails),
            'heroImageUrl' => $heroImageUrl,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        if ($request->boolean('footer_newsletter')) {
            return app(NewsletterSubscriptionController::class)->storeFromRequest($request);
        }

        $contactRequest = StoreContactMessageRequest::createFrom($request);
        $contactRequest->setContainer(app())->setRedirector(app('redirect'));
        $contactRequest->validateResolved();

        return $this->storeContactMessage($contactRequest);
    }

    private function storeContactMessage(StoreContactMessageRequest $request): RedirectResponse
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
