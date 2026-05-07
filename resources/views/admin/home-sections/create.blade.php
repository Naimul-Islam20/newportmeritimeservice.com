@extends('layouts.admin', ['title' => 'Create Home Section'])

@section('content')
<div class="header">
    <h1>Create home section</h1>
</div>

<style>
    .choice-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 14px;
    }

    .choice-card {
        border: 1px solid #e5e7eb;
        border-radius: 10px;
        padding: 14px;
        background: #fff;
    }

    .choice-card h2 {
        margin: 0 0 8px 0;
        font-size: 15px;
    }

    .choice-card p {
        margin: 0 0 12px 0;
        color: #64748b;
        font-size: 13px;
    }

    .choice-card.disabled {
        opacity: 0.55;
        filter: grayscale(0.15);
        background: #f8fafc;
    }

    .choice-card.disabled * {
        pointer-events: none;
    }

    .choice-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
    }

    .choice-top label {
        display: flex;
        align-items: center;
        gap: 10px;
        font-weight: 600;
    }

    .sub-select {
        margin-top: 10px;
    }

    /* Two mode boxes side-by-side */
    .inner-boxes {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 10px;
        margin-top: 10px;
        align-items: stretch;
    }

    .inner-box {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 8px 10px 10px;
        background: linear-gradient(180deg, #fafbfc 0%, #f4f6f8 100%);
        box-shadow: 0 1px 2px rgba(15, 23, 42, 0.04);
        min-height: 0;
    }

    .inner-box-head {
        display: flex;
        align-items: center;
        gap: 6px;
        margin-bottom: 4px;
        padding-bottom: 6px;
        border-bottom: 1px solid #e2e8f0;
    }

    .inner-box-head label {
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        line-height: 1.2;
        color: #0f172a;
    }

    /* Keep "Details on both sides" on one line (scroll if column is very narrow) */
    .inner-box[data-inner="both_sides_details"] .inner-box-head {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    .inner-box[data-inner="both_sides_details"] .inner-box-head label {
        white-space: nowrap;
    }

    .inner-box-head input[type="radio"] {
        margin: 0;
        flex-shrink: 0;
    }

    .inner-box-body {
        margin-top: 2px;
    }

    .inner-box-body .hint {
        color: #64748b;
        font-size: 10px;
        line-height: 1.35;
        margin: 0 0 6px 0;
    }

    .field-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 2px 8px;
    }

    .field-grid label {
        display: flex;
        gap: 5px;
        align-items: center;
        font-size: 11px;
        line-height: 1.2;
        color: #334155;
        padding: 3px 0;
    }

    .field-grid input[type="checkbox"] {
        margin: 0;
        flex-shrink: 0;
        width: 13px;
        height: 13px;
    }

    .inner-box-body.muted-panel {
        opacity: 0.48;
        pointer-events: none;
        filter: grayscale(0.08);
    }

    .both-cols {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 6px;
        margin-top: 4px;
    }

    .both-cols .sub {
        border: 1px solid #e5e7eb;
        border-radius: 6px;
        padding: 6px 6px 8px;
        background: #fff;
    }

    .both-cols .sub h4 {
        margin: 0 0 4px 0;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: 0.04em;
        text-transform: uppercase;
        color: #475569;
    }

    @media (max-width: 1100px) {
        .choice-grid {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 900px) {
        .inner-boxes {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 520px) {
        .field-grid {
            grid-template-columns: 1fr;
        }

        .both-cols {
            grid-template-columns: 1fr;
        }
    }
</style>

<div class="card">
    <form method="POST" action="{{ route('admin.home-sections.store') }}" id="homeSectionForm">
        @csrf

        <div class="choice-grid">
            <div class="choice-card" data-card="carousel">
                <div class="choice-top">
                    <label>
                        <input type="radio" name="block_type" value="carousel" @checked(old('block_type')==='carousel' )>
                        Add carousel
                    </label>
                </div>
                <p>Choose one carousel style. When selected, Two column layout will be blocked.</p>

                <div class="sub-select">
                    <label for="carousel_variant">Carousel type</label>
                    <select id="carousel_variant" name="carousel_variant">
                        <option value="">— Select —</option>
                        <option value="simple" @selected(old('carousel_variant')==='simple' )>Simple carousel</option>
                        <option value="content_2" @selected(old('carousel_variant')==='content_2' )>2 content carousel</option>
                        <option value="news" @selected(old('carousel_variant')==='news' )>3 news carousel</option>
                    </select>
                    @error('carousel_variant') <div class="error">{{ $message }}</div> @enderror
                </div>
            </div>

            <div class="choice-card" data-card="two_column">
                <div class="choice-top">
                    <label>
                        <input type="radio" name="block_type" value="two_column" @checked(old('block_type')==='two_column' )>
                        Two column layout
                    </label>
                </div>
                <p>Pick how this section should look, then choose which text fields you need. When selected, Add carousel will be blocked.</p>

                <div class="inner-boxes">
                    <div class="inner-box" data-inner="image_details">
                        <div class="inner-box-head">
                            <label>
                                <input type="radio" name="two_column_mode" value="image_details" @checked(old('two_column_mode')==='image_details' )>
                                Image + details
                            </label>
                        </div>
                        <div class="inner-box-body" data-body="image_details">
                            <p class="hint">Image always included. Tick fields for beside-image text.</p>
                            <div class="field-grid">
                                <label><input type="checkbox" name="fields_image[]" value="mini_title" @checked(is_array(old('fields_image')) && in_array('mini_title', old('fields_image', [])))> Mini title</label>
                                <label><input type="checkbox" name="fields_image[]" value="title" @checked(is_array(old('fields_image')) && in_array('title', old('fields_image', [])))> Title</label>
                                <label><input type="checkbox" name="fields_image[]" value="description" @checked(is_array(old('fields_image')) && in_array('description', old('fields_image', [])))> Description</label>
                                <label><input type="checkbox" name="fields_image[]" value="points" @checked(is_array(old('fields_image')) && in_array('points', old('fields_image', [])))> Point</label>
                                <label><input type="checkbox" name="fields_image[]" value="button" @checked(is_array(old('fields_image')) && in_array('button', old('fields_image', [])))> Button</label>
                            </div>
                            @error('fields_image') <div class="error" style="margin-top:8px;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div class="inner-box" data-inner="both_sides_details">
                        <div class="inner-box-head">
                            <label>
                                <input type="radio" name="two_column_mode" value="both_sides_details" @checked(old('two_column_mode')==='both_sides_details' )>
                                Details on both sides
                            </label>
                        </div>
                        <div class="inner-box-body" data-body="both_sides_details">
                            <p class="hint">Two text columns. Tick fields per side (compact).</p>
                            <div class="both-cols">
                                <div class="sub">
                                    <h4>Right side details</h4>
                                    <div class="field-grid">
                                        <label><input type="checkbox" name="fields_right[]" value="mini_title" @checked(is_array(old('fields_right')) && in_array('mini_title', old('fields_right', [])))> Mini title</label>
                                        <label><input type="checkbox" name="fields_right[]" value="title" @checked(is_array(old('fields_right')) && in_array('title', old('fields_right', [])))> Title</label>
                                        <label><input type="checkbox" name="fields_right[]" value="description" @checked(is_array(old('fields_right')) && in_array('description', old('fields_right', [])))> Description</label>
                                        <label><input type="checkbox" name="fields_right[]" value="points" @checked(is_array(old('fields_right')) && in_array('points', old('fields_right', [])))> Point</label>
                                        <label><input type="checkbox" name="fields_right[]" value="button" @checked(is_array(old('fields_right')) && in_array('button', old('fields_right', [])))> Button</label>
                                    </div>
                                </div>
                                <div class="sub">
                                    <h4>Left side details</h4>
                                    <div class="field-grid">
                                        <label><input type="checkbox" name="fields_left[]" value="mini_title" @checked(is_array(old('fields_left')) && in_array('mini_title', old('fields_left', [])))> Mini title</label>
                                        <label><input type="checkbox" name="fields_left[]" value="title" @checked(is_array(old('fields_left')) && in_array('title', old('fields_left', [])))> Title</label>
                                        <label><input type="checkbox" name="fields_left[]" value="description" @checked(is_array(old('fields_left')) && in_array('description', old('fields_left', [])))> Description</label>
                                        <label><input type="checkbox" name="fields_left[]" value="points" @checked(is_array(old('fields_left')) && in_array('points', old('fields_left', [])))> Point</label>
                                        <label><input type="checkbox" name="fields_left[]" value="button" @checked(is_array(old('fields_left')) && in_array('button', old('fields_left', [])))> Button</label>
                                    </div>
                                </div>
                            </div>
                            @error('fields_right') <div class="error" style="margin-top:8px;">{{ $message }}</div> @enderror
                            @error('fields_left') <div class="error" style="margin-top:8px;">{{ $message }}</div> @enderror
                        </div>
                    </div>
                </div>
                @error('two_column_mode') <div class="error" style="margin-top:8px;">{{ $message }}</div> @enderror
            </div>
        </div>

        @error('block_type') <div class="error" style="margin-top:10px;">{{ $message }}</div> @enderror

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save</button>
            <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Back</a>
        </div>
    </form>
</div>

<script>
    (() => {
        const form = document.getElementById('homeSectionForm');
        if (!form) return;

        const cards = Array.from(form.querySelectorAll('[data-card]'));
        const blockRadios = Array.from(form.querySelectorAll('input[name="block_type"]'));

        const carouselSelect = form.querySelector('#carousel_variant');
        const modeRadios = Array.from(form.querySelectorAll('input[name="two_column_mode"]'));

        const imageBody = form.querySelector('[data-body="image_details"]');
        const bothBody = form.querySelector('[data-body="both_sides_details"]');

        const applyBlockState = () => {
            const selected = blockRadios.find(r => r.checked)?.value;
            cards.forEach(card => {
                const cardType = card.getAttribute('data-card');
                const isOther = selected && cardType !== selected;
                card.classList.toggle('disabled', !!isOther);
            });

            if (selected === 'carousel') {
                if (carouselSelect) carouselSelect.value = carouselSelect.value || '';
                modeRadios.forEach(r => {
                    r.checked = false;
                });
                form.querySelectorAll('input[name="fields_image[]"], input[name="fields_right[]"], input[name="fields_left[]"]').forEach(i => {
                    i.checked = false;
                });
            }
            if (selected === 'two_column') {
                if (carouselSelect) carouselSelect.value = '';
            }
            applyInnerModeState();
        };

        const applyInnerModeState = () => {
            const block = blockRadios.find(r => r.checked)?.value;
            const mode = modeRadios.find(r => r.checked)?.value;

            if (block !== 'two_column') {
                if (imageBody) imageBody.classList.remove('muted-panel');
                if (bothBody) bothBody.classList.remove('muted-panel');
                return;
            }

            if (mode === 'image_details') {
                if (imageBody) imageBody.classList.remove('muted-panel');
                if (bothBody) bothBody.classList.add('muted-panel');
                form.querySelectorAll('input[name="fields_right[]"], input[name="fields_left[]"]').forEach(i => {
                    i.checked = false;
                });
            } else if (mode === 'both_sides_details') {
                if (imageBody) imageBody.classList.add('muted-panel');
                if (bothBody) bothBody.classList.remove('muted-panel');
                form.querySelectorAll('input[name="fields_image[]"]').forEach(i => {
                    i.checked = false;
                });
            } else {
                if (imageBody) imageBody.classList.remove('muted-panel');
                if (bothBody) bothBody.classList.remove('muted-panel');
            }
        };

        blockRadios.forEach(r => {
            r.addEventListener('click', (e) => {
                const input = e.currentTarget;
                if (!input) return;
                const wasChecked = input.dataset.wasChecked === '1';
                if (wasChecked) {
                    input.checked = false;
                }
                input.dataset.wasChecked = input.checked ? '1' : '0';
                applyBlockState();
            });
            r.addEventListener('change', () => {
                blockRadios.forEach(x => (x.dataset.wasChecked = x.checked ? '1' : '0'));
                applyBlockState();
            });
        });

        modeRadios.forEach(r => {
            r.addEventListener('change', applyInnerModeState);
        });

        applyBlockState();
    })();
</script>
@endsection