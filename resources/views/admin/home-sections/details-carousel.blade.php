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

    <form method="POST" action="{{ route('admin.home-sections.details.store') }}" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="block_type" value="two_column">
        <input type="hidden" name="two_column_mode" value="image_details">

        <div class="details-grid">
            <div class="panel">
                <h2>Image</h2>
                <label for="image">Upload image</label>
                <input id="image" type="file" name="image" accept="image/*">
                @error('image') <div class="error">{{ $message }}</div> @enderror
                <div class="muted">This image will be used on the Home page About section.</div>

                <div style="margin-top: 12px;">
                    <label for="image_alt">Image alt text</label>
                    <input id="image_alt" name="image_alt" placeholder="Describe the image for accessibility">
                    <div class="muted">Meaningful description for screen readers.</div>
                    @error('image_alt') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="panel">
                <h2>Details</h2>

                <div>
                    <label for="layout_width">Width</label>
                    <select id="layout_width" name="layout_width" required>
                        <option value="full" @selected(old('layout_width', 'full') === 'full')>Full width</option>
                        <option value="short" @selected(old('layout_width', 'full') === 'short')>Short width</option>
                    </select>
                    <div class="muted">
                        Full width → Home page “About Us” full layout. Short width → compact layout.
                    </div>
                    @error('layout_width') <div class="error">{{ $message }}</div> @enderror
                </div>

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
                        @error('description') <div class="error">{{ $message }}</div> @enderror
                    </div>
                @endif

                @if (in_array('points', $fields ?? []))
                    <div style="margin-top:12px;">
                        <label>Point</label>
                        <div id="pointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                            <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                <input name="points[]" placeholder="Write a point">
                                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <button type="button" class="btn btn-muted" id="addPointBtn">Add another point</button>
                        </div>
                        <div class="muted">Each point will show as a bullet on the Home page.</div>
                        @error('points') <div class="error">{{ $message }}</div> @enderror
                        @error('points.*') <div class="error">{{ $message }}</div> @enderror
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

@if (in_array('points', $fields ?? []))
    <script>
        (() => {
            const wrap = document.getElementById('pointsWrap');
            const addBtn = document.getElementById('addPointBtn');
            if (!wrap || !addBtn) return;

            const template = () => {
                const row = document.createElement('div');
                row.className = 'grid-2';
                row.style.gridTemplateColumns = '1fr auto';
                row.style.gap = '10px';
                row.innerHTML = `
                    <input name="points[]" placeholder="Write a point">
                    <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                `;
                return row;
            };

            const syncRemoveButtons = () => {
                const rows = Array.from(wrap.querySelectorAll('.grid-2'));
                rows.forEach((row) => {
                    const btn = row.querySelector('[data-remove-point]');
                    if (!btn) return;
                    btn.onclick = () => {
                        row.remove();
                        syncRemoveButtons();
                    };
                });
            };

            syncRemoveButtons();

            addBtn.addEventListener('click', () => {
                wrap.appendChild(template());
                syncRemoveButtons();
            });
        })();
    </script>
@endif
@endsection
