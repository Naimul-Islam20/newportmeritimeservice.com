@extends('layouts.admin', ['title' => $pageTitle])

@section('content')
<div class="header">
    <h1>{{ $pageTitle }}</h1>
    <a class="btn btn-muted" href="{{ $backUrl }}">Back</a>
</div>

<div class="card" style="margin-bottom:12px; color:#64748b; font-size:13px;">
    Page: <strong>{{ $ownerLabel }}</strong>
</div>

@php($d = is_array($section->data ?? null) ? $section->data : [])

<div class="card">
    <form method="POST" action="{{ $updateUrl }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <input type="hidden" name="type" value="{{ $section->type }}">

        <div class="grid grid-2">
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

        @if ($section->type === 'two_column_image_details')
            <input type="hidden" name="image_side" id="tci_image_side" value="{{ old('image_side', data_get($d, 'image_side', 'left')) }}">
            <input type="hidden" name="layout_width" id="tci_layout_width" value="{{ old('layout_width', data_get($d, 'layout_width', 'full')) }}">

            <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap; margin-top:12px;">
                <div style="min-width: 220px;">
                    <div style="font-weight:700; margin-bottom:8px;">Image</div>
                    <button type="button" id="tciImageSideToggle"
                        style="width:72px; height:34px; border-radius:999px; border:1px solid #cbd5e1; background:#0b4a7a; padding:3px; position:relative; cursor:pointer;">
                        <span id="tciImageSideKnob"
                            style="display:block; width:28px; height:28px; border-radius:999px; background:#0ea5e9; transform:translateX(0); transition:transform .18s ease;"></span>
                    </button>
                    <div id="tciImageSideHint" style="color:#64748b; font-size:12px; margin-top:6px;"></div>
                </div>

                <div style="min-width: 220px;">
                    <div style="font-weight:700; margin-bottom:8px;">Full width</div>
                    <button type="button" id="tciWidthToggle"
                        style="width:72px; height:34px; border-radius:999px; border:1px solid #cbd5e1; background:#0b4a7a; padding:3px; position:relative; cursor:pointer;">
                        <span id="tciWidthKnob"
                            style="display:block; width:28px; height:28px; border-radius:999px; background:#0ea5e9; transform:translateX(0); transition:transform .18s ease;"></span>
                    </button>
                    <div id="tciWidthHint" style="color:#64748b; font-size:12px; margin-top:6px;"></div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-top:12px;">
                <div id="tciImageCol" style="order: 0; border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                    <div style="font-weight:700; margin-bottom:10px;">Image upload</div>
                    @if (is_string(data_get($d, 'image_path')) && data_get($d, 'image_path') !== '')
                        <div style="margin-bottom:10px;">
                            <img src="{{ asset(data_get($d, 'image_path')) }}" alt="" style="max-width: 260px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb;">
                        </div>
                    @endif
                    <label for="image_file" style="font-size:12px;">Upload new image (optional)</label>
                    <input id="image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                    @error('image_file') <div class="error">{{ $message }}</div> @enderror
                </div>

                <div id="tciDetailsCol" style="order: 1; border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                    <div style="font-weight:700; margin-bottom:10px;">Details</div>
                    <div style="margin-top:10px;">
                        <label for="mini_title">Short title</label>
                        <input id="mini_title" name="mini_title" value="{{ old('mini_title', data_get($d, 'mini_title')) }}" placeholder="Optional">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="title">Title</label>
                        <input id="title" name="title" value="{{ old('title', $section->title) }}" placeholder="Optional">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="description">Description</label>
                        <textarea id="description" name="description" rows="4" placeholder="Optional">{{ old('description', data_get($d, 'description')) }}</textarea>
                    </div>

                    <div style="margin-top:10px;">
                        <label>Point</label>
                        @php($pts = is_array(old('points', data_get($d, 'points', []))) ? old('points', data_get($d, 'points', [])) : [])
                        @if (count($pts) < 1) @php($pts = ['']) @endif
                        <div id="tciPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                            @foreach ($pts as $p)
                                <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                    <input name="points[]" value="{{ is_string($p) ? $p : '' }}" placeholder="Write a point">
                                    <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                                </div>
                            @endforeach
                        </div>
                        <div style="margin-top:10px;">
                            <button type="button" class="btn btn-muted" id="tciAddPointBtn">Add another point</button>
                        </div>
                        @error('points') <div class="error">{{ $message }}</div> @enderror
                        @error('points.*') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

        @elseif ($section->type === 'text_input')
            <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                <div style="font-weight:700; margin-bottom:10px;">Content</div>
                <div style="margin-top:10px;">
                    <label for="ti_mini_title">Short title</label>
                    <input id="ti_mini_title" name="mini_title" value="{{ old('mini_title', data_get($d, 'mini_title')) }}" placeholder="Optional small label">
                </div>
                <div style="margin-top:10px;">
                    <label for="ti_title">Title</label>
                    <input id="ti_title" name="title" value="{{ old('title', $section->title) }}" placeholder="Optional">
                </div>
                <div style="margin-top:10px;">
                    <label for="ti_description">Description</label>
                    <textarea id="ti_description" name="description" rows="4" placeholder="Optional">{{ old('description', data_get($d, 'description')) }}</textarea>
                </div>
                <div style="margin-top:10px;">
                    <label for="ti_image_file" style="font-size:12px;">Image</label>
                    @php($tiImg = data_get($d, 'image_path'))
                    @if (is_string($tiImg) && $tiImg !== '')
                        <div style="margin-bottom:8px;">
                            <img src="{{ asset($tiImg) }}" alt="" style="max-width:280px; max-height:160px; border-radius:8px; object-fit:contain;">
                        </div>
                    @endif
                    <input id="ti_image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                    @error('image_file') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div style="margin-top:10px;">
                    <label>Point</label>
                    @php($pts = is_array(old('points', data_get($d, 'points', []))) ? old('points', data_get($d, 'points', [])) : [])
                    @if (count($pts) < 1) @php($pts = ['']) @endif
                    <div id="tiPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                        @foreach ($pts as $p)
                            <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                <input name="points[]" value="{{ is_string($p) ? $p : '' }}" placeholder="Write a point">
                                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                            </div>
                        @endforeach
                    </div>
                    <div style="margin-top:10px;">
                        <button type="button" class="btn btn-muted" id="tiAddPointBtn">Add another point</button>
                    </div>
                    @error('points') <div class="error">{{ $message }}</div> @enderror
                    @error('points.*') <div class="error">{{ $message }}</div> @enderror
                </div>
                <div style="margin-top:10px;">
                    <label for="ti_bottom_description">Description (bottom)</label>
                    <textarea id="ti_bottom_description" name="bottom_description" rows="4" placeholder="Optional">{{ old('bottom_description', data_get($d, 'bottom_description')) }}</textarea>
                </div>
            </div>

        @elseif ($section->type === 'two_column_two_side_details')
            <div class="grid grid-2" style="margin-top:12px;">
                <div style="grid-column: 1 / -1;">
                    <label for="title">Section title</label>
                    <input id="title" name="title" value="{{ old('title', $section->title) }}" placeholder="Optional">
                </div>
                <div>
                    <label for="left_title">Left title</label>
                    <input id="left_title" name="left_title" value="{{ old('left_title', data_get($d, 'left_title')) }}" placeholder="Optional">
                </div>
                <div>
                    <label for="right_title">Right title</label>
                    <input id="right_title" name="right_title" value="{{ old('right_title', data_get($d, 'right_title')) }}" placeholder="Optional">
                </div>
                <div>
                    <label for="left_description">Left description</label>
                    <textarea id="left_description" name="left_description" rows="6" placeholder="...">{{ old('left_description', data_get($d, 'left_description')) }}</textarea>
                </div>
                <div>
                    <label for="right_description">Right description</label>
                    <textarea id="right_description" name="right_description" rows="6" placeholder="...">{{ old('right_description', data_get($d, 'right_description')) }}</textarea>
                </div>
            </div>

        @elseif ($section->type === 'image')
            @php($extras = is_array(data_get($d, 'extra_gallery')) ? array_values(data_get($d, 'extra_gallery')) : [])
            <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                <div style="font-weight:700; margin-bottom:10px;">Section text</div>
                <div style="margin-top:10px;">
                    <label for="img_mini_title">Short title</label>
                    <input id="img_mini_title" name="mini_title" value="{{ old('mini_title', data_get($d, 'mini_title')) }}" placeholder="Optional small label">
                </div>
                <div style="margin-top:10px;">
                    <label for="img_title">Title</label>
                    <input id="img_title" name="title" value="{{ old('title', $section->title) }}" placeholder="Optional">
                </div>
                <div style="margin-top:10px;">
                    <label for="img_description">Description</label>
                    <textarea id="img_description" name="description" rows="4" placeholder="Optional">{{ old('description', data_get($d, 'description')) }}</textarea>
                </div>
            </div>

            <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                <div style="font-weight:700; margin-bottom:10px;">Main image</div>
                @if (is_string(data_get($d, 'image_path')) && data_get($d, 'image_path') !== '')
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset(data_get($d, 'image_path')) }}" alt="" style="max-width: 260px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb;">
                    </div>
                @endif
                <label for="image_file" style="font-size:12px;">Upload new image (optional)</label>
                <input id="image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('image_file') <div class="error">{{ $message }}</div> @enderror
                <div style="margin-top:10px;">
                    <label for="img_image_caption">Image title / caption</label>
                    <input id="img_image_caption" name="image_caption" value="{{ old('image_caption', data_get($d, 'image_caption')) }}" placeholder="Optional">
                </div>
            </div>

            @if (count($extras) > 0)
                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                    <div style="font-weight:700; margin-bottom:10px;">Gallery images</div>
                    @foreach ($extras as $ei => $ex)
                        @if (is_array($ex) && is_string(data_get($ex, 'path')) && data_get($ex, 'path') !== '')
                            <div style="display:grid; grid-template-columns: 120px 1fr auto; gap:12px; align-items:start; padding:10px 0; border-bottom:1px solid #f1f5f9;">
                                <img src="{{ asset(data_get($ex, 'path')) }}" alt="" style="width:120px; height:80px; object-fit:cover; border-radius:6px; border:1px solid #e5e7eb;">
                                <div>
                                    <label style="font-size:12px;">Caption</label>
                                    <input name="extra_gallery_titles[{{ $ei }}]" value="{{ old('extra_gallery_titles.'.$ei, data_get($ex, 'title')) }}" placeholder="Optional" style="width:100%; max-width:360px;">
                                </div>
                                <label style="display:flex; align-items:center; gap:6px; font-size:12px; white-space:nowrap;">
                                    <input type="checkbox" name="extra_remove[]" value="{{ $ei }}"> Remove
                                </label>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                <div style="font-weight:700; margin-bottom:10px;">Add images</div>
                <div id="imgNewExtraWrap" style="display:flex; flex-direction:column; gap:12px;"></div>
                <div style="margin-top:10px;">
                    <button type="button" class="btn btn-muted" id="imgEditAddExtraBtn">Another image</button>
                </div>
                @error('extra_images') <div class="error">{{ $message }}</div> @enderror
                @error('extra_images.*.file') <div class="error">{{ $message }}</div> @enderror
            </div>
        @endif

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Update section</button>
        </div>
    </form>
</div>

<script>
    (() => {
        const sideToggle = document.getElementById('tciImageSideToggle');
        const sideKnob = document.getElementById('tciImageSideKnob');
        const sideInput = document.getElementById('tci_image_side');
        const sideHint = document.getElementById('tciImageSideHint');
        const imgCol = document.getElementById('tciImageCol');
        const detCol = document.getElementById('tciDetailsCol');

        const widthToggle = document.getElementById('tciWidthToggle');
        const widthKnob = document.getElementById('tciWidthKnob');
        const widthInput = document.getElementById('tci_layout_width');
        const widthHint = document.getElementById('tciWidthHint');

        const pointsWrap = document.getElementById('tciPointsWrap');
        const addPointBtn = document.getElementById('tciAddPointBtn');

        const setSideUi = (onLeft) => {
            if (!sideToggle || !sideKnob || !sideInput || !imgCol || !detCol) return;
            sideInput.value = onLeft ? 'left' : 'right';
            sideToggle.style.background = onLeft ? '#0b4a7a' : '#f1f5f9';
            sideToggle.style.borderColor = onLeft ? '#0b4a7a' : '#cbd5e1';
            sideKnob.style.transform = onLeft ? 'translateX(38px)' : 'translateX(0)';
            imgCol.style.order = onLeft ? '0' : '1';
            detCol.style.order = onLeft ? '1' : '0';
            if (sideHint) sideHint.textContent = onLeft ? 'ON → image left, details right' : 'OFF → image right, details left';
        };

        if (sideToggle) {
            setSideUi((sideInput?.value ?? 'left') === 'left');
            sideToggle.addEventListener('click', () => setSideUi((sideInput?.value ?? 'left') !== 'left'));
        }

        const setWidthUi = (isFull) => {
            if (!widthToggle || !widthKnob || !widthInput) return;
            widthInput.value = isFull ? 'full' : 'short';
            widthToggle.style.background = isFull ? '#0b4a7a' : '#f1f5f9';
            widthToggle.style.borderColor = isFull ? '#0b4a7a' : '#cbd5e1';
            widthKnob.style.transform = isFull ? 'translateX(38px)' : 'translateX(0)';
            if (widthHint) widthHint.textContent = isFull
                ? 'ON → About Us (full), OFF → Why Choose Us (short)'
                : 'OFF → Why Choose Us (short), ON → About Us (full)';
        };

        if (widthToggle) {
            setWidthUi((widthInput?.value ?? 'full') === 'full');
            widthToggle.addEventListener('click', () => setWidthUi((widthInput?.value ?? 'full') !== 'full'));
        }

        const syncRemove = () => {
            if (!pointsWrap) return;
            pointsWrap.querySelectorAll('[data-remove-point]').forEach((btn) => {
                btn.onclick = () => {
                    const row = btn.closest('div');
                    if (row) row.remove();
                };
            });
        };

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

        if (pointsWrap && addPointBtn) {
            syncRemove();
            addPointBtn.addEventListener('click', () => {
                pointsWrap.appendChild(template());
                syncRemove();
            });
        }

        const tiPointsWrap = document.getElementById('tiPointsWrap');
        const tiAddPointBtn = document.getElementById('tiAddPointBtn');
        if (tiPointsWrap && tiAddPointBtn) {
            const syncTiRemove = () => {
                tiPointsWrap.querySelectorAll('[data-remove-point]').forEach((btn) => {
                    btn.onclick = () => {
                        const row = btn.closest('div');
                        if (row) row.remove();
                    };
                });
            };
            syncTiRemove();
            tiAddPointBtn.addEventListener('click', () => {
                tiPointsWrap.appendChild(template());
                syncTiRemove();
            });
        }

        const imgNewExtraWrap = document.getElementById('imgNewExtraWrap');
        const imgEditAddExtraBtn = document.getElementById('imgEditAddExtraBtn');
        let imgEditExtraSeq = 0;
        const syncImgEditExtraRemove = () => {
            if (!imgNewExtraWrap) return;
            imgNewExtraWrap.querySelectorAll('[data-img-edit-remove-extra]').forEach((btn) => {
                btn.onclick = () => {
                    const row = btn.closest('[data-img-edit-extra-row]');
                    if (row) row.remove();
                };
            });
        };
        const appendImgEditExtraRow = () => {
            if (!imgNewExtraWrap) return;
            const k = 'n' + (imgEditExtraSeq++);
            const row = document.createElement('div');
            row.setAttribute('data-img-edit-extra-row', '1');
            row.style.border = '1px dashed #cbd5e1';
            row.style.borderRadius = '8px';
            row.style.padding = '10px';
            row.innerHTML = `
                <div style="font-size:12px; color:#64748b; margin-bottom:8px;">New upload</div>
                <label style="font-size:12px;">File</label>
                <input name="extra_images[${k}][file]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:block; margin-top:4px;">
                <label style="font-size:12px; margin-top:8px; display:block;">Image title (optional)</label>
                <input name="extra_images[${k}][title]" placeholder="Caption" style="margin-top:4px; width:100%; max-width:420px;">
                <div style="margin-top:8px;">
                    <button type="button" class="btn btn-muted" data-img-edit-remove-extra style="padding:6px 10px;">Remove</button>
                </div>
            `;
            imgNewExtraWrap.appendChild(row);
            syncImgEditExtraRemove();
        };
        if (imgEditAddExtraBtn) {
            imgEditAddExtraBtn.addEventListener('click', appendImgEditExtraRow);
        }
    })();
</script>
@endsection

