<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ContactMessageController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ContactMessage::class);

        return view('admin.contact-messages.index', [
            'messages' => ContactMessage::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(ContactMessage $contactMessage): View
    {
        $this->authorize('view', $contactMessage);

        if ($contactMessage->status === 'unread') {
            $contactMessage->update(['status' => 'read']);
            AuditLogger::log('admin.contact_message.read', $contactMessage);
        }

        return view('admin.contact-messages.show', ['message' => $contactMessage->fresh()]);
    }

    public function destroy(ContactMessage $contactMessage): RedirectResponse
    {
        $this->authorize('delete', $contactMessage);

        $contactMessage->delete();
        AuditLogger::log('admin.contact_message.deleted', $contactMessage);

        return redirect()->route('admin.contact-messages.index')->with('status', 'Contact message deleted.');
    }
}
