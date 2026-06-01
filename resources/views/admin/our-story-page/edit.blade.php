@extends('layouts.admin', ['title' => 'Our Story page'])

@section('content')
<div class="header">
    <h1>Our Story page</h1>
    <a class="btn btn-muted" href="{{ route('our-story') }}" target="_blank" rel="noopener">View on site</a>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.our-story-page.update', $page) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px;font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div style="grid-column:1/-1;">
                <label for="hero_title">Hero title (H1)</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}">
            </div>
            <div style="grid-column:1/-1;">
                <label for="hero_background_file">Hero background image</label>
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/*">
                @if (filled($page->hero_background))
                    <div style="margin-top:8px;">
                        <img src="{{ \App\Models\OurStoryPage::imageSrc($page->hero_background) }}" alt="" style="max-width:220px;border-radius:8px;">
                        <label style="display:flex;gap:8px;margin-top:8px;font-size:13px;">
                            <input type="checkbox" name="remove_hero_background" value="1"> Remove hero image
                        </label>
                    </div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Intro</h2>
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
        <div data-repeater-wrap="tpl-intro-paragraph">
            @php($paragraphs = old('intro_paragraphs', $page->intro_paragraphs ?? []))
            @if (! is_array($paragraphs) || count($paragraphs) < 1) @php($paragraphs = ['']) @endif
            @foreach ($paragraphs as $pi => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="intro_paragraphs[{{ $pi }}]" rows="3" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-intro-paragraph" style="margin-top:8px;">Add paragraph</button>

        <h2 style="margin:24px 0 12px;font-size:15px;">Timeline milestones</h2>
        <div data-repeater-wrap="tpl-milestone">
            @php($milestones = old('milestone_year') ? null : ($page->milestones ?? []))
            @if (old('milestone_year'))
                @php($milestones = [])
                @foreach (old('milestone_year', []) as $mi => $y)
                    @php($milestones[] = [
                        'year' => $y,
                        'title' => old('milestone_title.'.$mi),
                        'text' => old('milestone_text.'.$mi),
                        'image_path' => old('milestone_image_path.'.$mi),
                    ])
                @endforeach
            @endif
            @if (! is_array($milestones) || count($milestones) < 1) @php($milestones = [['year'=>'','title'=>'','text'=>'','image_path'=>null]]) @endif
            @foreach ($milestones as $mi => $m)
                <div data-repeater-item style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:12px;">
                    <div class="grid grid-2">
                        <div>
                            <label>Year</label>
                            <input name="milestone_year[{{ $mi }}]" value="{{ $m['year'] ?? '' }}">
                        </div>
                        <div>
                            <label>Title</label>
                            <input name="milestone_title[{{ $mi }}]" value="{{ $m['title'] ?? '' }}">
                        </div>
                        <div style="grid-column:1/-1;">
                            <label>Text</label>
                            <textarea name="milestone_text[{{ $mi }}]" rows="3">{{ $m['text'] ?? '' }}</textarea>
                        </div>
                        <div style="grid-column:1/-1;">
                            <label>Image</label>
                            <input type="hidden" name="milestone_image_path[{{ $mi }}]" value="{{ $m['image_path'] ?? '' }}">
                            <input type="file" name="milestone_image[{{ $mi }}]" accept="image/*">
                            @if (filled($m['image_path'] ?? null))
                                <img src="{{ \App\Models\OurStoryPage::imageSrc($m['image_path']) }}" alt="" style="max-width:120px;margin-top:8px;display:block;">
                            @endif
                        </div>
                    </div>
                    <button type="button" class="btn btn-danger" data-remove-row style="margin-top:8px;">Remove milestone</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-milestone">Add milestone</button>

        <div style="margin-top:20px;">
            <button type="submit" class="btn btn-primary">Save page</button>
        </div>
    </form>
</div>

<template id="tpl-intro-paragraph">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="intro_paragraphs[__INDEX__]" rows="3" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

<template id="tpl-milestone">
    <div data-repeater-item style="border:1px solid #e5e7eb;border-radius:8px;padding:12px;margin-bottom:12px;">
        <div class="grid grid-2">
            <div><label>Year</label><input name="milestone_year[__INDEX__]"></div>
            <div><label>Title</label><input name="milestone_title[__INDEX__]"></div>
            <div style="grid-column:1/-1;"><label>Text</label><textarea name="milestone_text[__INDEX__]" rows="3"></textarea></div>
            <div style="grid-column:1/-1;"><label>Image</label><input type="hidden" name="milestone_image_path[__INDEX__]" value=""><input type="file" name="milestone_image[__INDEX__]" accept="image/*"></div>
        </div>
        <button type="button" class="btn btn-danger" data-remove-row style="margin-top:8px;">Remove milestone</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
