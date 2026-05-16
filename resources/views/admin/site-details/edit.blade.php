@extends('layouts.admin', ['title' => 'Site Details'])

@section('content')
<div class="header">
    <h1>Site details</h1>
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.site-details.update', $detail) }}" id="siteDetailsForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="grid grid-2">
            <div style="grid-column: 1 / -1;">
                <label for="location">Location</label>
                <textarea id="location" name="location" rows="3" placeholder="Office address / location">{{ old('location', $detail->location) }}</textarea>
                @error('location') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div style="grid-column: 1 / -1;">
                <label for="map">Map</label>
                <textarea id="map" name="map" rows="3" placeholder="Google map link or embed code">{{ old('map', $detail->map) }}</textarea>
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Tip: paste Google Maps share link or iframe embed code.
                </div>
                @error('map') <div class="error">{{ $message }}</div> @enderror
            </div>

            <div>
                <label>Email</label>
                <div id="emailsWrap" class="stack">
                    @php($emailsOld = old('emails', $detail->emails ?? []))
                    @php($emailsOld = is_array($emailsOld) ? $emailsOld : [])
                    @if (count($emailsOld) < 1)
                        @php($emailsOld = [''])
                    @endif
                    @foreach ($emailsOld as $i => $email)
                        <div class="row">
                            <input name="emails[]" value="{{ $email }}" placeholder="example@email.com">
                            <button type="button" class="btn btn-danger btn-mini js-remove" title="Remove">×</button>
                        </div>
                    @endforeach
                </div>
                @error('emails') <div class="error">{{ $message }}</div> @enderror
                @error('emails.*') <div class="error">{{ $message }}</div> @enderror
                <div style="margin-top: 8px;">
                    <button type="button" class="btn btn-muted btn-mini" id="addEmailBtn">Another email</button>
                </div>
            </div>

            <div>
                <label>Number</label>
                <div id="phonesWrap" class="stack">
                    @php($phonesOld = old('phones', $detail->phones ?? []))
                    @php($phonesOld = is_array($phonesOld) ? $phonesOld : [])
                    @if (count($phonesOld) < 1)
                        @php($phonesOld = [''])
                    @endif
                    @foreach ($phonesOld as $i => $phone)
                        <div class="row">
                            <input name="phones[]" value="{{ $phone }}" placeholder="+8801XXXXXXXXX">
                            <button type="button" class="btn btn-danger btn-mini js-remove" title="Remove">×</button>
                        </div>
                    @endforeach
                </div>
                @error('phones') <div class="error">{{ $message }}</div> @enderror
                @error('phones.*') <div class="error">{{ $message }}</div> @enderror
                <div style="margin-top: 8px;">
                    <button type="button" class="btn btn-muted btn-mini" id="addPhoneBtn">Another number</button>
                </div>
            </div>

            <div style="grid-column: 1 / -1;">
                <h2 style="margin: 6px 0 10px 0; font-size: 15px;">Social links</h2>
                <div class="grid grid-2">
                    @php($social = old('social', $detail->social_links ?? []))
                    @php($social = is_array($social) ? $social : [])
                    <div>
                        <label for="facebook">Facebook</label>
                        <input id="facebook" name="social[facebook]" value="{{ $social['facebook'] ?? '' }}" placeholder="https://facebook.com/...">
                        @error('social.facebook') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="linkedin">LinkedIn</label>
                        <input id="linkedin" name="social[linkedin]" value="{{ $social['linkedin'] ?? '' }}" placeholder="https://linkedin.com/...">
                        @error('social.linkedin') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="youtube">YouTube</label>
                        <input id="youtube" name="social[youtube]" value="{{ $social['youtube'] ?? '' }}" placeholder="https://youtube.com/...">
                        @error('social.youtube') <div class="error">{{ $message }}</div> @enderror
                    </div>
                    <div>
                        <label for="twitter">Twitter/X</label>
                        <input id="twitter" name="social[twitter]" value="{{ $social['twitter'] ?? '' }}" placeholder="https://x.com/...">
                        @error('social.twitter') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>

            <div style="grid-column: 1 / -1;">
                <h2 style="margin: 6px 0 10px 0; font-size: 15px;">Brand colors</h2>
                <p style="margin: 0 0 12px 0; color:#64748b; font-size: 13px; line-height: 1.45;">
                    These apply to the public site and the admin panel (navigation, buttons, login). Empty values fall back to built-in defaults until you save; use &quot;Reset all theme colors&quot; to clear saved overrides.
                </p>
                <label style="display:flex; align-items:center; gap:10px; font-weight:600; margin-bottom:14px; cursor:pointer;">
                    <input type="checkbox" name="reset_theme_colors" value="1" @checked(old('reset_theme_colors'))>
                    Reset all theme colors to built-in defaults
                </label>
                @error('theme_brand_navy') <div class="error">{{ $message }}</div> @enderror
                @error('theme_brand_navy_mid') <div class="error">{{ $message }}</div> @enderror
                @error('theme_brand_accent') <div class="error">{{ $message }}</div> @enderror
                @error('theme_brand_accent_hover') <div class="error">{{ $message }}</div> @enderror
                @error('theme_brand_topbar_muted') <div class="error">{{ $message }}</div> @enderror
                @error('theme_footer_overlay_base') <div class="error">{{ $message }}</div> @enderror
                @error('theme_footer_overlay_opacity') <div class="error">{{ $message }}</div> @enderror

                <div class="grid grid-2 theme-colors-grid">
                    <div class="theme-color-field">
                        <label for="theme_brand_navy">Navy (main)</label>
                        <div class="theme-color-row">
                            <input id="theme_brand_navy_picker" type="color" value="{{ $themeHex['theme_brand_navy'] }}" aria-label="Pick navy" class="theme-swatch">
                            <input id="theme_brand_navy" name="theme_brand_navy" type="text" inputmode="text" autocomplete="off" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_brand_navy'] }}" class="theme-hex">
                        </div>
                    </div>
                    <div class="theme-color-field">
                        <label for="theme_brand_navy_mid">Navy mid (borders)</label>
                        <div class="theme-color-row">
                            <input id="theme_brand_navy_mid_picker" type="color" value="{{ $themeHex['theme_brand_navy_mid'] }}" aria-label="Pick navy mid" class="theme-swatch">
                            <input id="theme_brand_navy_mid" name="theme_brand_navy_mid" type="text" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_brand_navy_mid'] }}" class="theme-hex">
                        </div>
                    </div>
                    <div class="theme-color-field">
                        <label for="theme_brand_accent">Accent (CTAs, links)</label>
                        <div class="theme-color-row">
                            <input id="theme_brand_accent_picker" type="color" value="{{ $themeHex['theme_brand_accent'] }}" aria-label="Pick accent" class="theme-swatch">
                            <input id="theme_brand_accent" name="theme_brand_accent" type="text" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_brand_accent'] }}" class="theme-hex">
                        </div>
                    </div>
                    <div class="theme-color-field">
                        <label for="theme_brand_accent_hover">Accent hover</label>
                        <div class="theme-color-row">
                            <input id="theme_brand_accent_hover_picker" type="color" value="{{ $themeHex['theme_brand_accent_hover'] }}" aria-label="Pick accent hover" class="theme-swatch">
                            <input id="theme_brand_accent_hover" name="theme_brand_accent_hover" type="text" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_brand_accent_hover'] }}" class="theme-hex">
                        </div>
                    </div>
                    <div class="theme-color-field">
                        <label for="theme_brand_topbar_muted">Muted text (top bar / sidebar)</label>
                        <div class="theme-color-row">
                            <input id="theme_brand_topbar_muted_picker" type="color" value="{{ $themeHex['theme_brand_topbar_muted'] }}" aria-label="Pick muted" class="theme-swatch">
                            <input id="theme_brand_topbar_muted" name="theme_brand_topbar_muted" type="text" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_brand_topbar_muted'] }}" class="theme-hex">
                        </div>
                    </div>
                    <div class="theme-color-field">
                        <label for="theme_footer_overlay_base">Footer overlay (base)</label>
                        <div class="theme-color-row">
                            <input id="theme_footer_overlay_base_picker" type="color" value="{{ $themeHex['theme_footer_overlay_base'] }}" aria-label="Pick footer overlay" class="theme-swatch">
                            <input id="theme_footer_overlay_base" name="theme_footer_overlay_base" type="text" pattern="^#[0-9A-Fa-f]{6}$" maxlength="7" value="{{ $themeHex['theme_footer_overlay_base'] }}" class="theme-hex">
                        </div>
                    </div>
                </div>
                <div style="margin-top: 12px;">
                    @php($opOld = old('theme_footer_overlay_opacity'))
                    @php($opVal = $opOld !== null && $opOld !== '' ? (int) $opOld : ($detail->theme_footer_overlay_opacity ?? $themeDef['theme_footer_overlay_opacity']))
                    <label for="theme_footer_overlay_opacity">Footer overlay opacity (%)</label>
                    <input id="theme_footer_overlay_opacity" name="theme_footer_overlay_opacity" type="number" min="0" max="100" value="{{ $opVal }}" style="max-width: 120px;">
                </div>
            </div>

            <div style="grid-column: 1 / -1;">
                <h2 style="margin: 6px 0 10px 0; font-size: 15px;">Site default image</h2>
                @php($defaultImagePath = $detail->default_image_path)
                @if (is_string($defaultImagePath) && $defaultImagePath !== '')
                    <div style="margin-bottom:10px;">
                        <img src="{{ asset($defaultImagePath) }}" alt="" style="max-width: 260px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb;">
                        <div style="margin-top:6px; color:#64748b; font-size:12px;">
                            Current: <code style="font-size:11px;">{{ $defaultImagePath }}</code>
                        </div>
                    </div>
                @endif
                <label for="default_image">Upload image</label>
                <input id="default_image" name="default_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('default_image') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:8px;">
                    This image is also used automatically as the footer background.
                </div>
            </div>

            <div style="grid-column: 1 / -1;">
                <h2 style="margin: 6px 0 10px 0; font-size: 15px;">Site logos</h2>
                <div class="grid grid-2">
                    <div>
                        <label for="header_logo">Header logo</label>
                        @if (is_string($detail->header_logo_path) && $detail->header_logo_path !== '')
                            <div style="margin-bottom:10px;">
                                <img src="{{ asset($detail->header_logo_path) }}" alt="" style="max-width: 260px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb; background:#fff; padding:10px;">
                                <div style="margin-top:6px; color:#64748b; font-size:12px;">
                                    Current: <code style="font-size:11px;">{{ $detail->header_logo_path }}</code>
                                </div>
                            </div>
                        @endif
                        <input id="header_logo" name="header_logo" type="file" accept="image/*,.svg">
                        @error('header_logo') <div class="error">{{ $message }}</div> @enderror
                    </div>

                    <div>
                        <label for="footer_logo">Footer logo</label>
                        @if (is_string($detail->footer_logo_path) && $detail->footer_logo_path !== '')
                            <div style="margin-bottom:10px;">
                                <img src="{{ asset($detail->footer_logo_path) }}" alt="" style="max-width: 260px; width: 100%; height: auto; border-radius: 8px; border:1px solid #e5e7eb; background:#fff; padding:10px;">
                                <div style="margin-top:6px; color:#64748b; font-size:12px;">
                                    Current: <code style="font-size:11px;">{{ $detail->footer_logo_path }}</code>
                                </div>
                            </div>
                        @endif
                        <input id="footer_logo" name="footer_logo" type="file" accept="image/*,.svg">
                        @error('footer_logo') <div class="error">{{ $message }}</div> @enderror
                    </div>
                </div>
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save</button>
        </div>
    </form>
</div>

<style>
    .stack {
        display: grid;
        gap: 8px;
    }
    .row {
        display: grid;
        grid-template-columns: 1fr auto;
        gap: 8px;
        align-items: center;
    }
    .btn-mini {
        padding: 6px 10px;
        font-size: 12px;
    }
    .theme-colors-grid {
        align-items: start;
    }
    .theme-color-field label {
        margin-bottom: 6px;
    }
    .theme-color-row {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }
    .theme-swatch {
        width: 48px;
        height: 40px;
        padding: 0;
        border: 1px solid #cbd5e1;
        border-radius: 8px;
        cursor: pointer;
        flex-shrink: 0;
        background: transparent;
    }
    .theme-swatch::-webkit-color-swatch-wrapper {
        padding: 2px;
    }
    .theme-swatch::-webkit-color-swatch {
        border-radius: 6px;
        border: none;
    }
    .theme-hex {
        flex: 1;
        min-width: 0;
        max-width: 120px;
        font-family: ui-monospace, monospace;
        font-size: 13px;
    }
</style>

<script>
    (() => {
        const syncPairs = [
            ['theme_brand_navy_picker', 'theme_brand_navy'],
            ['theme_brand_navy_mid_picker', 'theme_brand_navy_mid'],
            ['theme_brand_accent_picker', 'theme_brand_accent'],
            ['theme_brand_accent_hover_picker', 'theme_brand_accent_hover'],
            ['theme_brand_topbar_muted_picker', 'theme_brand_topbar_muted'],
            ['theme_footer_overlay_base_picker', 'theme_footer_overlay_base'],
        ];
        const hexOk = (v) => /^#[0-9A-Fa-f]{6}$/.test((v || '').trim());
        syncPairs.forEach(([pid, tid]) => {
            const p = document.getElementById(pid);
            const t = document.getElementById(tid);
            if (!p || !t) return;
            p.addEventListener('input', () => {
                t.value = p.value.toLowerCase();
            });
            t.addEventListener('input', () => {
                const v = t.value.trim();
                if (hexOk(v)) {
                    p.value = v.toLowerCase();
                }
            });
        });
    })();
</script>

<script>
    (() => {
        const emailsWrap = document.getElementById('emailsWrap');
        const phonesWrap = document.getElementById('phonesWrap');
        const addEmailBtn = document.getElementById('addEmailBtn');
        const addPhoneBtn = document.getElementById('addPhoneBtn');

        const rowHtml = (name, placeholder) => {
            const div = document.createElement('div');
            div.className = 'row';
            div.innerHTML = `
                <input name="${name}[]" placeholder="${placeholder}">
                <button type="button" class="btn btn-danger btn-mini js-remove" title="Remove">×</button>
            `;
            return div;
        };

        const onRemove = (e) => {
            const btn = e.target.closest('.js-remove');
            if (!btn) return;
            const row = btn.closest('.row');
            const wrap = btn.closest('#emailsWrap, #phonesWrap');
            if (!row || !wrap) return;

            row.remove();
            if (wrap.children.length < 1) {
                if (wrap.id === 'emailsWrap') wrap.appendChild(rowHtml('emails', 'example@email.com'));
                if (wrap.id === 'phonesWrap') wrap.appendChild(rowHtml('phones', '+8801XXXXXXXXX'));
            }
        };

        addEmailBtn?.addEventListener('click', () => emailsWrap?.appendChild(rowHtml('emails', 'example@email.com')));
        addPhoneBtn?.addEventListener('click', () => phonesWrap?.appendChild(rowHtml('phones', '+8801XXXXXXXXX')));
        document.addEventListener('click', onRemove);
    })();
</script>
@endsection

