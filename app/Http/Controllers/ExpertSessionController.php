<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreExpertSessionRequest;
use App\Models\ExpertSession;
use App\Support\AuditLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ExpertSessionController extends Controller
{
    public function create(): View
    {
        return view('expert-session.create');
    }

    public function store(StoreExpertSessionRequest $request): RedirectResponse
    {
        $payload = $request->safe()->only([
            'name',
            'company_name',
            'designation',
            'mobile',
            'email',
        ]);

        $expertSession = ExpertSession::create($payload);
        AuditLogger::log('expert_session.submitted.web', $expertSession, [], $request);

        return back()->with('status', 'Thanks! Your expert session request has been submitted.');
    }
}
