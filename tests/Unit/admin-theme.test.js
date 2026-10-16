import test from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import { readFile } from 'node:fs/promises';

const source = await readFile(new URL('../../public/admin-theme.js', import.meta.url), 'utf8');

function setup({ stored = null, systemDark = false, blocked = false } = {}) {
    const classes = new Set();
    const documentListeners = {};
    const windowListeners = {};
    const buttons = [];
    let change;
    let value = stored;
    const system = { matches: systemDark, addEventListener: (name, callback) => { change = callback; } };
    const document = {
        documentElement: { classList: { contains: (name) => classes.has(name), toggle: (name, active) => active ? classes.add(name) : classes.delete(name) } },
        querySelectorAll: () => buttons,
        addEventListener: (name, callback) => { documentListeners[name] = callback; },
    };
    const window = { matchMedia: () => system, addEventListener: (name, callback) => { windowListeners[name] = callback; } };
    const localStorage = {
        getItem: () => { if (blocked) throw new Error('Blocked storage'); return value; },
        setItem: (key, next) => { if (blocked) throw new Error('Blocked storage'); value = next; },
    };
    vm.runInNewContext(source, { window, document, localStorage });
    const button = { attributes: {}, setAttribute(name, next) { this.attributes[name] = next; } };
    buttons.push(button);
    documentListeners.DOMContentLoaded();
    return {
        classes, button, value: () => value,
        toggle: () => documentListeners.click({ target: { closest: () => button } }),
        system: (dark) => { system.matches = dark; change(); },
        storage: (next) => { value = next; windowListeners.storage({ key: 'admin-theme', newValue: next }); },
        restore: (next) => { value = next; windowListeners.pageshow(); },
    };
}

test('system preference is applied immediately and updates until an explicit choice', () => {
    const theme = setup({ systemDark: true });
    assert.equal(theme.classes.has('dark'), true);
    assert.equal(theme.button.attributes['aria-pressed'], 'true');
    assert.equal(theme.button.attributes['aria-label'], 'Switch to light mode');
    theme.system(false);
    assert.equal(theme.classes.has('dark'), false);
});

test('toggle persists explicit choice and overrides the system preference', () => {
    const theme = setup({ stored: 'light', systemDark: true });
    assert.equal(theme.classes.has('dark'), false);
    theme.toggle();
    assert.equal(theme.value(), 'dark');
    assert.equal(theme.classes.has('dark'), true);
    theme.system(false);
    assert.equal(theme.classes.has('dark'), true);
    theme.toggle();
    assert.equal(theme.value(), 'light');
    assert.equal(theme.button.attributes['aria-label'], 'Switch to dark mode');
});

test('blocked storage does not break the theme toggle', () => {
    const theme = setup({ blocked: true });
    theme.toggle();
    assert.equal(theme.classes.has('dark'), true);
});

test('cross-tab changes and browser history restores use the latest preference', () => {
    const theme = setup();
    theme.storage('dark');
    assert.equal(theme.classes.has('dark'), true);
    theme.restore('light');
    assert.equal(theme.classes.has('dark'), false);
    theme.storage(null);
    theme.system(true);
    assert.equal(theme.classes.has('dark'), true);
});

test('an invalid stored preference falls back to the system', () => {
    assert.equal(setup({ stored: 'invalid', systemDark: true }).classes.has('dark'), true);
});
