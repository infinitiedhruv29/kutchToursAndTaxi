import test from 'node:test';
import assert from 'node:assert/strict';
import vm from 'node:vm';
import { readFile } from 'node:fs/promises';

function element(properties = {}) {
    const classes = new Set();
    const listeners = {};
    return {
        value: '', listeners, dataset: {},
        classList: { add: (name) => classes.add(name), remove: (name) => classes.delete(name), contains: (name) => classes.has(name) },
        addEventListener(name, listener) { listeners[name] = listener; },
        getAttribute() { return null; },
        ...properties,
    };
}

function editorRoot(name) {
    const source = element({ name, value: '<p><strong>Saved formatting</strong></p>' });
    const enhanced = element();
    const fallback = element();
    enhanced.classList.add('hidden');
    fallback.classList.add('hidden');
    const nodes = {
        '[data-editor-source]': source, '[data-editor-enhanced]': enhanced,
        '[data-editor-fallback]': fallback, '[data-editor-canvas]': element(),
        '[data-editor-link-panel]': element(), '[data-editor-link-input]': element(),
        '[data-editor-link-apply]': element(), '[data-editor-link-remove]': element(), '[data-editor-link-cancel]': element(),
    };
    return { source, enhanced, fallback, querySelector: (selector) => nodes[selector], querySelectorAll: () => [] };
}

async function editorModule({ fail = false } = {}) {
    const instances = [];
    class Editor {
        constructor(options) {
            if (fail) throw new Error('Editor initialization failed');
            this.options = options;
            this.isEmpty = false;
            this.getHTML = () => '<p><em>Changed answer</em></p>';
            instances.push(this);
        }
    }
    const context = vm.createContext({ console: { error() {} }, URL, WeakSet });
    const source = await readFile(new URL('../../resources/js/rich-text-editor.js', import.meta.url), 'utf8');
    const module = new vm.SourceTextModule(source, { context });
    await module.link((specifier) => {
        const values = specifier === '@tiptap/core' ? { Editor } : { default: { configure: (options) => options } };
        return new vm.SyntheticModule(Object.keys(values), function () { for (const [key, value] of Object.entries(values)) this.setExport(key, value); }, { context });
    });
    await module.evaluate();
    return { initialize: module.namespace.initializeEditors, instances };
}

test('FAQ editor initializes independently of routes and only once per root', async () => {
    const module = await editorModule();
    const faq = editorRoot('answer');
    const route = editorRoot('description');
    const scope = { querySelectorAll: () => [faq, route] };
    module.initialize(scope);
    module.initialize(scope);
    assert.equal(module.instances.length, 2);
    assert.equal(module.instances[0].options.content, '<p><strong>Saved formatting</strong></p>');
    assert.equal(module.instances[0].options.injectCSS, false);
    assert.equal(module.instances[0].options.editorProps.attributes['aria-labelledby'], 'answer-label');
    assert.equal(module.instances[1].options.editorProps.attributes['aria-labelledby'], 'description-label');
    assert.equal(faq.source.classList.contains('hidden'), true);
    assert.equal(faq.enhanced.classList.contains('hidden'), false);
});

test('editor updates serialize to the original textarea for AJAX and native submissions', async () => {
    const module = await editorModule();
    const root = editorRoot('answer');
    module.initialize({ querySelectorAll: () => [root] });
    const editor = module.instances[0];
    editor.options.onUpdate({ editor });
    assert.equal(root.source.value, '<p><em>Changed answer</em></p>');
    editor.isEmpty = true;
    editor.options.onUpdate({ editor });
    assert.equal(root.source.value, '');
});

test('initialization failure preserves source contents and reveals textarea fallback', async () => {
    const module = await editorModule({ fail: true });
    const root = editorRoot('answer');
    module.initialize({ querySelectorAll: () => [root] });
    assert.equal(root.source.value, '<p><strong>Saved formatting</strong></p>');
    assert.equal(root.source.classList.contains('hidden'), false);
    assert.equal(root.enhanced.classList.contains('hidden'), true);
    assert.equal(root.fallback.classList.contains('hidden'), false);
});
