@extends('layouts.admin', ['title' => 'Honorable Clients'])

@section('content')
<div class="header">
    <h1>Honorable Clients</h1>
    <a href="{{ route('honorable-clients') }}" class="btn btn-muted" target="_blank" rel="noopener">View public page</a>
</div>

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Page settings</h2>
    @can('update', $page)
    <form method="POST" action="{{ route('admin.honorable-clients.page.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="grid grid-2">
            <div>
                <label for="hero_title">Page title</label>
                <input id="hero_title" name="hero_title" value="{{ old('hero_title', $page->hero_title) }}" required>
                @error('hero_title') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="meta_description">Meta description</label>
                <input id="meta_description" name="meta_description" value="{{ old('meta_description', $page->meta_description) }}">
                @error('meta_description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="page_intro">Intro text</label>
                <textarea id="page_intro" name="page_intro" rows="3">{{ old('page_intro', $page->page_intro) }}</textarea>
                @error('page_intro') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="hero_background_file">Hero background image</label>
                <input id="hero_background_file" name="hero_background_file" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                <p style="margin:6px 0 0;font-size:12px;color:#64748b;">
                    Optional. If empty, the default port/ship banner (same style as Ship Supply pages) is shown.
                </p>
                @error('hero_background_file') <div class="error">{{ $message }}</div> @enderror
                <div style="margin-top:12px;padding:10px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:8px;max-width:420px;">
                    <img src="{{ $page->resolvedHeroBackgroundUrl() }}" alt="" style="display:block;width:100%;max-height:140px;object-fit:cover;border-radius:6px;">
                    <p style="margin:8px 0 0;font-size:12px;color:#64748b;">
                        @if ($page->usesDefaultHeroBackground())
                            Showing <strong>default</strong> hero image.
                        @else
                            Showing your <strong>uploaded</strong> hero image.
                        @endif
                    </p>
                </div>
                @if (! $page->usesDefaultHeroBackground())
                    <label style="display:flex;align-items:center;gap:8px;margin-top:10px;">
                        <input type="checkbox" name="remove_hero_background" value="1"> Remove upload and use default image
                    </label>
                @endif
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:8px;margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', $page->is_active))> Page active on site
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Save page settings</button>
        </div>
    </form>
    @endcan
</div>

<div class="card" style="margin-bottom: 16px;">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Clients</h2>
    <p style="margin:0 0 14px;color:#64748b;font-size:13px;">Upload each company logo. Active clients appear on the public Honorable Clients page.</p>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Logo</th>
                    <th>Company name</th>
                    <th>Sort</th>
                    <th>Status</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($clients as $client)
                <tr>
                    <td>
                        @if ($client->hasLogo())
                            <img src="{{ $client->logoPublicUrl() }}" alt="" style="max-height:44px;max-width:120px;object-fit:contain;">
                        @else
                            <span style="color:#94a3b8;font-size:12px;">No logo</span>
                        @endif
                    </td>
                    <td>{{ $client->name }}</td>
                    <td>{{ $client->sort_order }}</td>
                    <td>{{ $client->is_active ? 'Active' : 'Hidden' }}</td>
                    <td class="actions-cell">
                        @can('update', $client)
                        <a class="btn btn-muted" href="{{ route('admin.honorable-clients.edit', $client) }}">Edit</a>
                        @endcan
                        @can('delete', $client)
                        <form method="POST" action="{{ route('admin.honorable-clients.destroy', $client) }}" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger" type="submit" onclick="return confirm('Delete this client?')">Delete</button>
                        </form>
                        @endcan
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5">No clients yet. Add one below.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

@can('create', \App\Models\HonorableClient::class)
<div class="card">
    <h2 style="margin: 0 0 12px 0; font-size: 16px;">Add client</h2>
    <form method="POST" action="{{ route('admin.honorable-clients.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="name">Company name</label>
                <input id="name" name="name" value="{{ old('name') }}" required placeholder="e.g. YASA SHIPMANAGEMENT & TRADING S.A">
                @error('name') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="logo_file">Logo image</label>
                <input id="logo_file" name="logo_file" type="file" accept="image/*">
                @error('logo_file') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSort) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label style="display:flex;align-items:center;gap:8px;margin-top:28px;">
                    <input type="checkbox" name="is_active" value="1" @checked(old('is_active', true))> Active
                </label>
            </div>
        </div>
        <div style="margin-top:14px;">
            <button type="submit" class="btn btn-primary">Add client</button>
        </div>
    </form>
</div>
@endcan
@endsection
