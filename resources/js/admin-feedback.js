import 'sweetalert2/dist/sweetalert2.min.css';

let library;
let queue = Promise.resolve();

async function dialog() {
    library ??= import('sweetalert2/dist/sweetalert2.esm.js').then(({ default: Swal }) => Swal.mixin({
        position: 'center', toast: false, buttonsStyling: false, returnFocus: false,
        customClass: {
            popup: 'rounded-2xl font-sans',
            confirmButton: 'mx-2 min-h-11 rounded-xl bg-brand-700 px-5 py-2.5 text-sm font-semibold text-white hover:bg-brand-800 focus-visible:outline-2 focus-visible:outline-brand-600 disabled:opacity-50',
            cancelButton: 'mx-2 min-h-11 rounded-xl border border-slate-200 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 disabled:opacity-50 dark:border-slate-700 dark:bg-slate-800 dark:text-slate-200 dark:hover:bg-slate-700',
        },
    }));
    return library;
}

export function inlineNotice(target, message, kind = 'error') {
    let notice = target.querySelector('[data-feedback-fallback]');
    if (!notice) {
        notice = document.createElement('p');
        notice.dataset.feedbackFallback = '';
        notice.className = 'my-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-800 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200';
        target.prepend(notice);
    }
    notice.setAttribute('role', kind === 'error' ? 'alert' : 'status');
    notice.textContent = message;
    return notice;
}

export function notify(kind, message, { title, target = document.querySelector('main') ?? document.body } = {}) {
    const run = async () => {
        let Swal;
        try { Swal = await dialog(); } catch {
            inlineNotice(target, message, kind);
            window.alert(message);
            return;
        }
        const success = kind === 'success';
        await Swal.fire({
            icon: kind,
            theme: document.documentElement?.classList.contains('dark') ? 'dark' : 'light',
            titleText: title ?? ({ success: 'Success', error: 'Unable to complete', warning: 'Please note', info: 'Information' }[kind]),
            text: message,
            toast: success,
            position: success ? 'top-end' : 'center',
            showConfirmButton: !success,
            showCloseButton: success,
            closeButtonAriaLabel: 'Dismiss notification',
            confirmButtonText: 'OK',
            allowOutsideClick: false,
            ...(success ? {
                timer: 2500,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', () => Swal.stopTimer());
                    toast.addEventListener('mouseleave', () => Swal.resumeTimer());
                    toast.addEventListener('focusin', () => Swal.stopTimer());
                    toast.addEventListener('focusout', () => Swal.resumeTimer());
                },
            } : {}),
        });
    };
    const pending = queue.then(run, run);
    queue = pending.catch(() => {});
    return pending;
}

export async function confirmAction({ title, text, action }) {
    await queue;
    let Swal;
    try { Swal = await dialog(); } catch {
        if (!window.confirm(`${title}\n\n${text}`)) return { confirmed: false };
        return { confirmed: true, value: await action() };
    }
    let failure;
    const result = await Swal.fire({
        icon: 'warning', titleText: title, text, showCancelButton: true,
        theme: document.documentElement?.classList.contains('dark') ? 'dark' : 'light',
        confirmButtonText: 'Delete', cancelButtonText: 'Cancel', focusCancel: true,
        showLoaderOnConfirm: true, allowOutsideClick: () => !Swal.isLoading(), allowEscapeKey: () => !Swal.isLoading(),
        preConfirm: async () => {
            Swal.getCancelButton().disabled = true;
            try { return await action(); } catch (error) { failure = error; return null; }
            finally { Swal.getCancelButton().disabled = false; }
        },
    });
    if (failure) throw failure;
    return { confirmed: result.isConfirmed, value: result.value };
}

export async function showFlashMessages(scope = document) {
    for (const element of scope.querySelectorAll('[data-flash-message]')) {
        if (element.dataset.delivered) continue;
        element.dataset.delivered = 'true';
        await notify(element.dataset.kind ?? 'success', element.textContent.trim());
        element.classList.add('hidden');
    }
}
