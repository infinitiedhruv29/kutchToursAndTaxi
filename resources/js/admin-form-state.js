export function fieldNames(key) {
    const pieces = key.split('.');
    const name = pieces.shift();
    return [key, name + pieces.map((piece) => `[${piece}]`).join(''), name + '[]', name];
}

export function safeAdminDestination(value, origin) {
    if (typeof value !== 'string' || !value || /[\u0000-\u0020\u007f\\]/.test(value) || value.startsWith('//')) return null;
    try {
        const url = new URL(value, origin);
        if (url.origin !== origin || url.username || url.password || url.hash || !(url.pathname === '/admin' || url.pathname.startsWith('/admin/'))) return null;
        return url.href;
    } catch { return null; }
}

export function failureMessage(status) {
    if (status === 401) return 'Your session has expired. Please sign in again.';
    if (status === 419) return 'Your session token has expired. Reload this page before trying again. Unsaved changes have not been submitted again.';
    if (status === 413) return 'The upload is too large. Choose a smaller file and try again.';
    if (status === 429) return 'Too many attempts. Please wait a minute before trying again.';
    if (status === 403) return 'You do not have permission to perform this action.';
    if (status === 404) return 'This record is no longer available. Refresh the listing before trying again.';
    return 'Unable to complete the request. Your inputs have been retained. Please try again.';
}

export function createSubmissionGate() {
    const active = new WeakSet();
    return {
        enter(form) { if (active.has(form)) return false; active.add(form); return true; },
        leave(form) { active.delete(form); },
    };
}
