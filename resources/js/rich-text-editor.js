import { Editor } from '@tiptap/core';
import StarterKit from '@tiptap/starter-kit';

const initializedEditors = new WeakSet();

export function initializeEditors(scope = document) {
    for (const root of scope.querySelectorAll('[data-rich-editor]')) {
        if (initializedEditors.has(root)) continue;
        initializedEditors.add(root);
        const source = root.querySelector('[data-editor-source]');
        const enhanced = root.querySelector('[data-editor-enhanced]');
        const canvas = root.querySelector('[data-editor-canvas]');
        const buttons = [...root.querySelectorAll('[data-editor-command]')];
        const linkPanel = root.querySelector('[data-editor-link-panel]');
        const linkInput = root.querySelector('[data-editor-link-input]');
        let editor;
        try {
            const syncButtons = (instance) => {
                for (const button of buttons) {
                    const command = button.dataset.editorCommand;
                    if (command === 'undo' || command === 'redo') {
                        button.disabled = !instance.can()[command]();
                    } else {
                        const active = command === 'h2' || command === 'h3'
                            ? instance.isActive('heading', { level: Number(command.slice(1)) })
                            : instance.isActive(command);
                        button.setAttribute('aria-pressed', String(active));
                    }
                }
            };
            editor = new Editor({
                element: canvas,
                content: source.value,
                injectCSS: false,
                extensions: [StarterKit.configure({
                    blockquote: false, code: false, codeBlock: false, horizontalRule: false,
                    dropcursor: false, gapcursor: false,
                    strike: false, underline: false,
                    heading: { levels: [2, 3] },
                    link: {
                        openOnClick: false, defaultProtocol: 'https', protocols: ['http', 'https'],
                        HTMLAttributes: { target: null, rel: null },
                    },
                })],
                editorProps: {
                    attributes: {
                        role: 'textbox',
                        'aria-multiline': 'true',
                        'aria-labelledby': source.name + '-label',
                        'aria-describedby': source.name + '-help',
                        'aria-invalid': source.getAttribute('aria-invalid') || 'false',
                        class: 'min-h-64 whitespace-pre-wrap break-words bg-white p-4 text-sm leading-7 text-slate-800 outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-brand-600 dark:bg-slate-900 dark:text-slate-200 [&_p]:my-3 [&_h2]:my-4 [&_h2]:text-xl [&_h2]:font-semibold [&_h3]:my-3 [&_h3]:text-lg [&_h3]:font-semibold [&_ul]:list-disc [&_ul]:pl-6 [&_ol]:list-decimal [&_ol]:pl-6 [&_li]:my-1 [&_a]:text-brand-700 dark:[&_a]:text-brand-200 [&_a]:underline',
                    },
                },
                onUpdate: ({ editor: instance }) => {
                    source.value = instance.isEmpty ? '' : instance.getHTML();
                },
                onTransaction: ({ editor: instance }) => syncButtons(instance),
            });
            for (const button of buttons) {
                button.addEventListener('click', () => {
                    const command = button.dataset.editorCommand;
                    if (command === 'link') {
                        linkInput.value = editor.getAttributes('link').href || '';
                        linkInput.setCustomValidity('');
                        linkPanel.classList.remove('hidden');
                        linkInput.focus();
                        return;
                    }
                    const chain = editor.chain().focus();
                    if (command === 'paragraph') chain.setParagraph().run();
                    else if (command === 'h2' || command === 'h3') chain.toggleHeading({ level: Number(command.slice(1)) }).run();
                    else if (command === 'bold') chain.toggleBold().run();
                    else if (command === 'italic') chain.toggleItalic().run();
                    else if (command === 'bulletList') chain.toggleBulletList().run();
                    else if (command === 'orderedList') chain.toggleOrderedList().run();
                    else chain[command]().run();
                    syncButtons(editor);
                });
            }
            const closeLinkPanel = () => {
                linkPanel.classList.add('hidden');
                linkInput.setCustomValidity('');
                linkInput.value = '';
                editor.commands.focus();
            };
            root.querySelector('[data-editor-link-apply]').addEventListener('click', () => {
                let valid = false;
                try { valid = ['http:', 'https:'].includes(new URL(linkInput.value.trim()).protocol); } catch { /* Invalid URL. */ }
                linkInput.setCustomValidity(valid ? '' : 'Enter a full HTTP or HTTPS link.');
                if (!linkInput.reportValidity()) return;
                editor.chain().focus().extendMarkRange('link').setLink({ href: linkInput.value.trim() }).run();
                closeLinkPanel();
            });
            root.querySelector('[data-editor-link-remove]').addEventListener('click', () => {
                editor.chain().focus().extendMarkRange('link').unsetLink().run();
                closeLinkPanel();
            });
            root.querySelector('[data-editor-link-cancel]').addEventListener('click', closeLinkPanel);
            linkInput.addEventListener('input', () => linkInput.setCustomValidity(''));
            linkPanel.addEventListener('keydown', (event) => {
                if (event.key === 'Escape') {
                    event.preventDefault();
                    closeLinkPanel();
                } else if (event.key === 'Enter' && event.target === linkInput) {
                    event.preventDefault();
                    root.querySelector('[data-editor-link-apply]').click();
                }
            });
            source.classList.add('hidden');
            enhanced.classList.remove('hidden');
            syncButtons(editor);
        } catch (error) {
            editor?.destroy();
            source.classList.remove('hidden');
            enhanced.classList.add('hidden');
            root.querySelector('[data-editor-fallback]').classList.remove('hidden');
            console.error('The rich-text editor could not initialize.', error);
        }
    }
}
