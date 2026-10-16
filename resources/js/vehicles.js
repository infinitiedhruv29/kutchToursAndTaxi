export function initializeVehicleForms(scope = document) {
    for (const form of scope.querySelectorAll('[data-vehicle-form]')) {
        if (form.dataset.vehicleReady) continue;
        form.dataset.vehicleReady = 'true';
        const name = form.elements.name;
        const slug = form.elements.slug;
        const automatic = form.elements.auto_slug;
        if (form.dataset.creating === 'true') {
            const suggest = () => {
                if (automatic.value !== '1') return;
                slug.value = name.value.normalize('NFKD').replace(/[\u0300-\u036f]/g, '').toLowerCase()
                    .replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0, 240).replace(/-$/, '') || 'vehicle';
            };
            name.addEventListener('input', suggest);
            slug.addEventListener('input', () => { automatic.value = slug.value === '' ? '1' : '0'; });
            if (name.value.trim()) suggest();
        }
    }
}

export function initializeImagePreviews(scope = document) {
    for (const root of scope.querySelectorAll('[data-image-upload]')) {
        if (root.dataset.previewReady) continue;
        root.dataset.previewReady = 'true';
        const input = root.querySelector('[data-image-file]');
        const remove = root.querySelector('[data-image-remove]');
        const image = root.querySelector('[data-image-preview]');
        const placeholder = root.querySelector('[data-image-placeholder]');
        const message = root.querySelector('[data-image-preview-message]');
        const saved = image.getAttribute('src');
        let temporary;
        const update = () => {
            if (temporary) URL.revokeObjectURL(temporary);
            temporary = null;
            message.textContent = '';
            const file = input.files?.[0];
            let source = remove?.checked ? null : saved;
            if (file) {
                if (!['image/jpeg', 'image/png', 'image/webp'].includes(file.type) || file.size > 2 * 1024 * 1024) {
                    message.textContent = 'Choose a JPEG, PNG, or WebP image up to 2 MB for a preview.';
                } else {
                    temporary = URL.createObjectURL(file);
                    source = temporary;
                }
            }
            if (source) image.src = source;
            else image.removeAttribute('src');
            image.classList.toggle('hidden', !source);
            placeholder.classList.toggle('hidden', !!source);
        };
        input.addEventListener('change', update);
        remove?.addEventListener('change', update);
        window.addEventListener('pagehide', (event) => { if (!event.persisted && temporary) URL.revokeObjectURL(temporary); });
        update();
    }
}
