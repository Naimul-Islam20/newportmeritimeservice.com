@extends('layouts.admin', ['title' => 'Our Team page'])

@php
    $regional = old('regional_label') ? collect(old('regional_label'))->map(fn ($l, $i) => ['label' => $l, 'url' => old('regional_url.'.$i, '#')])->all() : ($page->regional_nav ?? []);
    $categories = old('category_label') ? collect(old('category_label'))->map(fn ($l, $i) => ['label' => $l, 'url' => old('category_url.'.$i, '#')])->all() : ($page->category_nav ?? []);
    $sections = $page->team_sections ?? [];
    if (old('section_heading')) {
        $sections = [];
        foreach (old('section_heading', []) as $si => $heading) {
            $sections[] = [
                'heading' => $heading,
                'members' => collect(old('member_name.'.$si, []))->map(fn ($name, $mi) => [
                    'name' => $name,
                    'role' => old('member_role.'.$si.'.'.$mi),
                    'email' => old('member_email.'.$si.'.'.$mi),
                    'phone' => old('member_phone.'.$si.'.'.$mi),
                    'photo_path' => old('member_photo_path.'.$si.'.'.$mi),
                ])->all(),
            ];
        }
    }
    if (! is_array($sections) || count($sections) < 1) {
        $sections = [['heading' => '', 'members' => [['name' => '', 'role' => '', 'email' => '', 'phone' => '', 'photo_path' => null]]]];
    }
@endphp

@section('content')
<div class="header">
    <h1>Our Team page</h1>
    <a class="btn btn-muted" href="{{ route('our-team-management') }}" target="_blank" rel="noopener">View on site</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.our-team-page.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div>
                <label for="hero_title">Hero title</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}">
            </div>
            <div>
                <label for="breadcrumb_label">Breadcrumb label</label>
                <input id="breadcrumb_label" name="breadcrumb_label" value="{{ old('breadcrumb_label', $page->breadcrumb_label) }}">
            </div>
            <div>
                <label for="page_title">Main content title</label>
                <input id="page_title" name="page_title" value="{{ old('page_title', $page->page_title) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="hero_background_file">Hero background</label>
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/*">
                @if (filled($page->hero_background))
                    <div style="margin-top:8px;">
                        <img src="{{ \App\Models\OurTeamPage::imageSrc($page->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Sidebar — Regional links</h2>
        <div data-repeater-wrap="tpl-regional">
            @foreach ($regional as $ri => $item)
                <div data-repeater-item class="grid grid-2" style="margin-bottom:8px;">
                    <input name="regional_label[{{ $ri }}]" value="{{ $item['label'] ?? '' }}" placeholder="Label">
                    <div style="display:flex;gap:8px;">
                        <input name="regional_url[{{ $ri }}]" value="{{ $item['url'] ?? '#' }}" placeholder="/path or #" style="flex:1;">
                        <button type="button" class="btn btn-muted" data-remove-row>×</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-regional">Add regional link</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Sidebar — Categories</h2>
        <div data-repeater-wrap="tpl-category">
            @foreach ($categories as $ci => $item)
                <div data-repeater-item class="grid grid-2" style="margin-bottom:8px;">
                    <input name="category_label[{{ $ci }}]" value="{{ $item['label'] ?? '' }}" placeholder="Label">
                    <div style="display:flex;gap:8px;">
                        <input name="category_url[{{ $ci }}]" value="{{ $item['url'] ?? '#' }}" placeholder="/path or #" style="flex:1;">
                        <button type="button" class="btn btn-muted" data-remove-row>×</button>
                    </div>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-category">Add category link</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Team sections &amp; members</h2>
        <div data-repeater-wrap="tpl-team-section">
            @foreach ($sections as $si => $section)
                <div data-repeater-item data-section-index="{{ $si }}" style="border:1px solid #dbeafe;border-radius:10px;padding:14px;margin-bottom:16px;background:#f8fafc;">
                    <label>Section heading</label>
                    <input name="section_heading[{{ $si }}]" value="{{ $section['heading'] ?? '' }}" style="width:100%;margin-bottom:12px;">

                    @php($members = $section['members'] ?? [])
                    @if (count($members) < 1) @php($members = [['name'=>'','role'=>'','email'=>'','phone'=>'','photo_path'=>null]]) @endif
                    @foreach ($members as $mi => $member)
                        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:10px;margin-bottom:10px;background:#fff;">
                            <div class="grid grid-2">
                                <div><label>Name</label><input name="member_name[{{ $si }}][{{ $mi }}]" value="{{ $member['name'] ?? '' }}"></div>
                                <div><label>Role</label><input name="member_role[{{ $si }}][{{ $mi }}]" value="{{ $member['role'] ?? '' }}"></div>
                                <div><label>Email</label><input name="member_email[{{ $si }}][{{ $mi }}]" value="{{ $member['email'] ?? '' }}"></div>
                                <div><label>Phone</label><input name="member_phone[{{ $si }}][{{ $mi }}]" value="{{ $member['phone'] ?? '' }}"></div>
                                <div style="grid-column:1/-1;">
                                    <label>Photo</label>
                                    <input type="hidden" name="member_photo_path[{{ $si }}][{{ $mi }}]" value="{{ $member['photo_path'] ?? '' }}">
                                    <input type="file" name="member_photo[{{ $si }}][{{ $mi }}]" accept="image/*">
                                    @if (filled($member['photo_path'] ?? null))
                                        <img src="{{ \App\Models\OurTeamPage::imageSrc($member['photo_path']) }}" alt="" style="max-width:100px;margin-top:6px;display:block;">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <p style="font-size:12px;color:#64748b;">To add more members in this section, save once then edit again — or duplicate fields before save.</p>
                    <button type="button" class="btn btn-danger" data-remove-row>Remove section</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-team-section">Add team section</button>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save page</button>
        </div>
    </form>
</div>

<template id="tpl-regional">
    <div data-repeater-item class="grid grid-2" style="margin-bottom:8px;">
        <input name="regional_label[__INDEX__]" placeholder="Label">
        <div style="display:flex;gap:8px;">
            <input name="regional_url[__INDEX__]" placeholder="/path or #" style="flex:1;">
            <button type="button" class="btn btn-muted" data-remove-row>×</button>
        </div>
    </div>
</template>

<template id="tpl-category">
    <div data-repeater-item class="grid grid-2" style="margin-bottom:8px;">
        <input name="category_label[__INDEX__]" placeholder="Label">
        <div style="display:flex;gap:8px;">
            <input name="category_url[__INDEX__]" placeholder="/path or #" style="flex:1;">
            <button type="button" class="btn btn-muted" data-remove-row>×</button>
        </div>
    </div>
</template>

<template id="tpl-team-section">
    <div data-repeater-item style="border:1px solid #dbeafe;border-radius:10px;padding:14px;margin-bottom:16px;background:#f8fafc;">
        <label>Section heading</label>
        <input name="section_heading[__INDEX__]" style="width:100%;margin-bottom:12px;">
        <div style="border:1px solid #e5e7eb;border-radius:8px;padding:10px;margin-bottom:10px;background:#fff;">
            <div class="grid grid-2">
                <div><label>Name</label><input name="member_name[__INDEX__][0]"></div>
                <div><label>Role</label><input name="member_role[__INDEX__][0]"></div>
                <div><label>Email</label><input name="member_email[__INDEX__][0]"></div>
                <div><label>Phone</label><input name="member_phone[__INDEX__][0]"></div>
                <div style="grid-column:1/-1;"><label>Photo</label><input type="hidden" name="member_photo_path[__INDEX__][0]" value=""><input type="file" name="member_photo[__INDEX__][0]" accept="image/*"></div>
            </div>
        </div>
        <button type="button" class="btn btn-danger" data-remove-row>Remove section</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
