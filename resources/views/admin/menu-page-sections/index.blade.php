@extends('layouts.admin', ['title' => $pageTitle])

@section('content')
<div class="header">
    <h1>{{ $pageTitle }}</h1>
    <div style="display:flex; gap:10px; flex-wrap:wrap; align-items:center;">
        <a class="btn btn-primary" href="{{ $createUrl }}">Create section</a>
        @if (! empty($detailsUrl ?? null))
            <a class="btn btn-muted" href="{{ $detailsUrl }}">Details</a>
        @endif
        <a class="btn btn-muted" href="{{ $backUrl }}">Back</a>
    </div>
</div>

<div class="card" style="margin-bottom:12px; color:#64748b; font-size:13px;">
    Page: <strong>{{ $ownerLabel }}</strong>
</div>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Order</th>
                    <th>Title</th>
                    <th>Status</th>
                    <th class="actions-cell">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse (($sections ?? []) as $s)
                    <tr>
                        <td>{{ $s->sort_order }}</td>
                        <td>
                            <div style="font-weight:700;">{{ $s->title ?? 'Section' }}</div>
                            <div style="margin-top:4px; font-size:12px; color:#64748b;">
                                Type: <code style="font-size:11px;">{{ $s->type ?? 'unknown' }}</code>
                            </div>
                            @if ($s->type === 'two_column_image_details')
                                <div style="margin-top:4px; font-size:12px; color:#64748b;">
                                    Layout: <strong>{{ data_get($s->data, 'layout_width') === 'short' ? 'Why Choose Us (short)' : 'About Us (full)' }}</strong>
                                    · Image: <strong>{{ data_get($s->data, 'image_side') === 'right' ? 'right' : 'left' }}</strong>
                                </div>
                            @endif
                        </td>
                        <td>
                            @if ($s->is_active)
                                <span class="status status-unread">Active</span>
                            @else
                                <span class="status status-read">Inactive</span>
                            @endif
                        </td>
                        <td class="actions-cell">
                            <div class="action-group">
                                <a class="btn btn-muted" href="{{ route($editRouteName, [$owner, $s]) }}">Edit</a>
                                <form method="POST" action="{{ route($deleteRouteName, [$owner, $s]) }}" style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this section?')">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="white-space: normal;">
                            No sections yet. Click <strong>Create section</strong> to add one.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
