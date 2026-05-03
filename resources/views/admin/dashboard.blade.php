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
        <h3>Quote Requests (New)</h3>
        <p>{{ $newQuoteRequests }}</p>
    </div>
    <div class="card">
        <h3>Free Consultation Requests (New)</h3>
        <p>{{ $newExpertSessions }}</p>
    </div>
    <div class="card">
        <h3>Contact Form (Unread)</h3>
        <p>{{ $unreadContactMessages }}</p>
    </div>
</div>
@endsection