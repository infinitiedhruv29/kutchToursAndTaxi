import test from 'node:test';
import assert from 'node:assert/strict';
import { readTableState, writeTableState, visiblePages } from '../../resources/js/datatable-state.js';

const columns = [{ orderable: true, sort: 'title' }, { orderable: false }, { orderable: true, sort: 'sort_order' }];
const defaults = { order: [[2, 'asc']], size: 15 };
const read = (url, prefix = '') => readTableState(url, prefix, columns, defaults, ['status']);

test('absent or hostile URL state uses bounded defaults', () => {
    const state = read('https://example.test/admin/routes?sort=drop-table&direction=sideways&page=-2&per_page=-1');
    assert.deepEqual(state.order, [[2, 'asc']]);
    assert.equal(state.page, 1);
    assert.equal(state.size, 15);
    assert.equal(read('https://example.test/?page=21474837').page, 1);
    assert.equal(read('https://example.test/?q=' + 'x'.repeat(300)).q.length, 255);
});

test('URL state round trips search zero, filters, sort, page and page size', () => {
    const url = 'https://example.test/admin/routes?q=0&status=draft&sort=title&direction=desc&page=3&per_page=25&unrelated=keep';
    const state = read(url);
    assert.equal(state.q, '0');
    assert.deepEqual(state.filters, { status: 'draft' });
    assert.deepEqual(state.order, [[0, 'desc']]);
    assert.equal(state.page, 3);
    assert.equal(state.size, 25);
    assert.equal(writeTableState(url, '', state, columns).href, url);
});

test('multiple tables write independent namespaced state', () => {
    const initial = 'https://example.test/admin?first.q=taxi&second.q=invoice';
    const state = read(initial, 'first.');
    state.filters.status = 'published';
    state.page = 2;
    const url = writeTableState(initial, 'first.', state, columns);
    assert.equal(url.searchParams.get('second.q'), 'invoice');
    assert.equal(url.searchParams.get('first.q'), 'taxi');
    assert.equal(read(url.href, 'first.').page, 2);
    assert.equal(read(url.href, 'second.').q, 'invoice');
});

test('clearing filters removes stale URL values', () => {
    const initial = 'https://example.test/?q=taxi&status=published';
    const state = read('https://example.test/');
    const url = writeTableState(initial, '', state, columns);
    assert.equal(url.searchParams.has('q'), false);
    assert.equal(url.searchParams.has('status'), false);
    assert.equal(url.searchParams.get('page'), '1');
});

test('pagination window remains bounded at the beginning and end', () => {
    assert.deepEqual(visiblePages(0, 0), []);
    assert.deepEqual(visiblePages(0, 2), [0, 1]);
    assert.deepEqual(visiblePages(0, 10), [0, 1, 2, 3, 4]);
    assert.deepEqual(visiblePages(9, 10), [5, 6, 7, 8, 9]);
});
