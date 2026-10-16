import test from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import { readFile } from 'node:fs/promises';

function element(properties = {}) {
    const listeners = {};
    const classes = new Set();
    return {
        dataset: {}, value: '', listeners, classList: { toggle(name, active) { active ? classes.add(name) : classes.delete(name); }, contains: (name) => classes.has(name) },
        addEventListener(name, callback) { listeners[name] = callback; },
        ...properties,
    };
}

async function moduleFor(file, contextValues, imports = {}) {
    const context = vm.createContext({ console, ...contextValues });
    const source = await readFile(new URL('../../resources/js/' + file, import.meta.url), 'utf8');
    const module = new vm.SourceTextModule(source, { context });
    await module.link((specifier) => {
        const values = imports[specifier];
        return new vm.SyntheticModule(Object.keys(values), function () { for (const [key, value] of Object.entries(values)) this.setExport(key, value); }, { context });
    });
    await module.evaluate();
    return module.namespace;
}

test('vehicle slug follows the name only until a manual override; edits never auto-change it', async () => {
    const module = await moduleFor('vehicles.js', {});
    const name = element({ value: 'Toyota Innova' });
    const slug = element();
    const automatic = element({ value: '1' });
    const form = element({ dataset: { creating: 'true' }, elements: { name, slug, auto_slug: automatic } });
    module.initializeVehicleForms({ querySelectorAll: () => [form] });
    assert.equal(slug.value, 'toyota-innova');
    slug.value = 'manual';
    slug.listeners.input();
    name.value = 'Changed';
    name.listeners.input();
    assert.equal(slug.value, 'manual');
    slug.value = '';
    slug.listeners.input();
    name.listeners.input();
    assert.equal(slug.value, 'changed');
    const editing = element({ dataset: { creating: 'false' }, elements: { name: element({ value: 'Renamed' }), slug: element({ value: 'existing' }), auto_slug: element({ value: '0' }) } });
    module.initializeVehicleForms({ querySelectorAll: () => [editing] });
    assert.equal(editing.elements.slug.value, 'existing');
    assert.equal(editing.elements.name.listeners.input, undefined);
});

test('image preview replaces saved image, takes precedence over removal and releases object URLs', async () => {
    const revoked = [];
    let urls = 0;
    const module = await moduleFor('vehicles.js', { URL: { createObjectURL: () => 'blob:' + ++urls, revokeObjectURL: (url) => revoked.push(url) }, window: { addEventListener() {} } });
    const input = element({ files: [] });
    const remove = element({ checked: false });
    const image = element({ src: '/storage/vehicles/old.webp', getAttribute() { return this.src; }, removeAttribute() { this.src = null; } });
    const placeholder = element();
    const message = element();
    const nodes = { '[data-image-file]': input, '[data-image-remove]': remove, '[data-image-preview]': image, '[data-image-placeholder]': placeholder, '[data-image-preview-message]': message };
    const root = element({ querySelector: (selector) => nodes[selector] });
    module.initializeImagePreviews({ querySelectorAll: () => [root] });
    assert.equal(image.src, '/storage/vehicles/old.webp');
    remove.checked = true;
    remove.listeners.change();
    assert.equal(image.src, null);
    input.files = [{ type: 'image/webp', size: 100 }];
    input.listeners.change();
    assert.equal(image.src, 'blob:1');
    input.files = [{ type: 'image/png', size: 100 }];
    input.listeners.change();
    assert.deepEqual(revoked, ['blob:1']);
    assert.equal(image.src, 'blob:2');
    input.files = [{ type: 'image/svg+xml', size: 100 }];
    input.listeners.change();
    assert.equal(image.src, null);
    assert.match(message.textContent, /up to 2 MB/);
    assert.deepEqual(revoked, ['blob:1', 'blob:2']);
});

async function deletionHarness({ legacy = false, confirmed = true, conflict = false } = {}) {
    const calls = { confirmed: 0, requests: 0, refreshes: 0, failures: 0, focused: 0 };
    let click;
    const button = {
        dataset: { title: '<script>Vehicle</script>', action: '/admin/vehicles/1', ...(legacy ? {} : { deleteTitle: 'Delete vehicle?', deleteWarning: 'Cannot undo.' }) },
        hasAttribute: () => legacy,
        closest: () => table,
        isConnected: true,
        focus: () => calls.focused++,
    };
    const table = { dataset: { tableReady: 'true' }, dispatchEvent: () => calls.refreshes++, querySelector: () => ({ focus: () => calls.focused++ }) };
    const module = await moduleFor('admin-delete.js', {
        document: { addEventListener: (name, callback) => { click = callback; }, querySelector: () => ({ content: 'csrf-token' }) },
        CustomEvent: class { constructor(name) { this.type = name; } },
        window: { location: { reload() {} } },
    }, {
        './admin-feedback.js': { notify: async () => {}, confirmAction: async (options) => {
            calls.confirmed++;
            calls.options = options;
            if (!confirmed) return { confirmed: false };
            return { confirmed: true, value: await options.action() };
        } },
        './admin-forms.js': { requestJson: async (url, options) => {
            calls.requests++;
            assert.equal(options.headers['X-CSRF-TOKEN'], 'csrf-token');
            if (conflict) throw Object.assign(new Error('Lookup is used by a vehicle.'), { status: 409 });
            return { message: 'Deleted' };
        }, reportFailure: async () => { calls.failures++; } },
    });
    module.initializeDeletion();
    module.initializeDeletion();
    return { calls, click: () => click({ target: { closest: () => button } }) };
}

test('shared deletion supports legacy routes and refreshes only the originating table', async () => {
    for (const legacy of [true, false]) {
        const test = await deletionHarness({ legacy });
        const pending = test.click();
        await test.click();
        await pending;
        assert.equal(test.calls.requests, 1);
        assert.equal(test.calls.confirmed, 1);
        assert.equal(test.calls.refreshes, 1);
        assert.equal(test.calls.focused, 1);
        assert.equal(test.calls.options.title, legacy ? 'Delete taxi route?' : 'Delete vehicle?');
        assert.match(test.calls.options.text, /<script>Vehicle<\/script>/);
    }
});

test('cancellation sends no mutation and a conflict retains the row without refreshing', async () => {
    const cancelled = await deletionHarness({ confirmed: false });
    await cancelled.click();
    assert.equal(cancelled.calls.requests, 0);
    assert.equal(cancelled.calls.refreshes, 0);
    const blocked = await deletionHarness({ conflict: true });
    await blocked.click();
    assert.equal(blocked.calls.failures, 1);
    assert.equal(blocked.calls.refreshes, 0);
});
