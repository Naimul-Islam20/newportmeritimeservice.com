@extends('layouts.admin', ['title' => 'About Us page'])

@section('content')
<div class="header">
    <h1>About Us page</h1>
    <a class="btn btn-muted" href="{{ route('about-us') }}" target="_blank" rel="noopener">View on site</a>
</div>

<p style="color:#64748b; font-size:14px; max-width:52rem; margin:0 0 16px 0;">
    Everything on the public About Us page is controlled from this form. Leave a field empty only when you want the built-in fallback for that spot. <strong>Hero, intro side image, and banner background: upload images only.</strong> Video: <strong>YouTube link only</strong> (modal on the site).
</p>

<div class="card">
    <form method="POST" action="{{ route('admin.about-page.update', $aboutPage) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <h2 style="margin:0 0 12px 0; font-size:15px;">Hero</h2>
        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="hero_title">Page title (H1)</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $aboutPage->hero_title) }}" placeholder="{{ $defaults['hero_title'] }}">
                @error('hero_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="hero_background_file">Hero background — upload image only</label>
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('hero_background_file') <div class="error">{{ $message }}</div> @enderror
                @if (filled($aboutPage->hero_background))
                    <div style="margin-top:8px;"><img src="{{ \App\Models\AboutPage::imageSrc($aboutPage->hero_background) }}" alt="" style="max-width:220px;height:auto;border-radius:8px;border:1px solid #e5e7eb;"></div>
                @endif
            </div>
        </div>

        <h2 style="margin:24px 0 12px 0; font-size:15px;">Intro (trust block)</h2>
        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="trust_image_file">Side image — upload image only</label>
                <input id="trust_image_file" name="trust_image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('trust_image_file') <div class="error">{{ $message }}</div> @enderror
                @if (filled($aboutPage->trust_image))
                    <div style="margin-top:8px;"><img src="{{ \App\Models\AboutPage::imageSrc($aboutPage->trust_image) }}" alt="" style="max-width:220px;height:auto;border-radius:8px;border:1px solid #e5e7eb;"></div>
                @endif
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="trust_title">Title</label>
                <textarea id="trust_title" name="trust_title" rows="2" placeholder="Main heading (line breaks allowed)">{{ old('trust_title', $aboutPage->trust_title) }}</textarea>
                @error('trust_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="trust_description">Description</label>
                <textarea id="trust_description" name="trust_description" rows="8" placeholder="Supporting text">{{ old('trust_description', $aboutPage->trust_description) }}</textarea>
                @error('trust_description') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <h2 style="margin:24px 0 12px 0; font-size:15px;">Key numbers (three tiles)</h2>
        <div class="grid grid-2">
            <div>
                <label for="stat1_value">Stat 1 — number</label>
                <input id="stat1_value" name="stat1_value" value="{{ old('stat1_value', $aboutPage->stat1_value) }}" placeholder="{{ $defaults['stat1_value'] }}">
                @error('stat1_value') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="stat1_label">Stat 1 — label</label>
                <input id="stat1_label" name="stat1_label" value="{{ old('stat1_label', $aboutPage->stat1_label) }}" placeholder="{{ $defaults['stat1_label'] }}">
                @error('stat1_label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="stat2_value">Stat 2 — number</label>
                <input id="stat2_value" name="stat2_value" value="{{ old('stat2_value', $aboutPage->stat2_value) }}" placeholder="{{ $defaults['stat2_value'] }}">
                @error('stat2_value') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="stat2_label">Stat 2 — label</label>
                <input id="stat2_label" name="stat2_label" value="{{ old('stat2_label', $aboutPage->stat2_label) }}" placeholder="{{ $defaults['stat2_label'] }}">
                @error('stat2_label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="stat3_value">Stat 3 — number</label>
                <input id="stat3_value" name="stat3_value" value="{{ old('stat3_value', $aboutPage->stat3_value) }}" placeholder="{{ $defaults['stat3_value'] }}">
                @error('stat3_value') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="stat3_label">Stat 3 — label</label>
                <input id="stat3_label" name="stat3_label" value="{{ old('stat3_label', $aboutPage->stat3_label) }}" placeholder="{{ $defaults['stat3_label'] }}">
                @error('stat3_label') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <h2 style="margin:24px 0 12px 0; font-size:15px;">Mission &amp; vision</h2>
        <div class="grid grid-2">
            <div>
                <label for="mission_title">Mission title</label>
                <input id="mission_title" name="mission_title" value="{{ old('mission_title', $aboutPage->mission_title) }}" placeholder="{{ $defaults['mission_title'] }}">
                @error('mission_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="vision_title">Vision title</label>
                <input id="vision_title" name="vision_title" value="{{ old('vision_title', $aboutPage->vision_title) }}" placeholder="{{ $defaults['vision_title'] }}">
                @error('vision_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="mission_body">Mission text</label>
                <textarea id="mission_body" name="mission_body" rows="5">{{ old('mission_body', $aboutPage->mission_body) }}</textarea>
                @error('mission_body') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="vision_body">Vision text</label>
                <textarea id="vision_body" name="vision_body" rows="5">{{ old('vision_body', $aboutPage->vision_body) }}</textarea>
                @error('vision_body') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <h2 style="margin:24px 0 12px 0; font-size:15px;">Bottom banner (experience / video)</h2>
        <div class="grid grid-2">
            <div>
                <label for="cta_eyebrow">Small uppercase line</label>
                <input id="cta_eyebrow" name="cta_eyebrow" value="{{ old('cta_eyebrow', $aboutPage->cta_eyebrow) }}" placeholder="{{ $defaults['cta_eyebrow'] }}">
                @error('cta_eyebrow') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="cta_button_label">Button label</label>
                <input id="cta_button_label" name="cta_button_label" value="{{ old('cta_button_label', $aboutPage->cta_button_label) }}" placeholder="{{ $defaults['cta_button_label'] }}">
                @error('cta_button_label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cta_heading">Main heading</label>
                <textarea id="cta_heading" name="cta_heading" rows="2">{{ old('cta_heading', $aboutPage->cta_heading) }}</textarea>
                @error('cta_heading') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cta_background_file">Banner background — upload image only</label>
                <input id="cta_background_file" name="cta_background_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('cta_background_file') <div class="error">{{ $message }}</div> @enderror
                @if (filled($aboutPage->cta_background))
                    <div style="margin-top:8px;"><img src="{{ \App\Models\AboutPage::imageSrc($aboutPage->cta_background) }}" alt="" style="max-width:220px;height:auto;border-radius:8px;border:1px solid #e5e7eb;"></div>
                @endif
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cta_video_url">YouTube link (opens in video modal on the site)</label>
                <input id="cta_video_url" name="cta_video_url" value="{{ old('cta_video_url', $aboutPage->cta_video_url) }}" placeholder="https://www.youtube.com/watch?v=… or https://youtu.be/…">
                @error('cta_video_url') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 20px;">
            <button type="submit" class="btn btn-primary">Save About Us page</button>
        </div>
    </form>
</div>
@endsection
