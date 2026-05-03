<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreQuoteRequest;
use App\Models\QuoteRequest;
use App\Support\AuditLogger;
use Illuminate\Http\JsonResponse;

class QuoteRequestController extends Controller
{
    public function store(StoreQuoteRequest $request): JsonResponse
    {
        $payload = $request->safe()->only([
            'name',
            'designation',
            'company_name',
            'employee_count',
            'modules_needed',
            'email',
            'mobile_no',
            'address',
            'description',
        ]);

        $payload['modules_needed'] = collect($payload['modules_needed'] ?? [])
            ->map(fn (string $module): string => strtolower(trim($module)))
            ->values()
            ->all();

        $quoteRequest = QuoteRequest::create($payload);
        AuditLogger::log('quote_request.submitted.api', $quoteRequest, [], $request);

        return response()->json([
            'success' => true,
            'message' => 'Quote request submitted successfully.',
            'data' => [
                'id' => $quoteRequest->id,
                'status' => $quoteRequest->status,
                'created_at' => $quoteRequest->created_at,
            ],
        ], 201);
    }
}
