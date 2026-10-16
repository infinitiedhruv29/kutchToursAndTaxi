import test from 'node:test';
import assert from 'node:assert/strict';
import { fieldNames, safeAdminDestination, failureMessage, createSubmissionGate } from '../../resources/js/admin-form-state.js';

test('submission gate prevents duplicates and permits retries after release', () => {
    const gate = createSubmissionGate();
    const first = {};
    const second = {};
    assert.equal(gate.enter(first), true);
    assert.equal(gate.enter(first), false);
    assert.equal(gate.enter(second), true);
    gate.leave(first);
    assert.equal(gate.enter(first), true);
});

test('validation maps nested and list keys to form controls', () => {
    assert.deepEqual(fieldNames('inclusions.2'), ['inclusions.2', 'inclusions[2]', 'inclusions[]', 'inclusions']);
    assert.deepEqual(fieldNames('items.0.title'), ['items.0.title', 'items[0][title]', 'items[]', 'items']);
});

test('only local admin redirects are accepted', () => {
    const origin = 'https://taxi.test';
    assert.equal(safeAdminDestination('/admin/routes?page=2', origin), origin + '/admin/routes?page=2');
    assert.equal(safeAdminDestination(origin + '/admin/profile', origin), origin + '/admin/profile');
    for (const value of ['//taxi.test/admin', 'https://evil.test/admin', 'javascript:alert(1)', '/public', '/administrator', '/admin\\evil', '/admin#fragment', 'https://user@taxi.test/admin', null]) {
        assert.equal(safeAdminDestination(value, origin), null);
    }
});

test('safe status messages do not expose server exceptions and explain session/upload failures', () => {
    assert.match(failureMessage(419), /Reload this page/);
    assert.match(failureMessage(401), /sign in/);
    assert.match(failureMessage(413), /upload is too large/);
    assert.match(failureMessage(429), /wait a minute/);
    assert.match(failureMessage(500), /inputs have been retained/);
});
