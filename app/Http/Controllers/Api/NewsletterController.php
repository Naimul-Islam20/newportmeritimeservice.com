<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Newsletter;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    public function index(): JsonResponse
    {
        $newsletters = Newsletter::query()
            ->with('category')
            ->latest('published_at')
            ->latest('id')
            ->paginate(20);

        return response()->json([
            'success' => true,
            'data' => $newsletters->getCollection()->map(function (Newsletter $newsletter): array {
                return [
                    'id' => $newsletter->id,
                    'title' => $newsletter->title,
                    'category' => $newsletter->category?->name,
                    'date' => $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d'),
                    'image_url' => asset($newsletter->image_path),
                    'description' => $newsletter->description,
                ];
            }),
            'meta' => [
                'current_page' => $newsletters->currentPage(),
                'last_page' => $newsletters->lastPage(),
                'per_page' => $newsletters->perPage(),
                'total' => $newsletters->total(),
            ],
        ]);
    }

    public function show(Newsletter $newsletter): JsonResponse
    {
        $newsletter->load('category');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $newsletter->id,
                'title' => $newsletter->title,
                'category' => $newsletter->category?->name,
                'date' => $newsletter->published_at?->format('Y-m-d') ?? $newsletter->created_at?->format('Y-m-d'),
                'image_url' => asset($newsletter->image_path),
                'description' => $newsletter->description,
                'created_at' => $newsletter->created_at,
                'updated_at' => $newsletter->updated_at,
            ],
        ]);
    }
}
