@php
    $rawOld = old('show_submenus_on_page', $defaultShow ?? false);
    $showSubmenusOn = $rawOld === true || $rawOld === 1 || $rawOld === '1';
@endphp
<div style="grid-column: 1 / -1; padding-bottom: 14px; margin-bottom: 8px; border-bottom: 1px solid #e5e7eb;">
    <div style="min-width: 220px;">
        <div style="font-weight:700; margin-bottom:8px;">Show submenu pages</div>
        <button type="button" id="menuShowSubmenusToggle" aria-label="Show submenu pages on this menu"
            style="width:72px; height:34px; border-radius:999px; border:1px solid {{ $showSubmenusOn ? '#0b4a7a' : '#cbd5e1' }}; background:{{ $showSubmenusOn ? '#0b4a7a' : '#f1f5f9' }}; padding:3px; position:relative; cursor:pointer;">
            <span id="menuShowSubmenusKnob"
                style="display:block; width:28px; height:28px; border-radius:999px; background:#0ea5e9; transform:{{ $showSubmenusOn ? 'translateX(38px)' : 'translateX(0)' }}; transition:transform .18s ease;"></span>
        </button>
        <div id="menuShowSubmenusHint" style="color:#64748b; font-size:12px; margin-top:6px;">{{ $showSubmenusOn ? 'ON → submenu pages on this menu page' : 'OFF → submenu pages not shown here' }}</div>
        <input type="hidden" name="show_submenus_on_page" id="menu_show_submenus_on_page" value="{{ $showSubmenusOn ? '1' : '0' }}">
    </div>
</div>
@error('show_submenus_on_page')
    <div class="error" style="grid-column: 1 / -1;">{{ $message }}</div>
@enderror

<script>
    (() => {
        const toggle = document.getElementById('menuShowSubmenusToggle');
        const knob = document.getElementById('menuShowSubmenusKnob');
        const input = document.getElementById('menu_show_submenus_on_page');
        const hint = document.getElementById('menuShowSubmenusHint');
        if (!toggle || !knob || !input) return;

        const setUi = (isOn) => {
            input.value = isOn ? '1' : '0';
            toggle.setAttribute('aria-pressed', isOn ? 'true' : 'false');
            toggle.style.background = isOn ? '#0b4a7a' : '#f1f5f9';
            toggle.style.borderColor = isOn ? '#0b4a7a' : '#cbd5e1';
            knob.style.transform = isOn ? 'translateX(38px)' : 'translateX(0)';
            if (hint) {
                hint.textContent = isOn
                    ? 'ON → submenu pages on this menu page'
                    : 'OFF → submenu pages not shown here';
            }
        };

        setUi(input.value === '1');
        toggle.addEventListener('click', () => {
            setUi(input.value !== '1');
        });
    })();
</script>
