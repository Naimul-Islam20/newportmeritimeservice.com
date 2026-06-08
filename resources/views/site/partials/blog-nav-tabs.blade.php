@php
    $currentPath = request()->path();
    $currentPath = $currentPath === '' ? '/' : '/'.ltrim($currentPath, '/');
    $currentPath = rtrim($currentPath, '/') === '' ? '/' : rtrim($currentPath, '/');
@endphp

@if ($blogNavMenu && $blogNavMenu->subMenus->count() > 0)
    <section class="bg-white border-b border-secondary/10">
        <div class="site-container">
            <h2 class="blog-nav-tabs__title">Newport Blog</h2>
            <nav class="blog-nav-tabs" aria-label="Blog categories">
                @foreach ($blogNavMenu->subMenus as $child)
                    @php
                        $path = $child->normalizedPath();
                        $isActive = $path !== null
                            && ($currentPath === $path || str_starts_with($currentPath, $path.'/'));
                    @endphp
                    <a href="{{ $child->siteNavHref() }}"
                        @class([
                            'blog-nav-tabs__item',
                            'blog-nav-tabs__item--active' => $isActive,
                        ])>
                        {{ $child->label }}
                    </a>
                @endforeach
            </nav>
        </div>
    </section>
@endif

