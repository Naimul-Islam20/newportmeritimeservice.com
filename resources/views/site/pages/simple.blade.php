@extends('site.layouts.app', [
    'title' => $title,
    'metaDescription' => $metaDescription ?? null,
])

@section('content')
    @include('site.partials.menu-page-hero', [
        'heading' => $heading ?? '',
        'lead' => $lead ?? null,
    ])
@endsection
