@extends('layouts.admin', ['title' => 'Edit port: '.$port->title])

@section('content')
<div class="header">
    <h1>Edit port: {{ $port->title }}</h1>
    <a class="btn btn-muted" href="{{ route('where-we-are.port', [$location->slug, $port->slug]) }}" target="_blank" rel="noopener">View on site</a>
    <a class="btn btn-muted" href="{{ route('admin.where-we-are-locations.edit', $location) }}">Back to {{ $location->hero_title }}</a>
</div>

@if (session('status'))
    <p style="margin:0 0 12px;color:#0d9488;font-size:14px;">{{ session('status') }}</p>
@endif

<div class="card">
    <form method="POST" action="{{ route('admin.where-we-are-ports.update', [$location, $port]) }}">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="title">Port title</label>
                <input id="title" name="title" value="{{ old('title', $port->title) }}" required>
            </div>
            <div>
                <label>Public URL</label>
                <input value="/where-we-are/{{ $location->slug }}/ports/{{ $port->slug }}" readonly disabled>
            </div>
            <div style="grid-column:1/-1;">
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $port->meta_description) }}">
            </div>
        </div>

        <h2 style="margin:24px 0 12px;font-size:15px;">Map</h2>
        <div class="grid grid-2">
            <div style="grid-column:1/-1;">
                <label for="map_query">Google Maps search (used if no embed below)</label>
                <input id="map_query" name="map_query" value="{{ old('map_query', $port->map_query) }}" placeholder="Port of Rotterdam, Netherlands">
            </div>
            <div style="grid-column:1/-1;">
                <label for="map_embed">Google Maps embed (iframe HTML — optional)</label>
                <textarea id="map_embed" name="map_embed" rows="3" placeholder="Paste iframe from Google Maps → Share → Embed">{{ old('map_embed', $port->map_embed) }}</textarea>
            </div>
        </div>

        <label style="margin-top:12px;display:block;">Body paragraphs</label>
        <div data-repeater-wrap="tpl-port-body">
            @php($paragraphs = old('body_paragraphs', $port->body_paragraphs ?? []))
            @if (! is_array($paragraphs) || count($paragraphs) < 1) @php($paragraphs = ['']) @endif
            @foreach ($paragraphs as $i => $para)
                <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
                    <textarea name="body_paragraphs[{{ $i }}]" rows="4" style="flex:1;">{{ is_string($para) ? $para : '' }}</textarea>
                    <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
                </div>
            @endforeach
        </div>
        <button type="button" class="btn btn-muted" data-add-row="tpl-port-body">Add paragraph</button>

        <div class="grid grid-2" style="margin-top:16px;">
            <div>
                <label for="footer_link_label">Footer link label</label>
                <input id="footer_link_label" name="footer_link_label" value="{{ old('footer_link_label', $port->footer_link_label) }}">
            </div>
            <div>
                <label for="footer_link_url">Footer link URL</label>
                <input id="footer_link_url" name="footer_link_url" value="{{ old('footer_link_url', $port->footer_link_url) }}">
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $port->sort_order) }}">
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', $port->is_active))>Active</option>
                    <option value="0" @selected(! old('is_active', $port->is_active))>Hidden</option>
                </select>
            </div>
        </div>

        <div style="margin-top:14px;">
            <button class="btn btn-primary" type="submit">Save port</button>
        </div>
    </form>
</div>

<template id="tpl-port-body">
    <div data-repeater-item style="display:flex;gap:8px;margin-bottom:8px;">
        <textarea name="body_paragraphs[__INDEX__]" rows="4" style="flex:1;"></textarea>
        <button type="button" class="btn btn-muted" data-remove-row>Remove</button>
    </div>
</template>

@include('admin.partials.repeater-rows-script')
@endsection
