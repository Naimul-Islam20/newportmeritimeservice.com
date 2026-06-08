@extends('layouts.admin', ['title' => 'Where We Are — locations'])

@section('content')
<div class="header">
    <h1>Where We Are — locations</h1>
    <a class="btn btn-primary" href="{{ route('admin.where-we-are-locations.create') }}">Add location</a>
</div>

<div class="card">
    <p style="margin:0 0 16px;font-size:14px;color:#5a6578;">
        Each location gets a public page at <code>/where-we-are/{slug}</code> and appears in the
        <strong>WHO WE ARE</strong> flyout under <strong>Where We Are</strong> when saved.
    </p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Location</th>
                    <th>Slug</th>
                    <th>Order</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($locations as $location)
                    <tr>
                        <td>{{ $location->hero_title }}</td>
                        <td><code>/where-we-are/{{ $location->slug }}</code></td>
                        <td>{{ $location->sort_order }}</td>
                        <td>{{ $location->is_active ? 'Active' : 'Hidden' }}</td>
                        <td style="text-align:right;white-space:nowrap;">
                            <a class="btn btn-muted" href="{{ url('/where-we-are/'.$location->slug) }}" target="_blank" rel="noopener">View</a>
                            <a class="btn btn-primary" href="{{ route('admin.where-we-are-locations.edit', $location) }}">Edit</a>
                            <form method="POST" action="{{ route('admin.where-we-are-locations.destroy', $location) }}" style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-danger" type="submit" onclick="return confirm('Delete “{{ $location->hero_title }}”? This removes its public page, flyout link, and any ports under it.')">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="color:#64748b;">No locations yet. Run migrations or add one above.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
