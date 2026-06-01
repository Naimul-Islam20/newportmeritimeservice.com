<script>
(function () {
    document.querySelectorAll('[data-add-row]').forEach((btn) => {
        btn.addEventListener('click', () => {
            const targetId = btn.getAttribute('data-add-row');
            const template = document.getElementById(targetId);
            const wrap = document.querySelector('[data-repeater-wrap="' + targetId + '"]');
            if (!template || !wrap) return;
            const index = wrap.querySelectorAll('[data-repeater-item]').length;
            const html = template.innerHTML.replace(/__INDEX__/g, String(index));
            wrap.insertAdjacentHTML('beforeend', html);
        });
    });

    document.addEventListener('click', (e) => {
        const removeBtn = e.target.closest('[data-remove-row]');
        if (!removeBtn) return;
        const item = removeBtn.closest('[data-repeater-item]');
        if (item) item.remove();
    });
})();
</script>
