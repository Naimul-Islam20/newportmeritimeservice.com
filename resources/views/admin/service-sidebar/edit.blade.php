@extends('layouts.admin', ['title' => 'Service sidebar'])

@section('content')
<div class="header">
    <h1>Our Services — shared sidebar</h1>
    <a class="btn btn-muted" href="{{ route('admin.service-pages.index') }}">All service pages</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.service-sidebar.update', $setting) }}">
        @csrf
        @method('PUT')

        <div class="grid grid-2">
            <div style="grid-column:1/-1;">
                <label for="categories_title">Categories box title</label>
                <input id="categories_title" name="categories_title" value="{{ old('categories_title', $setting->categories_title) }}">
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Accordion groups</h2>
        @php($groups = old('nav_group_id') ? null : ($setting->nav_groups ?? []))
        @if (is_array(old('nav_group_id')))
            @php($groups = [])
            @foreach (old('nav_group_id', []) as $gi => $gid)
                @php($groups[] = [
                    'id' => $gid,
                    'label' => old('nav_group_label.'.$gi),
                    'children' => collect(old('nav_child_label.'.$gi, []))->map(fn ($l, $ci) => [
                        'label' => $l,
                        'href' => old('nav_child_href.'.$gi.'.'.$ci),
                    ])->values()->all(),
                ])
            @endforeach
        @endif
        @if (! is_array($groups) || count($groups) < 1)
            @php($groups = \App\Models\ServiceSidebarSetting::defaultContent()['nav_groups'])
        @endif

        <div data-repeater-wrap="tpl-nav-group">
            @foreach ($groups as $gi => $group)
                <div data-repeater-item style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:16px;">
                    <div class="grid grid-2">
                        <div>
                            <label>Group id (slug)</label>
                            <input name="nav_group_id[{{ $gi }}]" value="{{ $group['id'] ?? '' }}">
                        </div>
                        <div>
                            <label>Group label</label>
                            <input name="nav_group_label[{{ $gi }}]" value="{{ $group['label'] ?? '' }}">
                        </div>
                    </div>
                    <p style="margin:12px 0 8px;font-size:13px;font-weight:600;">Sub-items</p>
                    <div data-repeater-wrap="tpl-nav-child-{{ $gi }}">
                        @php($children = $group['children'] ?? [])
                        @if (count($children) < 1) @php($children = [['label' => '', 'href' => '#']]) @endif
                        @foreach ($children as $ci => $child)
                            <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
                                <input name="nav_child_label[{{ $gi }}][{{ $ci }}]" value="{{ $child['label'] ?? '' }}" placeholder="Label" style="flex:1;min-width:120px;">
                                <input name="nav_child_href[{{ $gi }}][{{ $ci }}]" value="{{ $child['href'] ?? '#' }}" placeholder="/path or URL" style="flex:1;min-width:140px;">
                                <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-muted" data-add-row="tpl-nav-child-{{ $gi }}">Add sub-item</button>
                    <button type="button" class="btn btn-danger" data-remove-row style="margin-top:12px;">Remove group</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-nav-group">Add group</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Bottom links (Transit Delivery, etc.)</h2>
        <div data-repeater-wrap="tpl-nav-link">
            @php($links = old('nav_link_label') ? null : ($setting->nav_links ?? []))
            @if (is_array(old('nav_link_label')))
                @php($links = [])
                @foreach (old('nav_link_label', []) as $li => $ll)
                    @php($links[] = ['label' => $ll, 'href' => old('nav_link_href.'.$li)])
                @endforeach
            @endif
            @if (! is_array($links) || count($links) < 1) @php($links = [['label' => '', 'href' => '#']]) @endif
            @foreach ($links as $li => $link)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
                    <input name="nav_link_label[{{ $li }}]" value="{{ $link['label'] ?? '' }}" placeholder="Label" style="flex:1;">
                    <input name="nav_link_href[{{ $li }}]" value="{{ $link['href'] ?? '#' }}" placeholder="/path" style="flex:1;">
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-nav-link">Add link</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Sidebar widgets</h2>
        <div class="grid grid-2">
            <div>
                <label for="spare_parts_title">Spare parts title</label>
                <input id="spare_parts_title" name="spare_parts_title" value="{{ old('spare_parts_title', $setting->spare_parts_title) }}">
            </div>
            <div>
                <label for="spare_parts_button_label">Spare parts button</label>
                <input id="spare_parts_button_label" name="spare_parts_button_label" value="{{ old('spare_parts_button_label', $setting->spare_parts_button_label) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="spare_parts_text">Spare parts text</label>
                <input id="spare_parts_text" name="spare_parts_text" value="{{ old('spare_parts_text', $setting->spare_parts_text) }}">
            </div>
            <div>
                <label for="brochures_title">Brochures title</label>
                <input id="brochures_title" name="brochures_title" value="{{ old('brochures_title', $setting->brochures_title) }}">
            </div>
            <div>
                <label for="brochure_label">Brochure link label</label>
                <input id="brochure_label" name="brochure_label" value="{{ old('brochure_label', $setting->brochure_label) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="brochures_text">Brochures text</label>
                <input id="brochures_text" name="brochures_text" value="{{ old('brochures_text', $setting->brochures_text) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="brochure_url">Brochure URL</label>
                <input id="brochure_url" name="brochure_url" value="{{ old('brochure_url', $setting->brochure_url) }}">
            </div>
            <div>
                <label for="quote_title">Quote form title</label>
                <input id="quote_title" name="quote_title" value="{{ old('quote_title', $setting->quote_title) }}">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save sidebar</button>
        </div>
    </form>
</div>

<template id="tpl-nav-link">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
        <input name="nav_link_label[__INDEX__]" placeholder="Label" style="flex:1;">
        <input name="nav_link_href[__INDEX__]" placeholder="/path" style="flex:1;">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-nav-group">
    <div data-repeater-item style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:16px;">
        <div class="grid grid-2">
            <div><label>Group id</label><input name="nav_group_id[__INDEX__]"></div>
            <div><label>Group label</label><input name="nav_group_label[__INDEX__]"></div>
        </div>
        <p style="margin:12px 0 8px;font-size:13px;">Add sub-items after saving, or edit existing groups above.</p>
        <button type="button" class="btn btn-danger" data-remove-row style="margin-top:8px;">Remove group</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
