import DataTable from 'datatables.net';
import { notify } from './admin-feedback.js';
import { failureMessage } from './admin-form-state.js';
import { pageSizes, readTableState, writeTableState, visiblePages } from './datatable-state.js';

const instances = new WeakMap();
const controlClasses = 'inline-flex min-h-11 items-center justify-center rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm text-slate-700 hover:bg-slate-50 focus-visible:outline-2 focus-visible:outline-brand-600 disabled:cursor-not-allowed disabled:opacity-50 dark:border-slate-700 dark:bg-slate-900 dark:text-slate-200 dark:hover:bg-slate-800';

export function initializeDatatables(scope = document) {
    const containers = scope.querySelectorAll('[data-admin-datatable]');
    for (const container of containers) {
        if (instances.has(container)) continue;
        try {
            initialize(container, document.querySelectorAll('[data-admin-datatable]').length > 1);
        } catch (error) {
            console.error('The table could not initialize; server-rendered controls remain available.', error);
            container.querySelector('[data-table-status]').textContent = 'Interactive controls could not load. Use the Filter button and page links.';
        }
    }
}

function initialize(container, multiple) {
    const table = container.querySelector('table');
    if (DataTable.isDataTable(table)) return;
    const columns = JSON.parse(container.dataset.columns);
    const defaults = { order: JSON.parse(container.dataset.order), size: Number(container.dataset.pageSize) };
    const prefix = multiple ? table.id + '.' : '';
    const form = container.querySelector('[data-table-form]');
    const search = container.querySelector('[data-table-search]');
    const size = container.querySelector('[data-table-size]');
    const filters = [...container.querySelectorAll('[data-table-filter]')];
    const status = container.querySelector('[data-table-status]');
    const errorBox = container.querySelector('[data-table-error]');
    const summary = container.querySelector('[data-table-summary]');
    const pages = container.querySelector('[data-table-pages]');
    const empty = container.querySelector('[data-table-empty]');
    const fallbackBody = table.tBodies[0].innerHTML;
    const fallbackData = [...table.tBodies[0].rows].map((row) => Object.fromEntries(columns.map((column, index) => [column.data, row.cells[index].innerHTML])));
    let previous = { data: fallbackData, recordsTotal: Number(container.dataset.total), recordsFiltered: Number(container.dataset.total) };
    let api;
    let pending;
    let sequence = 0;
    let timer;
    let failed = false;
    let navigating = false;
    let initialized = false;
    let failureNotified = false;
    const read = () => readTableState(window.location.href, prefix, columns, defaults, filters.map((field) => field.name));
    const applyControls = (state) => {
        search.value = state.q;
        size.value = String(state.size);
        for (const field of filters) {
            field.value = state.filters[field.name];
            if (field.value === '' || field.selectedIndex === -1) field.value = '';
        }
    };
    const currentState = () => ({
        q: search.value, filters: Object.fromEntries(filters.map((field) => [field.name, field.value])),
        order: api.order().map((order) => [...order]), size: api.page.len(), page: api.page.info().page + 1,
    });
    const updateUrl = () => {
        if (navigating || failed) return;
        const url = writeTableState(window.location.href, prefix, currentState(), columns);
        if (url.href !== window.location.href) {
            if (initialized) window.history.pushState({}, '', url);
            else window.history.replaceState({}, '', url);
        }
        initialized = true;
    };
    const button = (label, action, disabled = false, current = false) => {
        const element = document.createElement('button');
        element.type = 'button';
        element.textContent = label;
        element.className = controlClasses;
        element.disabled = disabled;
        if (current) {
            element.setAttribute('aria-current', 'page');
            element.classList.add('bg-brand-50', 'font-semibold', 'text-brand-800', 'dark:bg-brand-500/20', 'dark:text-brand-200');
        }
        element.addEventListener('click', action);
        return element;
    };
    const render = () => {
        const info = api.page.info();
        if (!failed && info.pages > 0 && info.page >= info.pages) {
            api.page(info.pages - 1).draw('page');
            return;
        }
        if (!failed && info.recordsDisplay === 0 && info.page > 0) {
            api.page(0).draw('page');
            return;
        }
        empty.classList.toggle('hidden', failed || info.recordsDisplay !== 0);
        // DataTables creates its own empty row; the shared empty state supplies accessible presentation instead.
        if (api.rows().count() === 0) table.tBodies[0].replaceChildren();
        const message = info.recordsDisplay === 0 ? '0 records' : `${info.start + 1}–${info.end} of ${info.recordsDisplay} records`;
        summary.textContent = message + (info.recordsTotal !== info.recordsDisplay ? ` (${info.recordsTotal} total)` : '');
        status.textContent = failed ? 'Could not refresh. Previously loaded records are shown.' : message;
        const focusLabel = pages.contains(document.activeElement) ? document.activeElement.textContent : null;
        pages.replaceChildren();
        pages.append(button('Previous', () => api.page('previous').draw('page'), info.page === 0 || failed));
        for (const page of visiblePages(info.page, info.pages)) {
            pages.append(button(String(page + 1), () => api.page(page).draw('page'), failed, page === info.page));
        }
        pages.append(button('Next', () => api.page('next').draw('page'), info.page + 1 >= info.pages || failed));
        if (focusLabel) {
            const focusTarget = [...pages.children].find((item) => item.textContent === focusLabel && !item.disabled)
                ?? pages.querySelector('[aria-current="page"]');
            focusTarget?.focus();
        }
        for (const heading of table.tHead.rows[0].cells) heading.removeAttribute('aria-sort');
        for (const sortButton of container.querySelectorAll('[data-table-sort]')) {
            const index = Number(sortButton.dataset.tableSort);
            const selected = Number(api.order()[0][0]) === index;
            const direction = api.order()[0][1];
            if (selected) sortButton.closest('th').setAttribute('aria-sort', direction === 'asc' ? 'ascending' : 'descending');
            sortButton.querySelector('[data-sort-indicator]').textContent = selected ? (direction === 'asc' ? '↑' : '↓') : '↕';
            sortButton.setAttribute('aria-label', `${columns[index].label}: sort ${selected && direction === 'asc' ? 'descending' : 'ascending'}`);
        }
        updateUrl();
        document.dispatchEvent(new CustomEvent('admin:listing-updated'));
    };

    const initial = read();
    applyControls(initial);
    table.tBodies[0].replaceChildren();
    try {
        api = new DataTable(table, {
            serverSide: true, processing: false, autoWidth: false, orderMulti: false,
            layout: { topStart: null, topEnd: null, bottomStart: null, bottomEnd: null },
            order: initial.order, pageLength: initial.size, lengthMenu: pageSizes,
            displayStart: (initial.page - 1) * initial.size,
            columns: columns.map((column) => ({ data: column.data, orderable: !!column.orderable, searchable: false, className: 'px-5 py-5 text-slate-600 dark:text-slate-300' })),
            ajax: async (parameters, callback) => {
                pending?.abort();
                pending = new AbortController();
                const requestSequence = ++sequence;
                const url = new URL(container.dataset.url, window.location.origin);
                const values = {
                    draw: parameters.draw, start: parameters.start, length: parameters.length,
                    'search[value]': search.value, 'search[regex]': 'false',
                    'order[0][column]': parameters.order[0].column, 'order[0][dir]': parameters.order[0].dir,
                    ...Object.fromEntries(filters.map((field) => [field.name, field.value])),
                };
                for (const [key, value] of Object.entries(values)) url.searchParams.set(key, value);
                container.setAttribute('aria-busy', 'true');
                status.textContent = 'Loading records…';
                errorBox.classList.add('hidden');
                try {
                    const response = await fetch(url, { headers: { Accept: 'application/json' }, signal: pending.signal });
                    if (!response.ok) throw Object.assign(new Error(failureMessage(response.status)), { status: response.status });
                    const result = await response.json();
                    if (!Array.isArray(result.data) || !Number.isInteger(result.recordsTotal) || !Number.isInteger(result.recordsFiltered) || result.draw !== parameters.draw) throw new Error('Invalid table response. Please retry.');
                    if (requestSequence !== sequence) return;
                    previous = result;
                    failed = false;
                    failureNotified = false;
                    callback(result);
                } catch (error) {
                    if (requestSequence !== sequence || error.name === 'AbortError') return;
                    failed = true;
                    errorBox.querySelector('[data-table-error-message]').textContent = error.message;
                    errorBox.classList.remove('hidden');
                    callback({ ...previous, draw: parameters.draw });
                    if (!failureNotified) {
                        failureNotified = true;
                        await notify('error', error.status ? failureMessage(error.status) : 'Unable to load records. Previously displayed records have been retained.', { target: container });
                        if (error.status === 401) window.location.assign(container.dataset.loginUrl);
                    }
                } finally {
                    if (requestSequence === sequence) container.setAttribute('aria-busy', 'false');
                }
            },
            drawCallback() {
                if (api) render();
            },
        });
    } catch (error) {
        if (DataTable.isDataTable(table)) new DataTable.Api(table).destroy();
        table.tBodies[0].innerHTML = fallbackBody;
        throw error;
    }
    instances.set(container, api);
    container.dataset.tableReady = 'true';
    for (const element of container.querySelectorAll('[data-table-fallback]')) element.classList.add('hidden');
    for (const element of container.querySelectorAll('[data-table-clear], [data-table-refresh], [data-table-sort], [data-table-pages]')) {
        element.classList.remove('hidden');
        if (element.matches('[data-table-sort], [data-table-pages]')) element.classList.add('inline-flex');
    }
    const reset = () => { clearTimeout(timer); api.page(0).draw(); };
    search.addEventListener('input', () => {
        clearTimeout(timer);
        pending?.abort();
        sequence++;
        timer = setTimeout(reset, 350);
    });
    form.addEventListener('submit', (event) => { event.preventDefault(); reset(); });
    for (const field of filters) field.addEventListener('change', reset);
    size.addEventListener('change', () => { api.page.len(Number(size.value)); reset(); });
    container.querySelector('[data-table-clear]').addEventListener('click', () => {
        search.value = '';
        for (const field of filters) field.value = '';
        reset();
    });
    for (const element of container.querySelectorAll('[data-table-refresh], [data-table-retry]')) element.addEventListener('click', () => { clearTimeout(timer); api.ajax.reload(null, false); });
    for (const element of container.querySelectorAll('[data-table-sort]')) element.addEventListener('click', () => {
        const index = Number(element.dataset.tableSort);
        api.order([[index, Number(api.order()[0][0]) === index && api.order()[0][1] === 'asc' ? 'desc' : 'asc']]);
        reset();
    });
    container.addEventListener('datatable:refresh', () => api.ajax.reload(null, false));
    window.addEventListener('popstate', () => {
        clearTimeout(timer);
        const state = read();
        applyControls(state);
        navigating = true;
        api.page.len(state.size).order(state.order).page(state.page - 1).draw('page');
    });
    api.on('draw', () => { navigating = false; });
}
