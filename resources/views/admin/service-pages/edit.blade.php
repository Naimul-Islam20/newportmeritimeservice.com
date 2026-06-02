@extends('layouts.admin', ['title' => 'Edit service page'])

@section('content')
<div class="header">
    <h1>{{ $page->title ?: $page->slug }}</h1>
    <div style="display:flex;gap:8px;flex-wrap:wrap;">
        <a class="btn btn-muted" href="{{ route('admin.service-pages.index') }}">All pages</a>
        <a class="btn btn-muted" href="{{ $publicUrl }}" target="_blank" rel="noopener">View on site</a>
    </div>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.service-pages.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px;font-size:15px;">Page layout</h2>
        <div class="grid grid-2" style="margin-bottom:20px;">
            <div style="grid-column:1/-1;">
                <label for="content_layout">Content layout</label>
                <select id="content_layout" name="content_layout">
                    <option value="full" @selected(old('content_layout', $page->content_layout ?? 'full') === 'full')>Full (Technical Stores style — gallery, services list)</option>
                    <option value="simple" @selected(old('content_layout', $page->content_layout ?? 'full') === 'simple')>Simple (title → description → image → Why Choose Us)</option>
                </select>
            </div>
        </div>

        <h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div>
                <label for="hero_title">Hero title (H1)</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}">
            </div>
            <div>
                <label for="breadcrumb_label">Breadcrumb label</label>
                <input id="breadcrumb_label" name="breadcrumb_label" value="{{ old('breadcrumb_label', $page->breadcrumb_label) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}">
            </div>
            <div>
                <label for="open_nav_group_id">Sidebar accordion open (group id)</label>
                <input id="open_nav_group_id" name="open_nav_group_id" value="{{ old('open_nav_group_id', $page->open_nav_group_id) }}" placeholder="e.g. technical-stores">
            </div>
            <div style="grid-column:1/-1;">
                <label for="hero_background_file">Hero background image</label>
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/*">
                @if (filled($page->hero_background))
                    <div style="margin-top:8px;">
                        <img src="{{ \App\Models\ServicePage::imageSrc($page->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Main content</h2>
        <div class="grid grid-2">
            <div>
                <label for="eyebrow">Eyebrow</label>
                <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}">
            </div>
            <div>
                <label for="title">Title (H2)</label>
                <input id="title" name="title" value="{{ old('title', $page->title) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="subtitle">Subtitle (H3)</label>
                <input id="subtitle" name="subtitle" value="{{ old('subtitle', $page->subtitle) }}">
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Top gallery (2 images side by side)</h2>
        <p style="margin:0 0 12px;color:#64748b;font-size:13px;">Shown below the title and subtitle on the public page. Leave empty to use default placeholders until you upload.</p>
        @php($galleryPaths = old('gallery_image_path_0') !== null ? [old('gallery_image_path_0'), old('gallery_image_path_1')] : ($page->gallery_images ?? []))
        @if (! is_array($galleryPaths)) @php($galleryPaths = []) @endif
        <div class="grid grid-2">
            @foreach ([0 => 'Left image', 1 => 'Right image'] as $gi => $galleryLabel)
                <div>
                    <label for="gallery_image_{{ $gi }}_file">{{ $galleryLabel }}</label>
                    <input id="gallery_image_{{ $gi }}_file" name="gallery_image_{{ $gi }}_file" type="file" accept="image/*">
                    <input type="hidden" name="gallery_image_path_{{ $gi }}" value="{{ $galleryPaths[$gi] ?? '' }}">
                    @if (filled($galleryPaths[$gi] ?? null))
                        <div style="margin-top:8px;">
                            <img src="{{ \App\Models\ServicePage::imageSrc($galleryPaths[$gi]) }}" alt="" style="max-width:100%;border-radius:8px;">
                            <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                                <input type="checkbox" name="remove_gallery_image_{{ $gi }}" value="1"> Remove image
                            </label>
                        </div>
                    @endif
                </div>
            @endforeach
        </div>

        <div class="grid grid-2" style="margin-top:12px;">
            <div style="grid-column:1/-1;">
                <label for="lead_paragraph">Lead paragraph</label>
                <input id="lead_paragraph" name="lead_paragraph" value="{{ old('lead_paragraph', $page->lead_paragraph) }}">
            </div>
        </div>

        <label style="margin-top:12px;display:block;">Body paragraphs</label>
        <div data-repeater-wrap="tpl-body-paragraph">
            @php($paragraphs = old('body_paragraphs', $page->body_paragraphs ?? []))
            @if (! is_array($paragraphs) || count($paragraphs) < 1) @php($paragraphs = ['']) @endif
            @foreach ($paragraphs as $pi => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="body_paragraphs[{{ $pi }}]" rows="3" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-body-paragraph">Add paragraph</button>

        <div style="margin-top:12px;">
            <label for="highlight_paragraph">Highlight paragraph</label>
            <textarea id="highlight_paragraph" name="highlight_paragraph" rows="3">{{ old('highlight_paragraph', $page->highlight_paragraph) }}</textarea>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Services list</h2>
        <div>
            <label for="services_heading">Section heading</label>
            <input id="services_heading" name="services_heading" value="{{ old('services_heading', $page->services_heading) }}">
        </div>
        @php($columns = old('service_columns', $page->service_columns ?? [[''], ['']]))
        @if (! is_array($columns) || count($columns) < 2) @php($columns = [[''], ['']]) @endif
        <div class="grid grid-2" style="margin-top:12px;">
            @foreach ([0, 1] as $colIndex)
                <div>
                    <label>Column {{ $colIndex + 1 }} items (one per line in fields below)</label>
                    <div data-repeater-wrap="tpl-service-col-{{ $colIndex }}">
                        @php($colItems = $columns[$colIndex] ?? [])
                        @if (! is_array($colItems) || count($colItems) < 1) @php($colItems = ['']) @endif
                        @foreach ($colItems as $ii => $item)
                            <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                                <input name="service_columns[{{ $colIndex }}][{{ $ii }}]" value="{{ is_string($item) ? $item : '' }}" style="flex:1;">
                                <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-muted" data-add-row="tpl-service-col-{{ $colIndex }}">Add item</button>
                </div>
            @endforeach
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Content image</h2>
        <div>
            <label for="content_image_file">Bottom image (below services list)</label>
            <input id="content_image_file" name="content_image_file" type="file" accept="image/*">
            @if (filled($page->content_image))
                <div style="margin-top:8px;">
                    <img src="{{ \App\Models\ServicePage::imageSrc($page->content_image) }}" alt="" style="max-width:220px;border-radius:8px;">
                    <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                        <input type="checkbox" name="remove_content_image" value="1"> Remove image
                    </label>
                </div>
            @endif
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Card icon/image (What We Do cards)</h2>
        @if ($cardSubMenu)
            <div>
                <label for="card_icon_file">Card icon for "{{ $cardSubMenu->label }}"</label>
                <input id="card_icon_file" name="card_icon_file" type="file" accept="image/*">
                <p style="margin:6px 0 0;color:#64748b;font-size:12px;">This image is used in /our-services card grid.</p>
                @if (filled($cardIconUrl))
                    <div style="margin-top:8px;">
                        <img src="{{ $cardIconUrl }}" alt="" style="max-width:220px;border-radius:8px;border:1px solid #e2e8f0;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_card_icon" value="1"> Remove card icon
                        </label>
                    </div>
                @endif
            </div>
        @else
            <p style="margin:0;color:#b91c1c;font-size:13px;">No matching top-level sub-menu found under "Our Services". Create/update sub-menu URL to match this page path first.</p>
        @endif

        <h2 style="margin:24px 0 12px;font-size:15px;">Why choose us</h2>
        <div>
            <label for="why_heading">Section heading</label>
            <input id="why_heading" name="why_heading" value="{{ old('why_heading', $page->why_heading) }}">
        </div>
        <label style="margin-top:12px;display:block;">Why paragraphs</label>
        <div data-repeater-wrap="tpl-why-paragraph">
            @php($whyParagraphs = old('why_paragraphs', $page->why_paragraphs ?? []))
            @if (! is_array($whyParagraphs) || count($whyParagraphs) < 1) @php($whyParagraphs = ['']) @endif
            @foreach ($whyParagraphs as $wi => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="why_paragraphs[{{ $wi }}]" rows="3" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-why-paragraph">Add paragraph</button>

        <label style="margin-top:16px;display:block;">Why cards</label>
        <div data-repeater-wrap="tpl-why-card">
            @php($cards = old('why_card_title') ? null : ($page->why_cards ?? []))
            @if (old('why_card_title'))
                @php($cards = [])
                @foreach (old('why_card_title', []) as $oci => $ot)
                    @php($cards[] = ['title' => $ot, 'icon' => old('why_card_icon.'.$oci)])
                @endforeach
            @endif
            @if (! is_array($cards) || count($cards) < 1) @php($cards = [['title' => '', 'icon' => 'team']]) @endif
            @foreach ($cards as $ci => $card)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
                    <input name="why_card_title[{{ $ci }}]" value="{{ $card['title'] ?? '' }}" placeholder="Title" style="flex:1;min-width:140px;">
                    <input name="why_card_icon[{{ $ci }}]" value="{{ $card['icon'] ?? 'team' }}" placeholder="Icon key" style="width:120px;">
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-why-card">Add card</button>

        <div style="margin-top:20px;">
            <label style="display:flex;gap:8px;font-size:14px;">
                <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active))> Page is active
            </label>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save page</button>
        </div>
    </form>
</div>

<template id="tpl-body-paragraph">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="body_paragraphs[__INDEX__]" rows="3" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-service-col-0">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <input name="service_columns[0][__INDEX__]" style="flex:1;">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-service-col-1">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <input name="service_columns[1][__INDEX__]" style="flex:1;">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-why-paragraph">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="why_paragraphs[__INDEX__]" rows="3" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-why-card">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;flex-wrap:wrap;">
        <input name="why_card_title[__INDEX__]" placeholder="Title" style="flex:1;min-width:140px;">
        <input name="why_card_icon[__INDEX__]" value="team" placeholder="Icon key" style="width:120px;">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
