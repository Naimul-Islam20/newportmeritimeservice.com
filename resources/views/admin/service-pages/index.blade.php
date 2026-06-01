@extends('layouts.admin', ['title' => 'Our Services pages'])

@section('content')
<div class="header">
    <h1>Our Services pages</h1>
    <a class="btn btn-muted" href="{{ route('admin.service-sidebar.edit') }}">Edit shared sidebar</a>
</div>

<div class="card">
    <p style="margin:0 0 16px;color:#64748b;font-size:14px;">Each page uses the same sidebar layout. Content is managed per page below.</p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Page</th>
                    <th>URL</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pages as $page)
                    <tr>
                        <td>{{ $page->title ?: $page->slug }}</td>
                        <td><code>{{ $page->path }}</code></td>
                        <td style="text-align:right;">
                            <a class="btn btn-muted" href="{{ \App\Models\ServicePage::publicUrlForSlug($page->slug) }}" target="_blank" rel="noopener">View</a>
                            <a class="btn btn-primary" href="{{ route('admin.service-pages.edit', $page) }}">Edit</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
