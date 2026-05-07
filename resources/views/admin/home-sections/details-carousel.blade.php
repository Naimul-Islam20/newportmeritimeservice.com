@extends('layouts.admin', ['title' => 'Image + Details'])

@section('content')
<div class="header">
    <h1>Image + details</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.create') }}">Back</a>
</div>

<style>
    .details-grid { display:grid; grid-template-columns: 320px 1fr; gap: 14px; }
    .panel { border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; }
    .panel h2 { margin:0 0 10px 0; font-size:14px; }
    .muted { color:#64748b; font-size:12px; margin-top:4px; }
    .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 900px) { .details-grid { grid-template-columns: 1fr; } }
</style>

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Layout: <strong>Image + details</strong> — image is always part of this block.
    </div>

    <form method="POST" action="{{ route('admin.home-sections.details.store') }}">
        @csrf
        <input type="hidden" name="block_type" value="two_column">
        <input type="hidden" name="two_column_mode" value="image_details">

        <div class="details-grid">
            <div class="panel">
                <h2>Image</h2>
                <label for="image">Upload image</label>
                <input id="image" type="file" name="image" accept="image/*">
                <div class="muted">Static UI only. Upload will be connected later.</div>

                <div style="margin-top: 12px;">
                    <label for="image_alt">Image alt text</label>
                    <input id="image_alt" name="image_alt" placeholder="Describe the image for accessibility">
                    <div class="muted">Meaningful description for screen readers.</div>
                </div>
            </div>

            <div class="panel">
                <h2>Details</h2>

                @if (in_array('mini_title', $fields ?? []))
                    <div>
                        <label for="mini_title">Mini title</label>
                        <input id="mini_title" name="mini_title" placeholder="Small label above the main title">
                        <div class="muted">Example: Trusted Partner</div>
                    </div>
                @endif

                @if (in_array('title', $fields ?? []))
                    <div style="margin-top:12px;">
                        <label for="title">Title</label>
                        <input id="title" name="title" placeholder="Main headline">
                        <div class="muted">Example: One Partner. Every Need.</div>
                    </div>
                @endif

                @if (in_array('description', $fields ?? []))
                    <div style="margin-top:12px;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="3" placeholder="Short supporting paragraph..."></textarea>
                        <div class="muted">Keep it concise and clear.</div>
                    </div>
                @endif

                @if (in_array('points', $fields ?? []))
                    <div style="margin-top:12px;">
                        <label for="points">Bullet points</label>
                        <textarea id="points" name="points" rows="4" placeholder="One point per line"></textarea>
                        <div class="muted">One highlight per line.</div>
                    </div>
                @endif

                @if (in_array('button', $fields ?? []))
                    <div class="grid-2" style="margin-top:12px;">
                        <div>
                            <label for="button_text">Button text</label>
                            <input id="button_text" name="button_text" placeholder="e.g., Learn more">
                        </div>
                        <div>
                            <label for="button_url">Button URL</label>
                            <input id="button_url" name="button_url" placeholder="/contact or https://...">
                        </div>
                    </div>
                @endif

                <div style="margin-top: 14px;">
                    <button class="btn btn-primary" type="submit">Save details</button>
                    <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Cancel</a>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection
