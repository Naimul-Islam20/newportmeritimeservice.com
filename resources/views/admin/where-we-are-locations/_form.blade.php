@php
    use App\Models\WhereWeAreLocation;
    use App\Support\ContactOffices;
    $isEdit = $location->exists;
    $contactTab = collect(ContactOffices::branchDefinitions())
        ->first(fn (array $branch) => ($branch['location_slug'] ?? '') === ($location->slug ?? ''));
@endphp

<h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
<div class="grid grid-2">
    <div>
        <label for="hero_title">City / location name (H1)</label>
        <input id="hero_title" name="hero_title" value="{{ old('hero_title', $location->hero_title) }}" required>
        @error('hero_title') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div>
        <label for="slug">URL slug (optional — auto from title)</label>
        <input id="slug" name="slug" value="{{ old('slug', $location->slug) }}" placeholder="istanbul">
        @error('slug') <div class="error">{{ $message }}</div> @enderror
    </div>
    <div style="grid-column:1/-1;">
        <label for="meta_description">Meta description</label>
        <input id="meta_description" name="meta_description" value="{{ old('meta_description', $location->meta_description) }}">
    </div>
    <div style="grid-column:1/-1;">
        <label for="hero_background_file">Hero background image</label>
        <input id="hero_background_file" name="hero_background_file" type="file" accept="image/*">
        @if (filled($location->hero_background))
            <div style="margin-top:8px;">
                <img src="{{ WhereWeAreLocation::imageSrc($location->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                    <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                </label>
            </div>
        @endif
    </div>
</div>

<h2 style="margin:24px 0 12px;font-size:15px;">Sidebar (Gimaş left column)</h2>
<div class="grid grid-2">
    <div>
        <label for="region_label">Region heading (e.g. TURKEY)</label>
        <input id="region_label" name="region_label" value="{{ old('region_label', $location->region_label) }}" placeholder="TURKEY">
    </div>
    <div>
        <label for="sidebar_label">Sidebar link label</label>
        <input id="sidebar_label" name="sidebar_label" value="{{ old('sidebar_label', $location->sidebar_label) }}" placeholder="Istanbul Head Office & Warehouse">
    </div>
</div>
<label style="margin-top:12px;display:block;">Extra sidebar links (label + URL, use #where-location-quality for on-page anchor)</label>
<div data-repeater-wrap="tpl-sidebar-extra">
    @php($extras = old('sidebar_extras', $location->sidebar_extras ?? []))
    @if (! is_array($extras) || count($extras) < 1) @php($extras = [['label' => '', 'url' => '']]) @endif
    @foreach ($extras as $ei => $extra)
        <div data-repeater-item style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;margin-bottom:8px;">
            <input name="sidebar_extras[{{ $ei }}][label]" value="{{ is_array($extra) ? ($extra['label'] ?? '') : '' }}" placeholder="Quality Certificates & Memberships">
            <input name="sidebar_extras[{{ $ei }}][url]" value="{{ is_array($extra) ? ($extra['url'] ?? '') : '' }}" placeholder="#where-location-quality">
            <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
        </div>
    @endforeach
</div>
<button type="button" class="btn btn-muted" data-add-row="tpl-sidebar-extra">Add sidebar link</button>

<h2 style="margin:24px 0 12px;font-size:15px;">Page content</h2>
<div class="grid grid-2">
    <div>
        <label for="eyebrow">Eyebrow (above title)</label>
        <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $location->eyebrow ?: 'Where We Are') }}">
    </div>
    <div>
        <label for="office_title">Office block title</label>
        <input id="office_title" name="office_title" value="{{ old('office_title', $location->office_title) }}">
    </div>
    <div>
        <label for="body_link_label">Inline link label (last paragraph)</label>
        <input id="body_link_label" name="body_link_label" value="{{ old('body_link_label', $location->body_link_label) }}" placeholder="All Ports of Turkey">
    </div>
    <div>
        <label for="body_link_url">Inline link URL</label>
        <input id="body_link_url" name="body_link_url" value="{{ old('body_link_url', $location->body_link_url) }}">
    </div>
</div>

<label style="margin-top:12px;display:block;">Body paragraphs</label>
<div data-repeater-wrap="tpl-body">
    @php($paragraphs = old('body_paragraphs', $location->body_paragraphs ?? []))
    @if (! is_array($paragraphs) || count($paragraphs) < 1) @php($paragraphs = ['']) @endif
    @foreach ($paragraphs as $i => $para)
        <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
            <textarea name="body_paragraphs[{{ $i }}]" rows="4" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
            <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
        </div>
    @endforeach
</div>
<button type="button" class="btn btn-muted" data-add-row="tpl-body">Add paragraph</button>

<h2 style="margin:24px 0 12px;font-size:15px;">Map</h2>
<div class="grid grid-2">
    <div style="grid-column:1/-1;">
        <label for="map_query">Google Maps search query</label>
        <input id="map_query" name="map_query" value="{{ old('map_query', $location->map_query) }}" placeholder="Office address for embed map">
    </div>
    <div style="grid-column:1/-1;">
        <label for="map_embed">Google Maps embed iframe (optional)</label>
        <textarea id="map_embed" name="map_embed" rows="3">{{ old('map_embed', $location->map_embed) }}</textarea>
    </div>
</div>

<h2 style="margin:24px 0 12px;font-size:15px;">Contact page — {{ data_get($contactTab, 'label', 'office tab') }}</h2>
<p style="margin:0 0 12px;color:#64748b;font-size:13px;line-height:1.5;">
    @if (filled($contactTab))
        Shown on the public <strong>Contact</strong> page when visitors select the <strong>{{ data_get($contactTab, 'label') }}</strong> tab (address + map below the form).
    @else
        This location slug is not linked to a Contact page tab. Contact tabs use slugs:
        @foreach (ContactOffices::branchDefinitions() as $branch)
            <code>{{ $branch['location_slug'] }}</code>@if (! $loop->last), @endif
        @endforeach
    @endif
</p>
<div class="grid grid-2">
    <div style="grid-column:1/-1;">
        <label for="contact_address">Office address (Contact page)</label>
        <textarea id="contact_address" name="contact_address" rows="3" placeholder="e.g. 1110/B, Hasna Tower (6th Floor), Agrabad C/A, Chittagong.">{{ old('contact_address', $location->contact_address) }}</textarea>
    </div>
    <div style="grid-column:1/-1;">
        <label for="contact_map_query">Map search query (optional)</label>
        <input id="contact_map_query" name="contact_map_query" value="{{ old('contact_map_query', $location->contact_map_query) }}" placeholder="Full address or 22.3279216,91.8160141">
        <div style="color:#64748b;font-size:12px;margin-top:6px;">Used when latitude/longitude are empty. Paste an address or coordinates <code>lat,lng</code>.</div>
    </div>
    <div>
        <label for="contact_map_lat">Map latitude (optional)</label>
        <input id="contact_map_lat" name="contact_map_lat" type="number" step="any" value="{{ old('contact_map_lat', $location->contact_map_lat) }}" placeholder="22.3279216">
    </div>
    <div>
        <label for="contact_map_lng">Map longitude (optional)</label>
        <input id="contact_map_lng" name="contact_map_lng" type="number" step="any" value="{{ old('contact_map_lng', $location->contact_map_lng) }}" placeholder="91.8160141">
    </div>
    <div>
        <label for="contact_map_zoom">Map zoom (1–21)</label>
        <input id="contact_map_zoom" name="contact_map_zoom" type="number" min="1" max="21" value="{{ old('contact_map_zoom', $location->contact_map_zoom ?? 18) }}" placeholder="18">
    </div>
    <div style="grid-column:1/-1;">
        <label for="contact_map_embed">Google Maps embed iframe (optional)</label>
        <textarea id="contact_map_embed" name="contact_map_embed" rows="3" placeholder="Paste iframe from Google Maps → Share → Embed a map">{{ old('contact_map_embed', $location->contact_map_embed) }}</textarea>
        <div style="color:#64748b;font-size:12px;margin-top:6px;">If set, this overrides the search query and coordinates above.</div>
    </div>
</div>

<h2 style="margin:24px 0 12px;font-size:15px;">Brochure</h2>
<div class="grid grid-2">
    <div>
        <label for="brochure_label">Button label</label>
        <input id="brochure_label" name="brochure_label" value="{{ old('brochure_label', $location->brochure_label ?: 'Download Brochure PDF') }}">
    </div>
    <div>
        <label for="brochure_url">External URL (if no file)</label>
        <input id="brochure_url" name="brochure_url" value="{{ old('brochure_url', $location->brochure_url) }}">
    </div>
    <div style="grid-column:1/-1;">
        <label for="brochure_lead">Sidebar brochure lead text</label>
        <input id="brochure_lead" name="brochure_lead" value="{{ old('brochure_lead', $location->brochure_lead) }}">
    </div>
    <div style="grid-column:1/-1;">
        <label for="brochure_file_upload">Brochure PDF / file</label>
        <input id="brochure_file_upload" name="brochure_file_upload" type="file">
        @if (filled($location->brochure_file))
            <p style="margin:8px 0 0;font-size:13px;color:#64748b;">Current: {{ basename($location->brochure_file) }}</p>
            <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                <input type="checkbox" name="remove_brochure_file" value="1"> Remove file
            </label>
        @endif
    </div>
</div>

<h2 style="margin:24px 0 12px;font-size:15px;">Quality block</h2>
<label style="display:flex;gap:8px;margin-bottom:12px;font-size:14px;">
    <input type="checkbox" name="show_quality_block" value="1" @checked(old('show_quality_block', $location->show_quality_block ?? true))>
    Show quality certificates section
</label>
<div class="grid grid-2">
    <div>
        <label for="quality_block_title">Section title</label>
        <input id="quality_block_title" name="quality_block_title" value="{{ old('quality_block_title', $location->quality_block_title ?: 'Quality Certificates & Memberships') }}">
    </div>
    <div style="grid-column:1/-1;">
        <label for="quality_block_lead">Certificates intro (e.g. Click on the image…)</label>
        <input id="quality_block_lead" name="quality_block_lead" value="{{ old('quality_block_lead', $location->quality_block_lead) }}">
    </div>
    <div>
        <label for="certificate_group_slug">Certificates group slug</label>
        <input id="certificate_group_slug" name="certificate_group_slug" value="{{ old('certificate_group_slug', $location->certificate_group_slug) }}" placeholder="turkey-quality-certificates">
    </div>
    <div>
        <label for="membership_group_slug">Membership group slug</label>
        <input id="membership_group_slug" name="membership_group_slug" value="{{ old('membership_group_slug', $location->membership_group_slug) }}" placeholder="membership-turkey">
    </div>
</div>

<h2 style="margin:24px 0 12px;font-size:15px;">Contact CTA</h2>
<div class="grid grid-2">
    <div>
        <label for="contact_cta_label">Button label</label>
        <input id="contact_cta_label" name="contact_cta_label" value="{{ old('contact_cta_label', $location->contact_cta_label ?: 'Contact Us') }}">
    </div>
    <div>
        <label for="contact_cta_url">Button URL</label>
        <input id="contact_cta_url" name="contact_cta_url" value="{{ old('contact_cta_url', $location->contact_cta_url ?: '/contact') }}">
    </div>
    <div>
        <label for="sort_order">Sort order (nav)</label>
        <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $location->sort_order ?? 0) }}">
    </div>
    <div>
        <label for="is_active">Status</label>
        <select id="is_active" name="is_active">
            <option value="1" @selected(old('is_active', $location->is_active ?? true))>Active</option>
            <option value="0" @selected(! old('is_active', $location->is_active ?? true))>Hidden</option>
        </select>
    </div>
</div>

<template id="tpl-sidebar-extra">
    <div data-repeater-item style="display:grid;grid-template-columns:1fr 1fr auto;gap:8px;margin-bottom:8px;">
        <input name="sidebar_extras[__INDEX__][label]" placeholder="Label">
        <input name="sidebar_extras[__INDEX__][url]" placeholder="URL">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

<template id="tpl-body">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="body_paragraphs[__INDEX__]" rows="4" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
