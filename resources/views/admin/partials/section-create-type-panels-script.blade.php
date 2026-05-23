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

        const lcCreateLogoWrap = document.getElementById('lcCreateLogoWrap');
        const lcCreateAddLogoBtn = document.getElementById('lcCreateAddLogoBtn');
        let lcCreateLogoSeq = 0;
        const appendLcCreateLogoRow = () => {
            if (!lcCreateLogoWrap) return;
            const k = 'n' + (lcCreateLogoSeq++);
            const row = document.createElement('div');
            row.style.border = '1px dashed #cbd5e1';
            row.style.borderRadius = '8px';
            row.style.padding = '10px';
            row.innerHTML = `
                <label style="font-size:12px;">Image file</label>
                <input name="logo_items[${k}][file]" type="file" accept="image/jpeg,image/png,image/webp,image/gif" style="display:block; margin-top:4px;">
                <label style="font-size:12px; margin-top:8px; display:block;">Title (optional)</label>
                <input name="logo_items[${k}][title]" placeholder="ISO 14001" style="margin-top:4px; width:100%; max-width:420px;">
                <label style="font-size:12px; margin-top:8px; display:block;">URL (optional)</label>
                <input name="logo_items[${k}][url]" placeholder="https://…" style="margin-top:4px; width:100%; max-width:420px;">
            `;
            lcCreateLogoWrap.appendChild(row);
        };
        if (lcCreateAddLogoBtn) {
            lcCreateAddLogoBtn.addEventListener('click', appendLcCreateLogoRow);
            appendLcCreateLogoRow();
            appendLcCreateLogoRow();
        }

        const syncPanelFieldNames = (activeType) => {
            panels.forEach((p) => {
                const active = p.getAttribute('data-type') === activeType;
                p.querySelectorAll('input:not([type="hidden"]), textarea, select').forEach((el) => {
                    if (el.type === 'file') {
                        const fieldName = el.dataset.fieldName;
                        if (!fieldName) {
                            el.disabled = !active;

                            return;
                        }
                        if (active) {
                            el.setAttribute('name', fieldName);
                            el.disabled = false;
                        } else {
                            el.removeAttribute('name');
                            el.disabled = true;
                        }

                        return;
                    }

                    el.disabled = !active;
                });
            });
        };

        const setActive = (type) => {
            typeInput.value = type;
            saveBtn.disabled = !type;
            if (formsWrap) formsWrap.style.display = type ? 'block' : 'none';
            if (hint) hint.style.display = type ? 'none' : 'block';
            panels.forEach((p) => {
                p.style.display = p.getAttribute('data-type') === type ? 'block' : 'none';
            });
            syncPanelFieldNames(type);
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

        const form = document.getElementById('sectionCreateForm');
        if (form) {
            form.addEventListener('submit', () => {
                const type = typeInput.value;
                panels.forEach((p) => {
                    const active = p.getAttribute('data-type') === type;
                    p.querySelectorAll('input, textarea, select').forEach((el) => {
                        el.disabled = !active;
                    });
                });
                syncPanelFieldNames(type);
            });
        }

        const initialType = (typeInput.value || '').trim();
        if (initialType) {
            setActive(initialType);
        }
    })();
</script>
