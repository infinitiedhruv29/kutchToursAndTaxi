import { notify, showFlashMessages, inlineNotice } from './admin-feedback.js';
import { fieldNames, safeAdminDestination, failureMessage, createSubmissionGate } from './admin-form-state.js';

const gate = createSubmissionGate();
const loading = new Map();
let errorSequence = 0;

export function updateReturnLinks() {
    for (const link of document.querySelectorAll('a[data-return-link]')) {
        const destination = new URL(link.href);
        destination.searchParams.set('return_to', window.location.href);
        link.href = destination.href;
    }
}

export function setFormLoading(form, submitter, text) {
    const buttons = [...form.querySelectorAll('button:not([type="button"]):not([type="reset"]), input[type="submit"]')];
    const snapshots = buttons.map((button) => ({ button, disabled: button.disabled }));
    const original = submitter?.innerHTML;
    const originalValue = submitter?.value;
    const originalBusy = form.getAttribute('aria-busy');
    for (const button of buttons) button.disabled = true;
    form.setAttribute('aria-busy', 'true');
    if (submitter?.tagName === 'BUTTON') {
        const spinner = document.createElement('span');
        spinner.className = 'inline-block size-4 shrink-0 animate-spin rounded-full border-2 border-current border-r-transparent motion-reduce:animate-none';
        spinner.setAttribute('aria-hidden', 'true');
        const label = document.createElement('span');
        label.textContent = text;
        submitter.replaceChildren(spinner, label);
    } else if (submitter) submitter.value = text;
    const live = document.createElement('span');
    live.className = 'sr-only';
    live.setAttribute('role', 'status');
    live.textContent = text;
    form.append(live);
    const restore = () => {
        for (const { button, disabled } of snapshots) button.disabled = disabled;
        if (submitter?.tagName === 'BUTTON') submitter.innerHTML = original;
        else if (submitter) submitter.value = originalValue;
        if (originalBusy === null) form.removeAttribute('aria-busy');
        else form.setAttribute('aria-busy', originalBusy);
        live.remove();
        loading.delete(form);
        gate.leave(form);
    };
    loading.set(form, restore);
    return restore;
}

export function clearValidation(form) {
    const ids = new Set([...form.querySelectorAll('[data-field-error]')].map((element) => element.id).filter(Boolean));
    for (const element of form.querySelectorAll('[data-field-error], [data-validation-summary]')) element.remove();
    for (const field of form.querySelectorAll('[aria-invalid]')) {
        field.removeAttribute('aria-invalid');
        field.classList.remove('border-red-400', 'dark:border-red-400');
        if (field.classList.contains('border')) field.classList.add('border-slate-200', 'dark:border-slate-700');
        const remaining = (field.getAttribute('aria-describedby') ?? '').split(/\s+/).filter((id) => id && !ids.has(id));
        if (remaining.length) field.setAttribute('aria-describedby', remaining.join(' '));
        else field.removeAttribute('aria-describedby');
    }
}

export function displayValidation(form, errors) {
    let first;
    const fields = [...form.querySelectorAll('input, select, textarea')];
    const unmatched = [];
    for (const [key, messages] of Object.entries(errors)) {
        const candidates = fieldNames(key);
        const field = candidates.map((name) => fields.find((item) => item.name === name)).find(Boolean);
        const message = Array.isArray(messages) ? messages.filter((item) => typeof item === 'string').join(' ') : '';
        if (!message) continue;
        if (!field) { unmatched.push(message); continue; }
        const root = field.closest('[data-field]') ?? field.parentElement;
        const error = document.createElement('p');
        error.id = 'admin-validation-' + ++errorSequence;
        error.dataset.fieldError = '';
        error.className = 'mt-2 text-sm text-red-700 dark:text-red-300';
        error.textContent = message;
        root.append(error);
        const visibleEditor = root.querySelector('[data-editor-canvas] [contenteditable="true"]');
        for (const control of [field, visibleEditor].filter(Boolean)) {
            control.setAttribute('aria-invalid', 'true');
            control.classList.remove('border-slate-200', 'dark:border-slate-700');
            control.classList.add('border-red-400', 'dark:border-red-400');
            control.setAttribute('aria-describedby', ((control.getAttribute('aria-describedby') ?? '') + ' ' + error.id).trim());
        }
        first ??= visibleEditor ?? (field.type === 'hidden' ? null : field);
    }
    if (unmatched.length) {
        const summary = document.createElement('p');
        summary.dataset.validationSummary = '';
        summary.className = 'my-4 text-sm text-red-700 dark:text-red-300';
        summary.setAttribute('role', 'alert');
        summary.textContent = unmatched.join(' ');
        form.prepend(summary);
    }
    return first;
}

export async function requestJson(url, options = {}) {
    let response;
    try { response = await fetch(url, { ...options, headers: { ...options.headers, Accept: 'application/json', 'X-Requested-With': 'XMLHttpRequest' } }); }
    catch { throw Object.assign(new Error('The connection failed. Your inputs have been retained. Check your connection before trying again.'), { status: 0 }); }
    let data;
    try { data = await response.json(); } catch {
        throw Object.assign(new Error(response.ok ? 'The server returned an unexpected response. Check whether the action completed before submitting again.' : failureMessage(response.status)), { status: response.status });
    }
    if (!response.ok) throw Object.assign(new Error(response.status === 409 && typeof data.message === 'string' ? data.message : failureMessage(response.status)), { status: response.status, errors: response.status === 422 ? data.errors : undefined });
    if (typeof data.message !== 'string') throw new Error('The server returned an unexpected response. Check whether the action completed before submitting again.');
    return data;
}

export async function reportFailure(error, target) {
    await notify('error', error.message, { target });
    if (error.status === 401) window.location.assign(target.dataset.loginUrl ?? '/admin/login');
}

export function initializeAdminForms() {
    document.addEventListener('submit', async (event) => {
        const form = event.target.closest('form[data-admin-form]');
        if (!form || form.method.toUpperCase() === 'GET' || event.defaultPrevented) return;
        event.preventDefault();
        if (!form.reportValidity() || !gate.enter(form)) return;
        clearValidation(form);
        const submitter = event.submitter ?? form.querySelector('button[type="submit"], button:not([type]), input[type="submit"]');
        const data = new FormData(form);
        if (submitter?.name) data.append(submitter.name, submitter.value);
        const restore = setFormLoading(form, submitter, form.dataset.loadingText ?? 'Saving…');
        let succeeded = false;
        try {
            const result = await requestJson(form.action, { method: form.method.toUpperCase(), body: data });
            const destination = safeAdminDestination(result.redirect_url, window.location.origin);
            if (!destination) throw new Error('The server returned an invalid return destination. Check whether the action completed before submitting again.');
            succeeded = true;
            await notify('success', result.message, { target: form });
            window.location.assign(destination);
        } catch (error) {
            if (error.status === 422 && error.errors && typeof error.errors === 'object') {
                const first = displayValidation(form, error.errors);
                await notify('error', 'Please check the highlighted fields and try again.', { title: 'Check your information', target: form });
                first?.focus();
            } else await reportFailure(error, form);
        } finally {
            if (!succeeded) restore();
        }
    });
    window.addEventListener('pageshow', () => { for (const restore of [...loading.values()]) restore(); });
    document.addEventListener('admin:listing-updated', updateReturnLinks);
    updateReturnLinks();
    document.addEventListener('click', (event) => {
        const link = event.target.closest('a[data-return-link]');
        if (!link) return;
        const destination = new URL(link.href);
        destination.searchParams.set('return_to', window.location.href);
        link.href = destination.href;
    });
    showFlashMessages().catch((error) => inlineNotice(document.querySelector('main') ?? document.body, error.message));
}
