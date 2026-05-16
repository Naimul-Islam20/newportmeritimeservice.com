@extends('layouts.admin', ['title' => 'Service area (home)'])

@section('content')
<div class="header">
    <h1>Service area</h1>
    <a href="{{ route('admin.home-sections.index') }}" class="btn btn-muted">Back to home sections</a>
</div>

@if (session('status'))
    <div class="card" style="margin-bottom: 14px; padding: 12px 14px; background: #ecfdf5; border: 1px solid #a7f3d0; color: #065f46;">
        {{ session('status') }}
    </div>
@endif

@php
    $defaults = \App\Models\HomeServiceAreaSetting::defaultAttributes();
    $stepsList = old('steps');
    if (! is_array($stepsList)) {
        $stepsList = ($setting->exists && is_array($setting->steps)) ? $setting->steps : $defaults['steps'];
    }
    $stepsList = array_values(array_map(fn ($s) => is_string($s) ? $s : '', $stepsList));
    $stepsList = array_pad($stepsList, max(4, count($stepsList)), '');
@endphp

<div class="card">
    <p style="margin: 0 0 14px 0; color:#64748b; font-size: 14px;">
        Content for the <strong>Service areas / Locations</strong> block on the home page (map image, titles, bottom text, and steps).
    </p>

    <form method="POST" action="{{ route('admin.home-sections.service-area.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-2" style="gap: 14px;">
            <div>
                <label for="mini_title">Mini title</label>
                <input id="mini_title" name="mini_title" type="text" value="{{ old('mini_title', $setting->mini_title ?? $defaults['mini_title']) }}" autocomplete="off">
                @error('mini_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" type="text" value="{{ old('title', $setting->title ?? $defaults['title']) }}" autocomplete="off">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="map_image">Map / box image</label>
                @if ($setting->exists && is_string($setting->map_image_path) && $setting->map_image_path !== '')
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset($setting->map_image_path) }}" alt="" style="max-width: 320px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb;">
                        <div style="margin-top:6px; color:#64748b; font-size:12px;">
                            Current file — upload a new image to replace.
                        </div>
                    </div>
                @endif
                <input id="map_image" name="map_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('map_image') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="highlight_title">Bottom block — heading</label>
                <input id="highlight_title" name="highlight_title" type="text" value="{{ old('highlight_title', $setting->highlight_title ?? $defaults['highlight_title']) }}" autocomplete="off">
                @error('highlight_title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="highlight_description">Bottom block — description</label>
                <textarea id="highlight_description" name="highlight_description" rows="4">{{ old('highlight_description', $setting->highlight_description ?? $defaults['highlight_description']) }}</textarea>
                @error('highlight_description') <div class="error">{{ $message }}</div> @enderror
            </div>

            @foreach ($stepsList as $i => $step)
                <div style="grid-column: 1 / -1;">
                    <label for="step_{{ $i }}">Step {{ $i + 1 }}</label>
                    <textarea id="step_{{ $i }}" name="steps[]" rows="2">{{ $step }}</textarea>
                </div>
            @endforeach
        </div>

        <div style="margin-top: 16px;">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>
    </form>
</div>
@endsection
