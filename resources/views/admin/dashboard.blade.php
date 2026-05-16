@extends('layouts.admin', ['title' => 'Dashboard'])

@section('content')
<div class="header">
    <h1>Dashboard</h1>
</div>

<div class="grid grid-2">
    <div class="card">
        <h3>Total Users</h3>
        <p>{{ $totalUsers }}</p>
    </div>
    <div class="card">
        <h3>Contact form (unread)</h3>
        <p>{{ $unreadContactMessages }}</p>
    </div>
    <div class="card">
        <h3>Get a quote (unread)</h3>
        <p>{{ $unreadQuoteRequests }}</p>
    </div>
</div>
@endsection
