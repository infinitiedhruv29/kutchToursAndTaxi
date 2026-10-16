const form = document.querySelector('[data-route-form]');

if (form) {
    const origin = form.elements.origin_name;
    const destination = form.elements.destination_name;
    const title = form.elements.title;
    const slug = form.elements.slug;
    const autoSlug = form.elements.auto_slug;
    const creating = form.dataset.creating === 'true';
    const suggestedTitle = () => origin.value.trim() && destination.value.trim()
        ? origin.value.trim() + ' to ' + destination.value.trim() : '';
    let autoTitle = creating && (!title.value || title.value === suggestedTitle());
    const suggest = () => {
        if (!creating) return;
        const suggestion = suggestedTitle();
        if (autoTitle) title.value = suggestion;
        if (autoSlug.value === '1') {
            slug.value = suggestion.normalize('NFKD').replace(/[\u0300-\u036f]/g, '')
                .toLowerCase().replace(/[^a-z0-9]+/g, '-').replace(/^-|-$/g, '').slice(0, 240).replace(/-$/, '');
        }
    };
    title.addEventListener('input', () => { autoTitle = title.value === ''; });
    slug.addEventListener('input', () => { autoSlug.value = slug.value === '' && creating ? '1' : '0'; });
    origin.addEventListener('input', suggest);
    destination.addEventListener('input', suggest);
    suggest();

    const reverse = form.elements.reverse_enabled;
    const reverseFields = form.querySelector('[data-reverse-fares]');
    const updateReverse = () => {
        reverseFields.classList.toggle('hidden', !reverse.checked);
        reverseFields.disabled = !reverse.checked;
        reverse.setAttribute('aria-expanded', String(reverse.checked));
        for (const trip of ['one_way', 'round_trip']) {
            const override = form.elements['reverse_' + trip + '_fare'].value;
            const forward = form.elements['forward_' + trip + '_fare'].value;
            const amount = override !== '' ? override : forward;
            const formatted = amount !== '' && Number.isFinite(Number(amount))
                ? new Intl.NumberFormat('en-IN', { style: 'currency', currency: 'INR' }).format(Number(amount))
                : 'Price on inquiry';
            form.querySelector('[data-reverse-summary="' + trip + '"]').textContent =
                (override !== '' ? 'Override: ' : 'Inherits forward fare: ') + formatted;
        }
    };
    reverseFields.id = 'reverse-fare-fields';
    reverse.setAttribute('aria-controls', reverseFields.id);
    reverse.addEventListener('change', updateReverse);
    for (const input of form.querySelectorAll('input[name$="_fare"]')) input.addEventListener('input', updateReverse);
    updateReverse();

}

