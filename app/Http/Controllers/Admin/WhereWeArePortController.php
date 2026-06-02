<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhereWeAreLocation;
use App\Models\WhereWeArePort;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class WhereWeArePortController extends Controller
{
    public function edit(WhereWeAreLocation $where_we_are_location, string $port): View
    {
        $this->authorize('update', $where_we_are_location);
        $portModel = $this->resolvePort($where_we_are_location, $port);

        return view('admin.where-we-are-ports.edit', [
            'location' => $where_we_are_location,
            'port' => $portModel,
        ]);
    }

    public function update(Request $request, WhereWeAreLocation $where_we_are_location, string $port): RedirectResponse
    {
        $this->authorize('update', $where_we_are_location);
        $portModel = $this->resolvePort($where_we_are_location, $port);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'map_query' => ['nullable', 'string', 'max:500'],
            'map_embed' => ['nullable', 'string', 'max:10000'],
            'body_paragraphs' => ['nullable', 'array'],
            'body_paragraphs.*' => ['nullable', 'string', 'max:5000'],
            'footer_link_label' => ['nullable', 'string', 'max:255'],
            'footer_link_url' => ['nullable', 'string', 'max:500'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ]);

        $data['body_paragraphs'] = array_values(array_filter(array_map(
            fn ($v) => is_string($v) ? trim($v) : '',
            $data['body_paragraphs'] ?? [],
        ), fn ($v) => $v !== ''));
        $data['is_active'] = $request->boolean('is_active');

        $portModel->fill($data);
        $portModel->save();

        return redirect()
            ->route('admin.where-we-are-ports.edit', [$where_we_are_location, $portModel->slug])
            ->with('status', 'Port updated.');
    }

    private function resolvePort(WhereWeAreLocation $location, string $portSlug): WhereWeArePort
    {
        return WhereWeArePort::query()
            ->where('where_we_are_location_id', $location->id)
            ->where('slug', $portSlug)
            ->firstOrFail();
    }
}
