@extends('layouts.admin', ['title' => 'Edit Home Section'])

@section('content')
<div class="header">
    <h1>Edit home section</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Back</a>
</div>

<style>
    .two-col { display:grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .panel { border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; }
    .panel h2 { margin:0 0 10px 0; font-size:14px; }
    .muted { color:#64748b; font-size:12px; margin-top:4px; }
    .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
</style>

@php($left = is_array($section->left_content ?? null) ? $section->left_content : [])
@php($right = is_array($section->right_content ?? null) ? $section->right_content : [])

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Type: <strong>{{ $section->block_type }}</strong> — Mode: <strong>{{ $section->two_column_mode }}</strong>
    </div>

    <form method="POST" action="{{ route('admin.home-sections.update', $section) }}">
        @csrf
        @method('PUT')

        <div class="two-col">
            <div class="panel">
                <h2>Box 1 (left)</h2>
                <div class="muted">This will render like “Our Mission”.</div>

                <div style="margin-top:12px;">
                    <label for="left_title">Title</label>
                    <input id="left_title" name="left_title" value="{{ old('left_title', $left['title'] ?? '') }}" placeholder="Our Mission">
                    @error('left_title') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-top:12px;">
                    <label for="left_description">Description</label>
                    <textarea id="left_description" name="left_description" rows="4" placeholder="...">{{ old('left_description', $left['description'] ?? '') }}</textarea>
                    @error('left_description') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-top:12px;">
                    <label>Point</label>
                    <div id="leftPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                        @php($existingLeft = is_array(old('left_points', $left['points'] ?? [])) ? old('left_points', $left['points'] ?? []) : [])
                        @if (count($existingLeft) < 1) @php($existingLeft = ['']) @endif
                        @foreach ($existingLeft as $p)
                            <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                <input name="left_points[]" value="{{ is_string($p) ? $p : '' }}" placeholder="Write a point">
                                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:10px;">
                        <button type="button" class="btn btn-muted" id="leftAddPointBtn">Add another point</button>
                    </div>
                    @error('left_points') <div class="error">{{ $message }}</div> @enderror
                    @error('left_points.*') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="panel">
                <h2>Box 2 (right)</h2>
                <div class="muted">This will render like “Our Vision”.</div>

                <div style="margin-top:12px;">
                    <label for="right_title">Title</label>
                    <input id="right_title" name="right_title" value="{{ old('right_title', $right['title'] ?? '') }}" placeholder="Our Vision">
                    @error('right_title') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-top:12px;">
                    <label for="right_description">Description</label>
                    <textarea id="right_description" name="right_description" rows="4" placeholder="...">{{ old('right_description', $right['description'] ?? '') }}</textarea>
                    @error('right_description') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div style="margin-top:12px;">
                    <label>Point</label>
                    <div id="rightPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                        @php($existingRight = is_array(old('right_points', $right['points'] ?? [])) ? old('right_points', $right['points'] ?? []) : [])
                        @if (count($existingRight) < 1) @php($existingRight = ['']) @endif
                        @foreach ($existingRight as $p)
                            <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                <input name="right_points[]" value="{{ is_string($p) ? $p : '' }}" placeholder="Write a point">
                                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:10px;">
                        <button type="button" class="btn btn-muted" id="rightAddPointBtn">Add another point</button>
                    </div>
                    @error('right_points') <div class="error">{{ $message }}</div> @enderror
                    @error('right_points.*') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-2" style="margin-top: 14px;">
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
        const setup = (wrapId, addBtnId, inputName) => {
            const wrap = document.getElementById(wrapId);
            const addBtn = document.getElementById(addBtnId);
            if (!wrap || !addBtn) return;

            const template = () => {
                const row = document.createElement('div');
                row.className = 'grid-2';
                row.style.gridTemplateColumns = '1fr auto';
                row.style.gap = '10px';
                row.innerHTML = `
                    <input name="${inputName}" placeholder="Write a point">
                    <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                `;
                return row;
            };

            const sync = () => {
                wrap.querySelectorAll('[data-remove-point]').forEach((btn) => {
                    btn.onclick = () => {
                        const row = btn.closest('div');
                        if (row) row.remove();
                    };
                });
            };

            sync();
            addBtn.addEventListener('click', () => {
                wrap.appendChild(template());
                sync();
            });
        };

        setup('leftPointsWrap', 'leftAddPointBtn', 'left_points[]');
        setup('rightPointsWrap', 'rightAddPointBtn', 'right_points[]');
    })();
</script>
@endsection

