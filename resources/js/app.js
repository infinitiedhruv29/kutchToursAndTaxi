const drawer = document.getElementById('admin-drawer');
if (document.querySelector('[data-admin-form], [data-flash-message], [data-admin-datatable]')) {
    import('./admin-forms.js').then(({ initializeAdminForms }) => initializeAdminForms()).catch((error) => {
        console.error('Enhanced forms could not load; normal submissions remain available.', error);
    });
}
if (document.querySelector('[data-admin-datatable]')) {
    import('./datatable.js').then(({ initializeDatatables }) => initializeDatatables()).catch((error) => {
        console.error('Interactive tables could not load; server-rendered controls remain available.', error);
    });
}
if (document.querySelector('[data-route-form]')) {
    import('./routes.js').catch((error) => console.error('Route controls could not load.', error));
}
if (document.querySelector('[data-rich-editor]')) {
    import('./rich-text-editor.js').then(({ initializeEditors }) => initializeEditors()).catch((error) => {
        for (const root of document.querySelectorAll('[data-rich-editor]')) {
            root.querySelector('[data-editor-source]').classList.remove('hidden');
            root.querySelector('[data-editor-enhanced]').classList.add('hidden');
            root.querySelector('[data-editor-fallback]').classList.remove('hidden');
        }
        console.error('Rich-text controls could not load; the escaped textarea remains available.', error);
    });
}
if (document.querySelector('[data-delete-feedback], [data-route-delete-feedback]')) {
    import('./admin-delete.js').then(({ initializeDeletion }) => initializeDeletion()).catch(() => {
        const feedback = document.querySelector('[data-delete-feedback], [data-route-delete-feedback]');
        feedback.textContent = 'Deletion controls could not load. No action was taken. Please reload the page.';
        feedback.classList.remove('hidden');
    });
}
if (document.querySelector('[data-vehicle-form], [data-image-upload]')) {
    import('./vehicles.js').then(({ initializeVehicleForms, initializeImagePreviews }) => {
        initializeVehicleForms();
        initializeImagePreviews();
    }).catch((error) => console.error('Vehicle enhancements could not load; normal form submissions remain available.', error));
}
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
