import { confirmAction, notify } from './admin-feedback.js';
import { requestJson, reportFailure } from './admin-forms.js';

let deleting = false;
let initialized = false;

export function initializeDeletion() {
    if (initialized) return;
    initialized = true;
    document.addEventListener('click', async (event) => {
        const button = event.target.closest('[data-confirm-delete], [data-delete-route]');
        if (!button || deleting) return;
        deleting = true;
        const table = button.closest('[data-admin-datatable]');
        let succeeded = false;
        try {
            const legacyRoute = button.hasAttribute('data-delete-route');
            const result = await confirmAction({
                title: button.dataset.deleteTitle ?? (legacyRoute ? 'Delete taxi route?' : 'Delete record?'),
                text: `Permanently delete ${button.dataset.title}? ${button.dataset.deleteWarning ?? (legacyRoute ? 'This cannot be undone. Existing inquiries will be preserved.' : 'This cannot be undone.')}`,
                action: () => requestJson(button.dataset.action, {
                    method: 'DELETE', headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                }),
            });
            if (!result.confirmed) return;
            succeeded = true;
            if (table?.dataset.tableReady === 'true') table.dispatchEvent(new CustomEvent('datatable:refresh'));
            else button.closest('tr')?.remove();
            await notify('success', result.value.message);
            if (table?.dataset.tableReady !== 'true') window.location.reload();
        } catch (error) {
            await reportFailure(error, table ?? document.querySelector('main'));
        } finally {
            deleting = false;
            if (succeeded) table?.querySelector('[data-table-refresh]')?.focus();
            else if (button.isConnected) button.focus();
        }
    });
}
