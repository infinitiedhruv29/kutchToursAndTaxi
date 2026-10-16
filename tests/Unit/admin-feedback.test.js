import test from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import { readFile } from 'node:fs/promises';
import * as state from '../../resources/js/admin-form-state.js';

class Element {
    constructor(tag = 'div') {
        this.tagName = tag.toUpperCase();
        this.children = [];
        this.dataset = {};
        this.attributes = new Map();
        this.classes = new Set();
        this.disabled = false;
        this.innerHTML = '';
        this.classList = { add: (...items) => items.forEach((item) => this.classes.add(item)), remove: (...items) => items.forEach((item) => this.classes.delete(item)), contains: (item) => this.classes.has(item) };
    }
    setAttribute(name, value) { this.attributes.set(name, value); }
    getAttribute(name) { return this.attributes.get(name) ?? null; }
    removeAttribute(name) { this.attributes.delete(name); }
    append(...items) { for (const item of items) { item.parentElement = this; this.children.push(item); } }
    prepend(item) { item.parentElement = this; this.children.unshift(item); }
    replaceChildren(...items) { this.children = []; this.append(...items); this.innerHTML = ''; }
    remove() { this.parentElement.children = this.parentElement.children.filter((item) => item !== this); }
    closest() { return this.parentElement; }
    querySelector(selector) { return selector.includes('contenteditable') ? this.editor : this.querySelectorAll(selector)[0] ?? null; }
    querySelectorAll(selector) {
        const all = this.children.flatMap((item) => [item, ...item.querySelectorAll('*')]);
        if (selector === '*') return all;
        if (selector === 'input, select, textarea') return all.filter((item) => ['INPUT', 'SELECT', 'TEXTAREA'].includes(item.tagName));
        if (selector === '[aria-invalid]') return all.filter((item) => item.attributes.has('aria-invalid'));
        if (selector.includes('[data-field-error]')) return all.filter((item) => 'fieldError' in item.dataset || 'validationSummary' in item.dataset);
        if (selector === '[data-flash-message]') return all.filter((item) => 'flashMessage' in item.dataset);
        if (selector.startsWith('button')) return all.filter((item) => item.tagName === 'BUTTON');
        return [];
    }
}

async function formsModule() {
    const context = vm.createContext({ console, WeakSet, WeakMap, Map, Set, URL, document: { createElement: (tag) => new Element(tag) } });
    const source = await readFile(new URL('../../resources/js/admin-forms.js', import.meta.url), 'utf8');
    const module = new vm.SourceTextModule(source, { context });
    await module.link((specifier) => {
        const values = specifier.includes('admin-form-state') ? state : { notify() {}, showFlashMessages() {}, inlineNotice() {} };
        return new vm.SyntheticModule(Object.keys(values), function () { for (const [name, value] of Object.entries(values)) this.setExport(name, value); }, { context });
    });
    await module.evaluate();
    return module.namespace;
}

test('button loading restores content and pre-existing disabled state', async () => {
    const { setFormLoading } = await formsModule();
    const form = new Element('form');
    const save = new Element('button');
    const disabled = new Element('button');
    save.innerHTML = '<span>Save changes</span>';
    disabled.disabled = true;
    form.append(save, disabled);
    const restore = setFormLoading(form, save, 'Saving…');
    assert.equal(save.disabled, true);
    assert.equal(form.getAttribute('aria-busy'), 'true');
    assert.equal(save.children[1].textContent, 'Saving…');
    restore();
    assert.equal(save.innerHTML, '<span>Save changes</span>');
    assert.equal(save.disabled, false);
    assert.equal(disabled.disabled, true);
    assert.equal(form.getAttribute('aria-busy'), null);
    assert.equal(form.children.length, 2);
});

test('validation is form-scoped, preserves values and files, and maps nested errors', async () => {
    const { displayValidation, clearValidation } = await formsModule();
    const form = new Element('form');
    const root = new Element();
    const input = new Element('textarea');
    input.name = 'inclusions';
    input.value = 'Keep my content';
    input.classList.add('border', 'border-slate-200');
    input.setAttribute('aria-describedby', 'inclusions-help');
    const uploadRoot = new Element();
    const upload = new Element('input');
    upload.type = 'file';
    upload.name = 'logo';
    upload.files = ['selected-logo.webp'];
    root.append(input);
    uploadRoot.append(upload);
    form.append(root, uploadRoot);
    const first = displayValidation(form, { 'inclusions.0': ['<script>not HTML</script>'], logo: ['Choose a smaller image.'] });
    assert.equal(first, input);
    assert.equal(root.children[1].textContent, '<script>not HTML</script>');
    assert.equal(root.children[1].innerHTML, '');
    assert.equal(input.value, 'Keep my content');
    assert.deepEqual(upload.files, ['selected-logo.webp']);
    assert.equal(input.getAttribute('aria-invalid'), 'true');
    clearValidation(form);
    assert.equal(input.getAttribute('aria-invalid'), null);
    assert.equal(input.getAttribute('aria-describedby'), 'inclusions-help');
    assert.equal(root.children.length, 1);
    assert.equal(uploadRoot.children.length, 1);
    assert.deepEqual(upload.files, ['selected-logo.webp']);
});

test('validation focuses the visible rich editor instead of the hidden source', async () => {
    const { displayValidation } = await formsModule();
    const form = new Element('form');
    const root = new Element();
    const source = new Element('textarea');
    const editor = new Element();
    source.name = 'description';
    root.editor = editor;
    root.append(source);
    form.append(root);
    assert.equal(displayValidation(form, { description: ['Too long'] }), editor);
    assert.equal(editor.getAttribute('aria-invalid'), 'true');
});

test('flash feedback is delivered once and messages use text rather than HTML', async () => {
    const calls = [];
    const main = new Element('main');
    const flash = new Element();
    flash.dataset.flashMessage = '';
    flash.textContent = '<img src=x onerror=alert(1)>';
    main.append(flash);
    const context = vm.createContext({ document: { querySelector: () => main, body: main }, console });
    const library = new vm.SyntheticModule(['default'], function () {
        this.setExport('default', { mixin: (defaults) => ({ fire: async (options) => { calls.push({ ...defaults, ...options }); return { isConfirmed: false }; } }) });
    }, { context });
    await library.link(() => {});
    await library.evaluate();
    const source = await readFile(new URL('../../resources/js/admin-feedback.js', import.meta.url), 'utf8');
    const module = new vm.SourceTextModule(source, { context, importModuleDynamically: async () => library });
    await module.link(() => new vm.SyntheticModule([], () => {}, { context }));
    await module.evaluate();
    await module.namespace.showFlashMessages(main);
    await module.namespace.showFlashMessages(main);
    assert.equal(calls.length, 1);
    assert.equal(calls[0].text, flash.textContent);
    assert.equal(calls[0].html, undefined);
    assert.equal(flash.classList.contains('hidden'), true);
    assert.equal(calls[0].toast, true);
    assert.equal(calls[0].position, 'top-end');
    assert.equal(calls[0].showConfirmButton, false);
    assert.equal(calls[0].timer, 2500);
    assert.equal(calls[0].timerProgressBar, true);
    assert.equal(calls[0].theme, 'light');
    await module.namespace.notify('error', 'Validation failed');
    assert.equal(calls[1].toast, false);
    assert.equal(calls[1].position, 'center');
    assert.equal(calls[1].showConfirmButton, true);
    assert.equal(calls[1].timer, undefined);
    const confirmation = await module.namespace.confirmAction({ title: 'Delete?', text: 'Confirm deletion', action: () => { throw new Error('Must not delete on cancellation'); } });
    assert.equal(confirmation.confirmed, false);
    assert.equal(calls[2].toast, false);
    assert.equal(calls[2].position, 'center');
    assert.equal(calls[2].focusCancel, true);
    assert.equal(calls[2].timer, undefined);
    context.document.documentElement = { classList: { contains: () => true } };
    await module.namespace.notify('success', 'Saved in dark mode');
    assert.equal(calls[3].theme, 'dark');
});
