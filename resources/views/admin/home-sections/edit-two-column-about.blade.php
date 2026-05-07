@extends('layouts.admin', ['title' => 'Edit Home Section'])

@section('content')
<div class="header">
    <h1>Edit home section</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Back</a>
</div>

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Type: <strong>{{ $section->block_type }}</strong> — Mode: <strong>{{ $section->two_column_mode }}</strong>
    </div>

    <form method="POST" action="{{ route('admin.home-sections.update', $section) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <h2 style="margin: 6px 0 10px 0; font-size: 15px;">Image</h2>
                @if (is_string($section->image_path) && $section->image_path !== '')
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset($section->image_path) }}" alt="" style="max-width: 320px; width: 100%; height: auto; border-radius: 10px; border:1px solid #e5e7eb;">
                        <div style="margin-top:6px; color:#64748b; font-size:12px;">
                            Current: <code style="font-size:11px;">{{ $section->image_path }}</code>
                        </div>
                    </div>
                @endif
                <label for="image">Upload image</label>
                <input id="image" name="image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('image') <div class="error">{{ $message }}</div> @enderror
                <div style="margin-top:10px;">
                    <label for="image_alt">Image alt</label>
                    <input id="image_alt" name="image_alt" value="{{ old('image_alt', $section->image_alt) }}" placeholder="Optional">
                    @error('image_alt') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="layout_width">Width</label>
                <select id="layout_width" name="layout_width" required>
                    <option value="full" @selected(old('layout_width', $section->layout_width) === 'full')>Full width</option>
                    <option value="short" @selected(old('layout_width', $section->layout_width) === 'short')>Short width</option>
                </select>
                @error('layout_width') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="mini_title">Mini title</label>
                <input id="mini_title" name="mini_title" value="{{ old('mini_title', $section->mini_title) }}" placeholder="Optional">
                @error('mini_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="title">Title</label>
                <input id="title" name="title" value="{{ old('title', $section->title) }}" placeholder="Optional">
                @error('title') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Optional">{{ old('description', $section->description) }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label>Point</label>
                <div id="editPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                    @php($existingPoints = is_array(old('points', $section->points ?? [])) ? old('points', $section->points ?? []) : [])
                    @if (count($existingPoints) < 1)
                        @php($existingPoints = [''])
                    @endif
                    @foreach ($existingPoints as $p)
                        <div class="grid grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                            <input name="points[]" value="{{ is_string($p) ? $p : '' }}" placeholder="Write a point">
                            <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                        </div>
                    @endforeach
                </div>
                <div style="margin-top:10px;">
                    <button type="button" class="btn btn-muted" id="editAddPointBtn">Add another point</button>
                </div>
                @error('points') <div class="error">{{ $message }}</div> @enderror
                @error('points.*') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="button_text">Button text</label>
                <input id="button_text" name="button_text" value="{{ old('button_text', $section->button_label) }}" placeholder="Optional">
                @error('button_text') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="button_url">Button URL</label>
                <input id="button_url" name="button_url" value="{{ old('button_url', $section->button_url) }}" placeholder="Optional">
                @error('button_url') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $section->sort_order) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $section->is_active) == 1)>Active</option>
                    <option value="0" @selected(old('is_active', $section->is_active) == 0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Update</button>
        </div>
    </form>
</div>

<script>
    (() => {
        const wrap = document.getElementById('editPointsWrap');
        const addBtn = document.getElementById('editAddPointBtn');
        if (!wrap || !addBtn) return;

        const template = () => {
            const row = document.createElement('div');
            row.className = 'grid grid-2';
            row.style.gridTemplateColumns = '1fr auto';
            row.style.gap = '10px';
            row.innerHTML = `
                <input name="points[]" placeholder="Write a point">
                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
            `;
            return row;
        };

        const syncRemoveButtons = () => {
            const rows = Array.from(wrap.querySelectorAll('[data-remove-point]'));
            rows.forEach((btn) => {
                btn.onclick = () => {
                    const row = btn.closest('div');
                    if (row) row.remove();
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
@endsection

