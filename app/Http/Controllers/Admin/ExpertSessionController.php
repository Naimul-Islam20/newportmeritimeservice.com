<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExpertSession;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpertSessionController extends Controller
{
    public function index(): View
    {
        $this->authorize('viewAny', ExpertSession::class);

        return view('admin.expert-sessions.index', [
            'expertSessions' => ExpertSession::query()->latest()->paginate(20)->withQueryString(),
        ]);
    }

    public function show(ExpertSession $expertSession): View
    {
        $this->authorize('view', $expertSession);

        if ($expertSession->status === 'new') {
            $expertSession->update(['status' => 'reviewed']);
            AuditLogger::log('admin.expert_session.reviewed', $expertSession);
        }

        return view('admin.expert-sessions.show', [
            'expertSession' => $expertSession->fresh(),
        ]);
    }

    public function destroy(ExpertSession $expertSession): RedirectResponse
    {
        $this->authorize('delete', $expertSession);

        $expertSession->delete();
        AuditLogger::log('admin.expert_session.deleted', $expertSession);

        return redirect()->route('admin.expert-sessions.index')->with('status', 'Expert session request deleted.');
    }
}
