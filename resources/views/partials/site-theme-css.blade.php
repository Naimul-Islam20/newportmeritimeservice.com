@php($vars = $siteThemeCssVars ?? \App\Models\SiteDetail::themeVariablesForApp())
<style id="site-theme-css-vars">
:root {
@foreach ($vars as $prop => $val)
    {{ $prop }}: {{ $val }};
@endforeach
}
</style>
