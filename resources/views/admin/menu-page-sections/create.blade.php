@extends('layouts.admin', ['title' => $pageTitle])

@section('content')
<div class="header">
    <h1>{{ $pageTitle }}</h1>
    <a class="btn btn-muted" href="{{ $backUrl }}">Back to list</a>
</div>

<div class="card">
    <p style="margin:0 0 14px 0; color:#64748b; font-size:13px;">
        Page: <strong>{{ $ownerLabel }}</strong>
    </p>

    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:14px; padding-bottom:14px; border-bottom:1px solid #e2e8f0;">
        <button type="button" class="btn btn-muted" data-type-btn="video" disabled title="Blocked for now"
            style="opacity:.5; cursor:not-allowed;">Video</button>
        <button type="button" class="btn btn-muted" data-type-btn="image">Image</button>
        <button type="button" class="btn btn-muted" data-type-btn="two_column_image_details">Image and details</button>
        <button type="button" class="btn btn-muted" data-type-btn="two_column_two_side_details">2 side details</button>
        <button type="button" class="btn btn-muted" data-type-btn="text_input">Text &amp; points</button>
        <button type="button" class="btn btn-muted" data-type-btn="carousel_simple" disabled title="Blocked for now"
            style="opacity:.5; cursor:not-allowed;">Simple carousel</button>
        <button type="button" class="btn btn-muted" data-type-btn="carousel_content" disabled title="Blocked for now"
            style="opacity:.5; cursor:not-allowed;">Content carousel</button>
    </div>

    <form method="post" action="{{ $postUrl ?? '#' }}" id="sectionCreateForm" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="type" id="sectionType" value="">
        <div id="typeHint" style="color:#64748b; font-size:13px; padding:10px 12px; border:1px dashed #cbd5e1; border-radius:8px;">
            Select a section type above to see the form.
        </div>

        <div id="typeForms" style="display:none; margin-top:12px;">
            <div class="type-panel" data-type="two_column_image_details" style="display:none;">
                <input type="hidden" name="image_side" id="tci_image_side" value="{{ old('image_side', 'left') }}">
                <input type="hidden" name="layout_width" id="tci_layout_width" value="{{ old('layout_width', 'full') }}">

                <div style="display:flex; align-items:flex-start; justify-content:space-between; gap:14px; flex-wrap:wrap;">
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
                        <div id="tciWidthHint" style="color:#64748b; font-size:12px; margin-top:6px;">ON → About Us (full), OFF → Why Choose Us (short)</div>
                    </div>
                </div>

                <div id="tciTwoColWrap" style="display:grid; grid-template-columns: 1fr 1fr; gap:14px; margin-top:12px;">
                    <div id="tciImageCol" style="order: 0; border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                        <div style="font-weight:700; margin-bottom:10px;">Image upload</div>
                        <label for="tci_image" style="font-size:12px;">Upload image</label>
                        <input id="tci_image" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                    </div>

                    <div id="tciDetailsCol" style="order: 1; border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                        <div style="font-weight:700; margin-bottom:10px;">Details</div>

                        <div style="margin-top:10px;">
                            <label for="tci_mini_title">Short title</label>
                            <input id="tci_mini_title" name="mini_title" placeholder="Optional small label">
                        </div>
                        <div style="margin-top:10px;">
                            <label for="tci_title">Title</label>
                            <input id="tci_title" name="title" placeholder="Optional">
                        </div>
                        <div style="margin-top:10px;">
                            <label for="tci_description">Description</label>
                            <textarea id="tci_description" name="description" rows="4" placeholder="Optional"></textarea>
                        </div>

                        <div style="margin-top:10px;">
                            <label>Point</label>
                            <div id="tciPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                                <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                    <input name="points[]" placeholder="Write a point">
                                    <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                                </div>
                            </div>
                            <div style="margin-top:10px;">
                                <button type="button" class="btn btn-muted" id="tciAddPointBtn">Add another point</button>
                            </div>
                        </div>

                        <div style="margin-top:10px;">
                            <label for="is_active_tci">Status</label>
                            <select id="is_active_tci" name="is_active">
                                <option value="1">Active</option>
                                <option value="0">Inactive</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="type-panel" data-type="image" style="display:none;">
                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                    <div style="font-weight:700; margin-bottom:10px;">Section text</div>
                    <div style="margin-top:10px;">
                        <label for="img_mini_title">Short title</label>
                        <input id="img_mini_title" name="mini_title" placeholder="Optional small label">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="img_title">Title</label>
                        <input id="img_title" name="title" placeholder="Optional">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="img_description">Description</label>
                        <textarea id="img_description" name="description" rows="4" placeholder="Optional"></textarea>
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                    <div style="font-weight:700; margin-bottom:10px;">Main image</div>
                    <label for="img_main_file" style="font-size:12px;">Upload image</label>
                    <input id="img_main_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                    <div style="margin-top:10px;">
                        <label for="img_image_caption">Image title / caption</label>
                        <input id="img_image_caption" name="image_caption" placeholder="Optional">
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                    <div style="font-weight:700; margin-bottom:10px;">More images</div>
                    <div id="imgExtraWrap" style="display:flex; flex-direction:column; gap:12px;"></div>
                    <div style="margin-top:10px;">
                        <button type="button" class="btn btn-muted" id="imgAddExtraBtn">Another image</button>
                    </div>
                </div>

                <div style="margin-top:12px;">
                    <label for="is_active_img">Status</label>
                    <select id="is_active_img" name="is_active">
                        <option value="1">Active</option>
                        <option value="0">Inactive</option>
                    </select>
                </div>
            </div>

            <div class="type-panel" data-type="text_input" style="display:none;">
                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px;">
                    <div style="font-weight:700; margin-bottom:10px;">Content</div>
                    <div style="margin-top:10px;">
                        <label for="ti_mini_title">Short title</label>
                        <input id="ti_mini_title" name="mini_title" placeholder="Optional small label">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="ti_title">Title</label>
                        <input id="ti_title" name="title" placeholder="Optional">
                    </div>
                    <div style="margin-top:10px;">
                        <label for="ti_description">Description</label>
                        <textarea id="ti_description" name="description" rows="4" placeholder="Optional"></textarea>
                    </div>
                    <div style="margin-top:10px;">
                        <label for="ti_image_file" style="font-size:12px;">Image</label>
                        <input id="ti_image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                        @error('image_file') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div style="margin-top:10px;">
                        <label>Point</label>
                        <div id="tiPointsWrap" style="display:flex; flex-direction:column; gap:10px;">
                            <div class="grid-2" style="grid-template-columns: 1fr auto; gap:10px;">
                                <input name="points[]" placeholder="Write a point">
                                <button type="button" class="btn btn-muted" data-remove-point style="padding:8px 12px;">Remove</button>
                            </div>
                        </div>
                        <div style="margin-top:10px;">
                            <button type="button" class="btn btn-muted" id="tiAddPointBtn">Add another point</button>
                        </div>
                    </div>
                    <div style="margin-top:10px;">
                        <label for="ti_bottom_description">Description (bottom)</label>
                        <textarea id="ti_bottom_description" name="bottom_description" rows="4" placeholder="Optional"></textarea>
                    </div>
                    <div style="margin-top:10px;">
                        <label for="is_active_ti">Status</label>
                        <select id="is_active_ti" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="type-panel" data-type="two_column_two_side_details" style="display:none;">
                <div class="grid grid-2">
                    <div>
                        <label for="tcs_left_title">Left title</label>
                        <input id="tcs_left_title" name="left_title" placeholder="Optional">
                    </div>
                    <div>
                        <label for="tcs_right_title">Right title</label>
                        <input id="tcs_right_title" name="right_title" placeholder="Optional">
                    </div>
                    <div>
                        <label for="tcs_left_description">Left description</label>
                        <textarea id="tcs_left_description" name="left_description" rows="6" placeholder="..."></textarea>
                    </div>
                    <div>
                        <label for="tcs_right_description">Right description</label>
                        <textarea id="tcs_right_description" name="right_description" rows="6" placeholder="..."></textarea>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <label for="tcs_title">Section title</label>
                        <input id="tcs_title" name="title" placeholder="Optional">
                    </div>
                    <div>
                        <label for="is_active_tcs">Status</label>
                        <select id="is_active_tcs" name="is_active">
                            <option value="1">Active</option>
                            <option value="0">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>

        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" id="saveBtn" disabled>Save section</button>
        </div>
    </form>
</div>

<script>
    (() => {
        const typeInput = document.getElementById('sectionType');
        const saveBtn = document.getElementById('saveBtn');
        const buttons = Array.from(document.querySelectorAll('[data-type-btn]'));
        const formsWrap = document.getElementById('typeForms');
        const hint = document.getElementById('typeHint');
        const panels = Array.from(document.querySelectorAll('.type-panel[data-type]'));
        const tciSideToggle = document.getElementById('tciImageSideToggle');
        const tciSideKnob = document.getElementById('tciImageSideKnob');
        const tciSideInput = document.getElementById('tci_image_side');
        const tciSideHint = document.getElementById('tciImageSideHint');
        const tciImageCol = document.getElementById('tciImageCol');
        const tciDetailsCol = document.getElementById('tciDetailsCol');
        const tciPointsWrap = document.getElementById('tciPointsWrap');
        const tciAddPointBtn = document.getElementById('tciAddPointBtn');
        const tciWidthToggle = document.getElementById('tciWidthToggle');
        const tciWidthKnob = document.getElementById('tciWidthKnob');
        const tciWidthInput = document.getElementById('tci_layout_width');
        const tciWidthHint = document.getElementById('tciWidthHint');
        if (!typeInput || !saveBtn || buttons.length === 0) return;

        const setTciSideUi = (onLeft) => {
            if (!tciSideToggle || !tciSideKnob || !tciSideInput || !tciImageCol || !tciDetailsCol) return;
            tciSideInput.value = onLeft ? 'left' : 'right';
            tciSideToggle.style.background = onLeft ? '#0b4a7a' : '#f1f5f9';
            tciSideToggle.style.borderColor = onLeft ? '#0b4a7a' : '#cbd5e1';
            tciSideKnob.style.transform = onLeft ? 'translateX(38px)' : 'translateX(0)';
            tciImageCol.style.order = onLeft ? '0' : '1';
            tciDetailsCol.style.order = onLeft ? '1' : '0';
            if (tciSideHint) {
                tciSideHint.textContent = onLeft ? 'ON → image left, details right' : 'OFF → image right, details left';
            }
        };

        if (tciSideToggle) {
            setTciSideUi((tciSideInput?.value ?? 'left') === 'left');
            tciSideToggle.addEventListener('click', () => {
                setTciSideUi((tciSideInput?.value ?? 'left') !== 'left');
            });
        }

        const setTciWidthUi = (isFull) => {
            if (!tciWidthToggle || !tciWidthKnob || !tciWidthInput) return;
            tciWidthInput.value = isFull ? 'full' : 'short';
            tciWidthToggle.style.background = isFull ? '#0b4a7a' : '#f1f5f9';
            tciWidthToggle.style.borderColor = isFull ? '#0b4a7a' : '#cbd5e1';
            tciWidthKnob.style.transform = isFull ? 'translateX(38px)' : 'translateX(0)';
            if (tciWidthHint) {
                tciWidthHint.textContent = isFull
                    ? 'ON → About Us (full), OFF → Why Choose Us (short)'
                    : 'OFF → Why Choose Us (short), ON → About Us (full)';
            }
        };

        if (tciWidthToggle) {
            setTciWidthUi((tciWidthInput?.value ?? 'full') === 'full');
            tciWidthToggle.addEventListener('click', () => {
                setTciWidthUi((tciWidthInput?.value ?? 'full') !== 'full');
            });
        }

        const setupPoints = (wrap, addBtn) => {
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

        setupPoints(tciPointsWrap, tciAddPointBtn);

        const tiPointsWrap = document.getElementById('tiPointsWrap');
        const tiAddPointBtn = document.getElementById('tiAddPointBtn');
        setupPoints(tiPointsWrap, tiAddPointBtn);

        const imgExtraWrap = document.getElementById('imgExtraWrap');
        const imgAddExtraBtn = document.getElementById('imgAddExtraBtn');
        let imgExtraIdx = 0;
        const syncImgExtraRemove = () => {
            if (!imgExtraWrap) return;
            imgExtraWrap.querySelectorAll('[data-img-remove-extra]').forEach((btn) => {
                btn.onclick = () => {
                    const row = btn.closest('[data-img-extra-row]');
                    if (row) row.remove();
                };
            });
        };
        const appendImgExtraRow = () => {
            if (!imgExtraWrap) return;
            const i = imgExtraIdx++;
            const row = document.createElement('div');
            row.setAttribute('data-img-extra-row', '1');
            row.style.border = '1px dashed #cbd5e1';
            row.style.borderRadius = '8px';
            row.style.padding = '10px';
            row.innerHTML = `
                <div style="font-size:12px; color:#64748b; margin-bottom:8px;">Additional image</div>
                <label style="font-size:12px;">File</label>
                <input name="extra_images[${i}][file]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:block; margin-top:4px;">
                <label style="font-size:12px; margin-top:8px; display:block;">Image title (optional)</label>
                <input name="extra_images[${i}][title]" placeholder="Caption" style="margin-top:4px; width:100%; max-width:420px;">
                <div style="margin-top:8px;">
                    <button type="button" class="btn btn-muted" data-img-remove-extra style="padding:6px 10px;">Remove</button>
                </div>
            `;
            imgExtraWrap.appendChild(row);
            syncImgExtraRemove();
        };
        if (imgAddExtraBtn) {
            imgAddExtraBtn.addEventListener('click', appendImgExtraRow);
        }

        const setActive = (type) => {
            typeInput.value = type;
            saveBtn.disabled = !type;
            if (formsWrap) formsWrap.style.display = type ? 'block' : 'none';
            if (hint) hint.style.display = type ? 'none' : 'block';
            panels.forEach((p) => {
                const active = p.getAttribute('data-type') === type;
                p.style.display = active ? 'block' : 'none';
                p.querySelectorAll('input:not([type="hidden"]), textarea, select').forEach((el) => {
                    el.disabled = !active;
                });
            });
            if (type === 'two_column_image_details') {
                setTciSideUi((tciSideInput?.value ?? 'left') === 'left');
                setTciWidthUi((tciWidthInput?.value ?? 'full') === 'full');
            }
            buttons.forEach((b) => {
                const isActive = b.getAttribute('data-type-btn') === type;
                b.classList.toggle('btn-primary', isActive);
                b.classList.toggle('btn-muted', !isActive);
            });
        };

        buttons.forEach((b) => {
            b.addEventListener('click', () => {
                if (b.disabled) return;
                const t = b.getAttribute('data-type-btn');
                setActive(t);
            });
        });
    })();
</script>
@endsection
