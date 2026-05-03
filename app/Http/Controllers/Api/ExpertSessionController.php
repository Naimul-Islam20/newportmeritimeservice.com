<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreExpertSessionRequest;
use App\Models\ExpertSession;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;

class ExpertSessionController extends Controller
{
    public function store(StoreExpertSessionRequest $request): JsonResponse
    {
        $payload = $request->safe()->only([
            'name',
            'company_name',
            'designation',
            'mobile',
            'email',
        ]);

        $expertSession = ExpertSession::create($payload);
        AuditLogger::log('expert_session.submitted.api', $expertSession, [], $request);

        return response()->json([
            'success' => true,
            'message' => 'Expert session request submitted successfully.',
            'data' => [
                'id' => $expertSession->id,
                'status' => $expertSession->status,
                'created_at' => $expertSession->created_at,
            ],
        ], 201);
    }
}
