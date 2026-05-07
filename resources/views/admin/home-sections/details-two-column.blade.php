@extends('layouts.admin', ['title' => 'Details on both sides'])

@section('content')
<div class="header">
    <h1>Details on both sides</h1>
    <a class="btn btn-muted" href="{{ route('admin.home-sections.create') }}">Back</a>
</div>

<style>
    .two-col { display:grid; grid-template-columns: 1fr 1fr; gap: 14px; }
    .panel { border:1px solid #e5e7eb; border-radius:10px; background:#fff; padding:14px; }
    .panel h2 { margin:0 0 10px 0; font-size:14px; }
    .muted { color:#64748b; font-size:12px; margin-top:4px; }
    .grid-2 { display:grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 900px) { .two-col { grid-template-columns: 1fr; } }
</style>

<div class="card">
    <div style="margin-bottom: 10px; color:#64748b; font-size:13px;">
        Layout: <strong>Two columns of text</strong> — fill each side below.
    </div>

    <form method="POST" action="{{ route('admin.home-sections.details.store') }}">
        @csrf
        <input type="hidden" name="block_type" value="two_column">
        <input type="hidden" name="two_column_mode" value="both_sides_details">

        <div class="two-col">
            <div class="panel">
                <h2>Right side details</h2>
                <div class="muted">Content for the right column.</div>

                @if (in_array('mini_title', $fieldsRight ?? []))
                    <div style="margin-top:12px;">
                        <label for="right_mini_title">Mini title</label>
                        <input id="right_mini_title" name="right_mini_title" placeholder="Small label above the title">
                    </div>
                @endif

                @if (in_array('title', $fieldsRight ?? []))
                    <div style="margin-top:12px;">
                        <label for="right_title">Title</label>
                        <input id="right_title" name="right_title" placeholder="Main heading">
                    </div>
                @endif

                @if (in_array('description', $fieldsRight ?? []))
                    <div style="margin-top:12px;">
                        <label for="right_description">Description</label>
                        <textarea id="right_description" name="right_description" rows="3" placeholder="Short paragraph..."></textarea>
                    </div>
                @endif

                @if (in_array('points', $fieldsRight ?? []))
                    <div style="margin-top:12px;">
                        <label for="right_points">Bullet points</label>
                        <textarea id="right_points" name="right_points" rows="4" placeholder="One point per line"></textarea>
                    </div>
                @endif

                @if (in_array('button', $fieldsRight ?? []))
                    <div class="grid-2" style="margin-top:12px;">
                        <div>
                            <label for="right_button_text">Button text</label>
                            <input id="right_button_text" name="right_button_text" placeholder="e.g., Learn more">
                        </div>
                        <div>
                            <label for="right_button_url">Button URL</label>
                            <input id="right_button_url" name="right_button_url" placeholder="/contact or https://...">
                        </div>
                    </div>
                @endif
            </div>

            <div class="panel">
                <h2>Left side details</h2>
                <div class="muted">Content for the left column.</div>

                @if (in_array('mini_title', $fieldsLeft ?? []))
                    <div style="margin-top:12px;">
                        <label for="left_mini_title">Mini title</label>
                        <input id="left_mini_title" name="left_mini_title" placeholder="Small label above the title">
                    </div>
                @endif

                @if (in_array('title', $fieldsLeft ?? []))
                    <div style="margin-top:12px;">
                        <label for="left_title">Title</label>
                        <input id="left_title" name="left_title" placeholder="Main heading">
                    </div>
                @endif

                @if (in_array('description', $fieldsLeft ?? []))
                    <div style="margin-top:12px;">
                        <label for="left_description">Description</label>
                        <textarea id="left_description" name="left_description" rows="3" placeholder="Short paragraph..."></textarea>
                    </div>
                @endif

                @if (in_array('points', $fieldsLeft ?? []))
                    <div style="margin-top:12px;">
                        <label for="left_points">Bullet points</label>
                        <textarea id="left_points" name="left_points" rows="4" placeholder="One point per line"></textarea>
                    </div>
                @endif

                @if (in_array('button', $fieldsLeft ?? []))
                    <div class="grid-2" style="margin-top:12px;">
                        <div>
                            <label for="left_button_text">Button text</label>
                            <input id="left_button_text" name="left_button_text" placeholder="e.g., Contact us">
                        </div>
                        <div>
                            <label for="left_button_url">Button URL</label>
                            <input id="left_button_url" name="left_button_url" placeholder="/contact or https://...">
                        </div>
                    </div>
                @endif
            </div>
        </div>

        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">Save details</button>
            <a class="btn btn-muted" href="{{ route('admin.home-sections.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
