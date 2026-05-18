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
                        <input id="tci_image" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-field-name="image_file">
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
                    <label for="img_main_file" style="font-size:12px;">Upload image <span style="color:#dc2626;">*</span></label>
                    <input id="img_main_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-field-name="image_file">
                    @error('image_file') <div class="error">{{ $message }}</div> @enderror
                    <div style="margin-top:10px;">
                        <label for="img_image_caption">Image title / caption</label>
                        <input id="img_image_caption" name="image_caption" placeholder="Optional">
                    </div>
                </div>

                <div style="border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; margin-top:12px;">
                    <div style="font-weight:700; margin-bottom:10px;">More images</div>
                    <p style="margin:0 0 10px 0; color:#64748b; font-size:12px;">Optional. Max {{ \App\Support\ImageUploadRules::maxMegabytesLabel() }} MB each (JPEG, PNG, WebP, GIF). macOS screenshots are often larger — compress if upload fails.</p>
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
                        <input id="ti_image_file" name="image_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif" data-field-name="image_file">
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
