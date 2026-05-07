<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class HomeSectionController extends Controller
{
    public function index(): View
    {
        return view('admin.home-sections.index', [
            // Static placeholders until DB is wired.
            'sections' => [
                ['id' => 1, 'title' => 'Section 1', 'type' => '—', 'variant' => '—'],
                ['id' => 2, 'title' => 'Section 2', 'type' => '—', 'variant' => '—'],
                ['id' => 3, 'title' => 'Section 3', 'type' => '—', 'variant' => '—'],
            ],
        ]);
    }

    public function create(): View
    {
        return view('admin.home-sections.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $contentFieldRule = Rule::in(['mini_title', 'title', 'description', 'points', 'button']);

        $validated = $request->validate([
            'block_type' => ['required', 'in:carousel,two_column'],
            'carousel_variant' => ['nullable', 'in:simple,content_2,news'],
            'two_column_mode' => ['nullable', 'required_if:block_type,two_column', 'in:image_details,both_sides_details'],
            'fields_image' => ['nullable', 'array'],
            'fields_image.*' => ['string', $contentFieldRule],
            'fields_right' => ['nullable', 'array'],
            'fields_right.*' => ['string', $contentFieldRule],
            'fields_left' => ['nullable', 'array'],
            'fields_left.*' => ['string', $contentFieldRule],
        ]);

        if ($validated['block_type'] === 'carousel' && empty($validated['carousel_variant'])) {
            return back()->withErrors(['carousel_variant' => 'Please select a carousel type.'])->withInput();
        }

        if ($validated['block_type'] !== 'two_column') {
            return redirect()
                ->route('admin.home-sections.index')
                ->with('status', 'Carousel details flow will be added later.');
        }

        $mode = $validated['two_column_mode'] ?? null;
        if ($mode === 'image_details') {
            $fields = array_values(array_unique($validated['fields_image'] ?? []));
            if (count($fields) < 1) {
                return back()->withErrors(['fields_image' => 'Select at least one field next to the image.'])->withInput();
            }
            $request->session()->put('home_sections_draft', [
                'block_type' => 'two_column',
                'two_column_mode' => 'image_details',
                'fields_image' => $fields,
            ]);
        } elseif ($mode === 'both_sides_details') {
            $right = array_values(array_unique($validated['fields_right'] ?? []));
            $left = array_values(array_unique($validated['fields_left'] ?? []));
            if (count($right) < 1) {
                return back()->withErrors(['fields_right' => 'Select at least one field for the right side.'])->withInput();
            }
            if (count($left) < 1) {
                return back()->withErrors(['fields_left' => 'Select at least one field for the left side.'])->withInput();
            }
            $request->session()->put('home_sections_draft', [
                'block_type' => 'two_column',
                'two_column_mode' => 'both_sides_details',
                'fields_right' => $right,
                'fields_left' => $left,
            ]);
        } else {
            return back()->withErrors(['two_column_mode' => 'Choose Image + details or Details on both sides.'])->withInput();
        }

        return redirect()->route('admin.home-sections.details');
    }

    public function details(Request $request): View
    {
        $draft = $request->session()->get('home_sections_draft');

        if (! is_array($draft) || ($draft['block_type'] ?? null) !== 'two_column') {
            abort(404);
        }

        $mode = $draft['two_column_mode'] ?? null;

        if ($mode === 'image_details') {
            return view('admin.home-sections.details-carousel', [
                'fields' => $draft['fields_image'] ?? [],
            ]);
        }

        if ($mode === 'both_sides_details') {
            return view('admin.home-sections.details-two-column', [
                'fieldsRight' => $draft['fields_right'] ?? [],
                'fieldsLeft' => $draft['fields_left'] ?? [],
            ]);
        }

        abort(404);
    }

    public function saveDetails(Request $request): RedirectResponse
    {
        $request->session()->forget('home_sections_draft');

        return redirect()
            ->route('admin.home-sections.index')
            ->with('status', 'Details saved (static UI). Database connection will be added later.');
    }
}
