export const pageSizes = [15, 25, 50, 100];

export function readTableState(url, prefix, columns, defaults, filterNames) {
    const query = new URL(url).searchParams;
    const defaultColumn = columns[defaults.order[0][0]];
    const sort = query.get(prefix + 'sort');
    const column = columns.findIndex((item) => item.orderable && item.sort === sort);
    const size = Number(query.get(prefix + 'per_page'));
    const page = Number(query.get(prefix + 'page'));
    return {
        q: (query.get(prefix + 'q') ?? '').slice(0, 255),
        filters: Object.fromEntries(filterNames.map((name) => [name, query.get(prefix + name) ?? ''])),
        order: [[column < 0 ? defaults.order[0][0] : column,
            ['asc', 'desc'].includes(query.get(prefix + 'direction')) ? query.get(prefix + 'direction') : defaults.order[0][1]]],
        sort: column < 0 ? defaultColumn.sort : columns[column].sort,
        size: pageSizes.includes(size) ? size : defaults.size,
        page: Number.isInteger(page) && page > 0 && page <= 21474836 ? page : 1,
    };
}

export function writeTableState(url, prefix, state, columns) {
    const next = new URL(url);
    const values = {
        q: state.q, ...state.filters, sort: columns[state.order[0][0]].sort,
        direction: state.order[0][1], page: state.page, per_page: state.size,
    };
    for (const [name, value] of Object.entries(values)) {
        if (value === '') next.searchParams.delete(prefix + name);
        else next.searchParams.set(prefix + name, String(value));
    }
    return next;
}

export function visiblePages(current, count) {
    const start = Math.max(0, Math.min(current - 2, count - 5));
    return Array.from({ length: Math.min(5, count) }, (_, index) => start + index);
}
