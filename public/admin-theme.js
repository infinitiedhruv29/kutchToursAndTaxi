(() => {
    const key = 'admin-theme';
    const root = document.documentElement;
    const system = window.matchMedia('(prefers-color-scheme: dark)');
    let preference;
    try { preference = localStorage.getItem(key); } catch {}
    const apply = () => {
        const dark = preference === 'dark' || (preference !== 'light' && system.matches);
        root.classList.toggle('dark', dark);
        for (const button of document.querySelectorAll('[data-theme-toggle]')) {
            button.setAttribute('aria-pressed', String(dark));
            button.setAttribute('aria-label', dark ? 'Switch to light mode' : 'Switch to dark mode');
            button.title = dark ? 'Switch to light mode' : 'Switch to dark mode';
        }
    };
    apply();
    document.addEventListener('DOMContentLoaded', apply);
    document.addEventListener('click', (event) => {
        if (!event.target.closest('[data-theme-toggle]')) return;
        preference = root.classList.contains('dark') ? 'light' : 'dark';
        try { localStorage.setItem(key, preference); } catch {}
        apply();
    });
    system.addEventListener('change', apply);
    window.addEventListener('storage', (event) => {
        if (event.key !== key && event.key !== null) return;
        preference = event.newValue;
        apply();
    });
    window.addEventListener('pageshow', () => {
        try { preference = localStorage.getItem(key); } catch {}
        apply();
    });
})();
