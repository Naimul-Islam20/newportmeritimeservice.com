@extends('layouts.admin', ['title' => 'Career page'])

@section('content')
<div class="header">
    <h1>Career page</h1>
    <a class="btn btn-muted" href="{{ route('career') }}" target="_blank" rel="noopener">View on site</a>
</div>
<p style="margin:0 0 16px;font-size:14px;color:#5a6578;">All content on the public <strong>/career</strong> page is managed here — not via Menu → Page sections.</p>

<div class="card">
    <form method="POST" action="{{ route('admin.career-page.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div>
                <label for="hero_title">Hero title (H1)</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}">
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
                        <img src="{{ \App\Models\CareerPage::imageSrc($page->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">HR vision</h2>
        <div class="grid grid-2">
            <div>
                <label for="eyebrow">Eyebrow</label>
                <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}">
            </div>
            <div>
                <label for="section_title">Section title</label>
                <input id="section_title" name="section_title" value="{{ old('section_title', $page->section_title) }}">
            </div>
        </div>
        <label style="margin-top:12px;display:block;">Intro paragraphs</label>
        <div data-repeater-wrap="tpl-intro">
            @php($intros = old('intro_paragraphs', $page->intro_paragraphs ?? []))
            @if (! is_array($intros) || count($intros) < 1) @php($intros = ['']) @endif
            @foreach ($intros as $i => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="intro_paragraphs[{{ $i }}]" rows="3" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-intro">Add paragraph</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">General application</h2>
        <div class="grid grid-2">
            <div>
                <label for="application_title">Block title</label>
                <input id="application_title" name="application_title" value="{{ old('application_title', $page->application_title) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="application_lead">Lead text</label>
                <input id="application_lead" name="application_lead" value="{{ old('application_lead', $page->application_lead) }}">
            </div>
        </div>
        <label style="margin-top:12px;display:block;">Qualifications</label>
        <div data-repeater-wrap="tpl-qual">
            @php($quals = old('qualifications', $page->qualifications ?? []))
            @if (! is_array($quals) || count($quals) < 1) @php($quals = ['']) @endif
            @foreach ($quals as $qi => $q)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <input name="qualifications[{{ $qi }}]" value="{{ is_string($q) ? $q : '' }}" style="flex:1;">
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-qual">Add qualification</button>
        <div style="margin-top:12px;">
            <label for="application_note">Note (shown before email)</label>
            <textarea id="application_note" name="application_note" rows="3">{{ old('application_note', $page->application_note) }}</textarea>
        </div>
        <div class="grid grid-2" style="margin-top:12px;">
            <div>
                <label for="hr_email">HR email</label>
                <input id="hr_email" name="hr_email" type="email" value="{{ old('hr_email', $page->hr_email) }}">
            </div>
            <div>
                <label for="mail_button_label">Send mail button label</label>
                <input id="mail_button_label" name="mail_button_label" value="{{ old('mail_button_label', $page->mail_button_label) }}">
            </div>
            <div>
                <label for="kariyer_url">Kariyer.net URL (empty = hide button)</label>
                <input id="kariyer_url" name="kariyer_url" value="{{ old('kariyer_url', $page->kariyer_url) }}">
            </div>
            <div>
                <label for="kariyer_button_label">Kariyer.net button label</label>
                <input id="kariyer_button_label" name="kariyer_button_label" value="{{ old('kariyer_button_label', $page->kariyer_button_label) }}">
            </div>
            <div>
                <label for="linkedin_url">LinkedIn URL (empty = hide; blank in DB = site default)</label>
                <input id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $page->linkedin_url) }}">
            </div>
            <div>
                <label for="linkedin_button_label">LinkedIn button label</label>
                <input id="linkedin_button_label" name="linkedin_button_label" value="{{ old('linkedin_button_label', $page->linkedin_button_label) }}">
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Sidebar</h2>
        <div class="grid grid-2">
            <div>
                <label for="team_button_label">Team button label</label>
                <input id="team_button_label" name="team_button_label" value="{{ old('team_button_label', $page->team_button_label) }}">
            </div>
            <div>
                <label for="team_button_url">Team button URL</label>
                <input id="team_button_url" name="team_button_url" value="{{ old('team_button_url', $page->team_button_url) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="aside_image_alt">Aside image alt text</label>
                <input id="aside_image_alt" name="aside_image_alt" value="{{ old('aside_image_alt', $page->aside_image_alt) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="aside_image_file">Aside image</label>
                <input id="aside_image_file" name="aside_image_file" type="file" accept="image/*">
                @if (filled($page->aside_image))
                    <div style="margin-top:8px;">
                        <img src="{{ \App\Models\CareerPage::imageSrc($page->aside_image) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_aside_image" value="1"> Remove image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Offers section</h2>
        <div class="grid grid-2">
            <div>
                <label for="offers_eyebrow">Eyebrow</label>
                <input id="offers_eyebrow" name="offers_eyebrow" value="{{ old('offers_eyebrow', $page->offers_eyebrow) }}">
            </div>
            <div>
                <label for="offers_title">Title</label>
                <input id="offers_title" name="offers_title" value="{{ old('offers_title', $page->offers_title) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="offers_card_title">Card title</label>
                <input id="offers_card_title" name="offers_card_title" value="{{ old('offers_card_title', $page->offers_card_title) }}">
            </div>
        </div>
        <label style="margin-top:12px;display:block;">Card paragraphs</label>
        <div data-repeater-wrap="tpl-offers">
            @php($offers = old('offers_paragraphs', $page->offers_paragraphs ?? []))
            @if (! is_array($offers) || count($offers) < 1) @php($offers = ['']) @endif
            @foreach ($offers as $oi => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="offers_paragraphs[{{ $oi }}]" rows="3" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-offers">Add paragraph</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Bottom bar</h2>
        <div class="grid grid-2">
            <div>
                <label for="bottom_cta_label">Button label</label>
                <input id="bottom_cta_label" name="bottom_cta_label" value="{{ old('bottom_cta_label', $page->bottom_cta_label) }}">
            </div>
            <div>
                <label for="bottom_cta_url">Button URL</label>
                <input id="bottom_cta_url" name="bottom_cta_url" value="{{ old('bottom_cta_url', $page->bottom_cta_url) }}">
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save page</button>
        </div>
    </form>
</div>

<template id="tpl-intro">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="intro_paragraphs[__INDEX__]" rows="3" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-qual">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <input name="qualifications[__INDEX__]" style="flex:1;">
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>
<template id="tpl-offers">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="offers_paragraphs[__INDEX__]" rows="3" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
