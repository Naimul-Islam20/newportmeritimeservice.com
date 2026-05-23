@extends('layouts.admin', ['title' => 'Create Home Section'])

@section('content')
<div class="header">
    <h1>Create home section</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Back to list</a>
</div>

<div class="card">
    <div style="display:flex; flex-wrap:wrap; gap:10px; margin-bottom:14px; padding-bottom:14px; border-bottom:1px solid #e2e8f0;">
        <button type="button" class="btn btn-muted" data-type-btn="image">Image</button>
        <button type="button" class="btn btn-muted" data-type-btn="two_column_image_details">Image and details</button>
        <button type="button" class="btn btn-muted" data-type-btn="two_column_split_cta">Text, image &amp; CTAs</button>
        <button type="button" class="btn btn-muted" data-type-btn="logo_carousel">Certificates / logos</button>
        <button type="button" class="btn btn-muted" data-type-btn="two_column_two_side_details">2 side details</button>
        <button type="button" class="btn btn-muted" data-type-btn="text_input">Text &amp; points</button>
        <button type="button" class="btn btn-muted" data-carousel-kind="carousel_simple">Simple carousel</button>
        <button type="button" class="btn btn-muted" data-carousel-kind="carousel_content">Content carousel</button>
        <button type="button" class="btn btn-muted" data-carousel-kind="carousel_news">News carousel</button>
    </div>

    <form method="POST" action="{{ route('admin.home-sections.store') }}" id="homeCarouselForm" style="display:none;">
        @csrf
        <input type="hidden" name="section_kind" id="homeCarouselKind" value="">
    </form>

    <form method="POST" action="{{ route('admin.home-sections.store') }}" id="sectionCreateForm" enctype="multipart/form-data">
        @csrf
        @if ($errors->any())
            <div class="error" style="margin-bottom:12px;">
                @foreach ($errors->all() as $err)
                    <div>{{ $err }}</div>
                @endforeach
            </div>
        @endif
        <input type="hidden" name="type" id="sectionType" value="{{ old('type', '') }}">
        <div id="typeHint" style="color:#64748b; font-size:13px; padding:10px 12px; border:1px dashed #cbd5e1; border-radius:8px;">
            Select a section type above (carousels open on the next step).
        </div>

        <div id="typeForms" style="display:none; margin-top:12px;">
            @include('admin.partials.section-create-type-panels')
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit" id="saveBtn" disabled>Save section</button>
        </div>
    </form>
</div>

@include('admin.partials.section-create-type-panels-script')

<script>
    (() => {
        const carouselForm = document.getElementById('homeCarouselForm');
        const carouselKindInput = document.getElementById('homeCarouselKind');
        const typeInput = document.getElementById('sectionType');
        const saveBtn = document.getElementById('saveBtn');
        const typeButtons = Array.from(document.querySelectorAll('[data-type-btn]'));

        document.querySelectorAll('[data-carousel-kind]').forEach((btn) => {
            btn.addEventListener('click', () => {
                const kind = btn.getAttribute('data-carousel-kind');
                if (!kind || !carouselForm || !carouselKindInput) return;

                typeButtons.forEach((b) => {
                    b.classList.remove('btn-primary');
                    b.classList.add('btn-muted');
                });
                btn.classList.add('btn-primary');
                btn.classList.remove('btn-muted');

                if (typeInput) typeInput.value = '';
                if (saveBtn) saveBtn.disabled = true;

                carouselKindInput.value = kind;
                carouselForm.submit();
            });
        });
    })();
</script>
@endsection
