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
</style>

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

