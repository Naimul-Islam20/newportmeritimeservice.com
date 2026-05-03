<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreContactMessageRequest;
use App\Models\ContactMessage;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    /**
     * Submit contact form (JSON). Same handler for /api/contact, /api/contact/submit, /api/contact-messages.
     */
    public function store(StoreContactMessageRequest $request): JsonResponse
    {
        $validated = $request->safe()->only([
            'full_name',
            'email',
            'phone',
            'subject',
            'message',
        ]);

        $contactMessage = ContactMessage::create($validated);
        AuditLogger::log('contact.submitted', $contactMessage, [], $request);

        return response()->json([
            'success' => true,
            'message' => 'Message submitted successfully!',
            'data' => [
                'id' => $contactMessage->id,
                'full_name' => $contactMessage->full_name,
                'email' => $contactMessage->email,
                'phone' => $contactMessage->phone,
                'subject' => $contactMessage->subject,
                'status' => $contactMessage->status,
                'created_at' => $contactMessage->created_at,
            ],
        ], 201);
    }

    /** @deprecated Use store() — kept for backwards compatibility */
    public function submitMessage(StoreContactMessageRequest $request): JsonResponse
    {
        return $this->store($request);
    }
}
