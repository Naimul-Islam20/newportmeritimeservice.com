@extends('layouts.admin', ['title' => 'Visual showcase (home)'])

@section('content')
<div class="header">
    <h1>Our World in Frames</h1>
    <a href="{{ route('admin.home-sections.index') }}" class="btn btn-muted">Back to home sections</a>
</div>

@if (session('status'))
    <div class="card" style="margin-bottom: 14px; padding: 12px 14px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
        {{ session('status') }}
    </div>
@endif

@php
    $headerDefaults = $headerDefaults ?? \App\Models\HomeVisualFramesSetting::defaultHeader();
    $galleryDefaults = $galleryDefaults ?? \App\Models\HomeVisualFramesSetting::defaultGallery();
    $itemsOld = old('items');
    if (is_array($itemsOld)) {
        $formRows = array_values($itemsOld);
    } elseif ($setting->exists && is_array($setting->gallery)) {
        $formRows = $setting->gallery;
    } else {
        $formRows = $galleryDefaults;
    }
    if (count($formRows) === 0 && $setting->exists) {
        $formRows = [['path' => null, 'url' => null, 'caption' => null]];
    }
@endphp

<div class="card">
    <p style="margin: 0 0 14px 0; color:#64748b; font-size: 14px;">
        Home page <strong>Our World in Frames</strong> block. All fields are optional: add as many images as you like (image URL and/or upload). Only rows with an image URL or uploaded file appear on the site.
    </p>

    <form method="POST" action="{{ route('admin.home-sections.visual-frames.update') }}" enctype="multipart/form-data" id="vf-form">
        @csrf
        @method('PUT')

        <div class="grid grid-2" style="gap: 14px;">
            <div>
                <label for="mini_title">Mini title</label>
                <input id="mini_title" name="mini_title" type="text" value="{{ old('mini_title', $setting->mini_title ?? $headerDefaults['mini_title']) }}" autocomplete="off" placeholder="Optional">
                @error('mini_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $setting->title ?? $headerDefaults['title']) }}" autocomplete="off" placeholder="Optional">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional">{{ old('description', $setting->description ?? $headerDefaults['description']) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Show on home page</label>
                @php($vfActive = (string) old('is_active', $setting->exists ? ($setting->is_active ? '1' : '0') : '1'))
                <select id="is_active" name="is_active">
                    <option value="1" @selected($vfActive === '1')>Yes</option>
                    <option value="0" @selected($vfActive === '0')>No</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 20px; font-weight: 700;">Images</div>
        <p style="margin: 6px 0 12px 0; color:#64748b; font-size: 13px;">Per row: optional caption, optional image URL, optional file upload (upload overrides URL when saved).</p>

        <div id="vf-rows" style="display: flex; flex-direction: column; gap: 14px;">
            @foreach ($formRows as $idx => $row)
                @php($row = is_array($row) ? $row : [])
                <div class="vf-row" style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; background: #fff;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
                        <span style="font-size: 12px; color: #64748b;">Image row</span>
                        <label style="font-size: 12px; display: flex; align-items: center; gap: 6px;">
                            <input type="checkbox" name="items[{{ $idx }}][remove]" value="1"> Remove this row
                        </label>
                    </div>
                    <input type="hidden" name="items[{{ $idx }}][path]" value="{{ old('items.'.$idx.'.path', data_get($row, 'path')) }}">
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 12px;">Caption (optional)</label>
                        <input type="text" name="items[{{ $idx }}][caption]" value="{{ old('items.'.$idx.'.caption', data_get($row, 'caption')) }}" placeholder="Title under image" style="width: 100%; max-width: 480px;">
                        @error('items.'.$idx.'.caption') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div style="margin-bottom: 10px;">
                        <label style="font-size: 12px;">Image URL (optional)</label>
                        <input type="text" name="items[{{ $idx }}][url]" value="{{ old('items.'.$idx.'.url', data_get($row, 'url')) }}" placeholder="https://..." style="width: 100%; max-width: 640px;">
                        @error('items.'.$idx.'.url') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label style="font-size: 12px;">Or upload image (optional)</label>
                        @if (is_string(data_get($row, 'path')) && data_get($row, 'path') !== '')
                            <div style="margin: 6px 0;">
                                <img src="{{ asset(data_get($row, 'path')) }}" alt="" style="max-height: 72px; border-radius: 6px; border: 1px solid #e5e7eb;">
                            </div>
                        @endif
                        <input type="file" name="items[{{ $idx }}][file]" accept="image/jpeg,image/png,image/webp,image/gif">
                        @error('items.'.$idx.'.file') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 12px;">
            <button type="button" class="btn btn-muted" id="vf-add-row">Add image row</button>
        </div>

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>

<template id="vf-row-template">
    <div class="vf-row" style="border: 1px solid #e5e7eb; border-radius: 10px; padding: 14px; background: #fff;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 10px;">
            <span style="font-size: 12px; color: #64748b;">Image row</span>
            <label style="font-size: 12px; display: flex; align-items: center; gap: 6px;">
                <input type="checkbox" name="items[__KEY__][remove]" value="1"> Remove this row
            </label>
        </div>
        <input type="hidden" name="items[__KEY__][path]" value="">
        <div style="margin-bottom: 10px;">
            <label style="font-size: 12px;">Caption (optional)</label>
            <input type="text" name="items[__KEY__][caption]" value="" placeholder="Title under image" style="width: 100%; max-width: 480px;">
        </div>
        <div style="margin-bottom: 10px;">
            <label style="font-size: 12px;">Image URL (optional)</label>
            <input type="text" name="items[__KEY__][url]" value="" placeholder="https://..." style="width: 100%; max-width: 640px;">
        </div>
        <div>
            <label style="font-size: 12px;">Or upload image (optional)</label>
            <input type="file" name="items[__KEY__][file]" accept="image/jpeg,image/png,image/webp,image/gif">
        </div>
    </div>
</template>

<script>
    (() => {
        const wrap = document.getElementById('vf-rows');
        const addBtn = document.getElementById('vf-add-row');
        const tpl = document.getElementById('vf-row-template');
        if (!wrap || !addBtn || !tpl) return;
        let seq = 0;
        addBtn.addEventListener('click', () => {
            const key = 'n' + Date.now() + '_' + (seq++);
            const html = tpl.innerHTML.replace(/__KEY__/g, key);
            const div = document.createElement('div');
            div.innerHTML = html.trim();
            const row = div.firstElementChild;
            if (row) wrap.appendChild(row);
        });
    })();
</script>
@endsection
