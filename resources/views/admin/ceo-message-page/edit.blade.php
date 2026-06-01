@extends('layouts.admin', ['title' => 'Message from the CEO'])

@section('content')
<div class="header">
    <h1>Message from the CEO</h1>
    <a class="btn btn-muted" href="{{ route('message-from-ceo') }}" target="_blank" rel="noopener">View on site</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.ceo-message-page.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div style="grid-column:1/-1;">
                <label for="hero_title">Hero quote (H1)</label>
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
                        <img src="{{ \App\Models\CeoMessagePage::imageSrc($page->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Letter</h2>
        <div class="grid grid-2">
            <div>
                <label for="eyebrow">Eyebrow</label>
                <input id="eyebrow" name="eyebrow" value="{{ old('eyebrow', $page->eyebrow) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="salutation">Salutation</label>
                <input id="salutation" name="salutation" value="{{ old('salutation', $page->salutation) }}">
            </div>
        </div>

        <label style="margin-top:12px;display:block;">Body paragraphs</label>
        <div data-repeater-wrap="tpl-ceo-paragraph">
            @php($paragraphs = old('paragraphs', $page->paragraphs ?? []))
            @if (! is_array($paragraphs) || count($paragraphs) < 1) @php($paragraphs = ['']) @endif
            @foreach ($paragraphs as $pi => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="paragraphs[{{ $pi }}]" rows="4" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-ceo-paragraph" style="margin-top:8px;">Add paragraph</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Signature &amp; portrait</h2>
        <div class="grid grid-2">
            <div>
                <label for="signature_name">Name</label>
                <input id="signature_name" name="signature_name" value="{{ old('signature_name', $page->signature_name) }}">
            </div>
            <div>
                <label for="signature_role">Role</label>
                <input id="signature_role" name="signature_role" value="{{ old('signature_role', $page->signature_role) }}">
            </div>
            <div>
                <label for="linkedin_url">LinkedIn URL</label>
                <input id="linkedin_url" name="linkedin_url" value="{{ old('linkedin_url', $page->linkedin_url) }}">
            </div>
            <div>
                <label for="instagram_url">Instagram URL</label>
                <input id="instagram_url" name="instagram_url" value="{{ old('instagram_url', $page->instagram_url) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="portrait_file">Portrait photo</label>
                <input id="portrait_file" name="portrait_file" type="file" accept="image/*">
                @if (filled($page->portrait_path))
                    <div style="margin-top:8px;">
                        <img src="{{ \App\Models\CeoMessagePage::imageSrc($page->portrait_path) }}" alt="" style="max-width:160px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_portrait" value="1"> Remove portrait
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save page</button>
        </div>
    </form>
</div>

<template id="tpl-ceo-paragraph">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="paragraphs[__INDEX__]" rows="4" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
