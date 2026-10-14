const drawer = document.getElementById('admin-drawer');
const drawerTrigger = document.querySelector('[data-drawer-open]');

if (drawer && drawerTrigger) {
    drawerTrigger.addEventListener('click', () => {
        drawer.showModal();
        drawerTrigger.setAttribute('aria-expanded', 'true');
        document.body.classList.add('overflow-hidden');
    });
    drawer.querySelector('[data-drawer-close]').addEventListener('click', () => drawer.close());
    drawer.addEventListener('click', (event) => {
        const bounds = drawer.getBoundingClientRect();
        if (event.target === drawer && (event.clientX > bounds.right || event.clientX < bounds.left)) drawer.close();
    });
    drawer.addEventListener('close', () => {
        drawerTrigger.setAttribute('aria-expanded', 'false');
        document.body.classList.remove('overflow-hidden');
        drawerTrigger.focus();
    });
    window.matchMedia('(min-width: 64rem)').addEventListener('change', (event) => {
        if (event.matches && drawer.open) drawer.close();
    });
}

const profile = document.querySelector('[data-profile-menu]');
if (profile) {
    const toggle = profile.querySelector('[data-profile-toggle]');
    const menu = document.getElementById(toggle.getAttribute('aria-controls'));
    const close = (restoreFocus = false) => {
        menu.classList.add('hidden');
        toggle.setAttribute('aria-expanded', 'false');
        if (restoreFocus) toggle.focus();
    };
    const open = () => {
        menu.classList.remove('hidden');
        toggle.setAttribute('aria-expanded', 'true');
        menu.querySelector('a').focus();
    };
    toggle.addEventListener('click', () => {
        if (menu.classList.contains('hidden')) open();
        else close(true);
    });
    toggle.addEventListener('keydown', (event) => {
        if (event.key === 'ArrowDown') {
            event.preventDefault();
            open();
        }
    });
    profile.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && !menu.classList.contains('hidden')) {
            event.preventDefault();
            close(true);
        }
    });
    document.addEventListener('click', (event) => {
        if (!profile.contains(event.target)) close();
    });
    profile.addEventListener('focusout', (event) => {
        if (!profile.contains(event.relatedTarget)) close();
    });
}
