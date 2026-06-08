@php
    $parentCategory = isset($preselectedParentId)
        ? \App\Models\SubMenu::query()->find($preselectedParentId)
        : null;
    $isCategoryPost = $parentCategory?->isNavDropdownCategory() ?? false;
@endphp
@extends('layouts.admin', ['title' => $isCategoryPost ? 'Create '.$parentCategory->label : 'Create sub-menu'])

@section('content')
<div class="header">
    <h1>{{ $isCategoryPost ? 'Create '.$parentCategory->label : 'Create sub-menu' }}</h1>
    @if ($isCategoryPost)
        <a class="btn btn-muted" href="{{ route('admin.sub-menus.manage', $parentCategory) }}">Back to {{ $parentCategory->label }}</a>
    @endif
</div>

<div class="card">
    <form method="POST" action="{{ route('admin.sub-menus.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="grid grid-2">
            <div>
                <label for="menu_id">Parent menu</label>
                <select id="menu_id" name="menu_id" required>
                    <option value="">— Select menu —</option>
                    @foreach ($menus as $menu)
                    <option value="{{ $menu->id }}" @selected((string) old('menu_id', $preselectedMenuId ?? '') === (string) $menu->id)>{{ $menu->label }}</option>
                    @endforeach
                </select>
                @error('menu_id') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="label">{{ $isCategoryPost ? 'Title' : 'Sub menu label' }}</label>
                <input id="label" name="label" value="{{ old('label') }}" required>
                @error('label') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="parent_sub_menu_id">Nest under (flyout parent)</label>
                <select id="parent_sub_menu_id" name="parent_sub_menu_id">
                    <option value="">— Top level —</option>
                    @foreach ($parentSubMenus as $parent)
                        <option value="{{ $parent->id }}" data-menu-id="{{ $parent->menu_id }}" @selected((string) old('parent_sub_menu_id', $preselectedParentId ?? '') === (string) $parent->id)>
                            {{ $parent->menu?->label }} → {{ $parent->label }}
                        </option>
                    @endforeach
                </select>
                @error('parent_sub_menu_id') <div class="error">{{ $message }}</div> @enderror
                <div id="parent-hint" style="color:#64748b;font-size:12px;margin-top:6px;">
                    Use for city links under “Where We Are”. For News / Events / Gallery items, choose the matching parent (e.g. <strong>News</strong>).
                </div>
            </div>
            <div>
                <label for="url">{{ $isCategoryPost ? 'URL (optional)' : 'Sub menu URL' }}</label>
                <input id="url" name="url" value="{{ old('url') }}" placeholder="{{ $isCategoryPost ? 'Auto: '.$parentCategory->suggestCategoryPostUrl('your-title') : '/blog/news/my-article-title' }}">
                @error('url') <div class="error">{{ $message }}</div> @enderror
                <div id="url-hint" style="color:#64748b;font-size:12px;margin-top:6px;">
                    @if ($isCategoryPost)
                        Leave blank to auto-create under <code>{{ $parentCategory->categoryBasePath() }}/…</code>. This is <strong>content</strong>, not a navbar submenu.
                    @else
                        Must start with the parent menu path (e.g. <code>/blog/news/…</code> for News).
                    @endif
                </div>
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="3" placeholder="Short line under the page title (hero)">{{ old('description') }}</textarea>
                @error('description') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="page_content">Page content</label>
                <textarea id="page_content" name="page_content" rows="10" placeholder="Main text for this page (below the hero)">{{ old('page_content') }}</textarea>
                @error('page_content') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div style="grid-column: 1 / -1;">
                <label for="cover_image">Cover image</label>
                <input id="cover_image" name="cover_image" type="file" accept="image/jpeg,image/png,image/webp,image/gif">
                @error('cover_image') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Used on Home page sliders (if set).
                </div>
            </div>
            <div>
                <label for="published_at">Date</label>
                <input id="published_at" name="published_at" type="date" value="{{ old('published_at') }}">
                @error('published_at') <div class="error">{{ $message }}</div> @enderror
                <div style="color:#64748b; font-size:12px; margin-top:6px;">
                    Used in News carousel.
                </div>
            </div>
            <div>
                <label for="sort_order">Sort order</label>
                <input id="sort_order" name="sort_order" type="number" min="0" value="{{ old('sort_order', $nextSortOrder ?? 0) }}">
                @error('sort_order') <div class="error">{{ $message }}</div> @enderror
            </div>
            <div>
                <label for="is_active">Status</label>
                <select id="is_active" name="is_active">
                    <option value="1" @selected(old('is_active', 1)==1)>Active</option>
                    <option value="0" @selected(old('is_active', 1)==0)>Inactive</option>
                </select>
                @error('is_active') <div class="error">{{ $message }}</div> @enderror
            </div>
        </div>
        <div style="margin-top: 14px;">
            <button class="btn btn-primary" type="submit">{{ $isCategoryPost ? 'Publish' : 'Save sub-menu' }}</button>
        </div>
    </form>
</div>
@endsection