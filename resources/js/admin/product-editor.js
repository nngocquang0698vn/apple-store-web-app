import Quill from 'quill';
import 'quill/dist/quill.snow.css';

const BlockEmbed = Quill.import('blots/block/embed');

class VideoEmbedBlot extends BlockEmbed {
    static create(videoId) {
        const node = super.create();
        const iframe = document.createElement('iframe');
        iframe.setAttribute('src', `https://www.youtube.com/embed/${videoId}`);
        iframe.setAttribute('title', 'Video YouTube');
        iframe.setAttribute(
            'allow',
            'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share',
        );
        iframe.setAttribute('allowfullscreen', '');
        node.appendChild(iframe);
        node.setAttribute('data-video-id', videoId);

        return node;
    }

    static value(node) {
        return node.getAttribute('data-video-id');
    }
}

VideoEmbedBlot.blotName = 'videoEmbed';
VideoEmbedBlot.tagName = 'div';
VideoEmbedBlot.className = 'video-embed';

Quill.register(VideoEmbedBlot);

const toolbarOptions = [
    [{ header: [2, 3, false] }],
    ['bold', 'italic'],
    [{ list: 'ordered' }, { list: 'bullet' }],
    ['blockquote', 'link', 'image', 'youtube'],
    ['clean'],
];

function extractYoutubeId(url) {
    const patterns = [
        /youtube\.com\/watch\?[^#]*v=([A-Za-z0-9_-]{11})/i,
        /youtu\.be\/([A-Za-z0-9_-]{11})/i,
        /youtube\.com\/embed\/([A-Za-z0-9_-]{11})/i,
        /youtube\.com\/shorts\/([A-Za-z0-9_-]{11})/i,
    ];

    for (const pattern of patterns) {
        const match = url.match(pattern);

        if (match) {
            return match[1];
        }
    }

    return null;
}

function syncEditorValue(quill, textarea) {
    const html = typeof quill.getSemanticHTML === 'function'
        ? quill.getSemanticHTML().trim()
        : quill.root.innerHTML.trim();

    textarea.value = html === '' || html === '<p><br></p>' ? '' : html;
}

async function uploadDescriptionImage(quill, uploadUrl, csrfToken) {
    const input = document.createElement('input');
    input.setAttribute('type', 'file');
    input.setAttribute('accept', 'image/jpeg,image/png,image/webp');
    input.click();

    input.addEventListener('change', async () => {
        const file = input.files?.[0];

        if (!file) {
            return;
        }

        const formData = new FormData();
        formData.append('image', file);

        const response = await fetch(uploadUrl, {
            method: 'POST',
            credentials: 'same-origin',
            headers: {
                Accept: 'application/json',
                'X-CSRF-TOKEN': csrfToken,
            },
            body: formData,
        });

        if (!response.ok) {
            window.alert('Không thể tải ảnh. Vui lòng thử lại.');

            return;
        }

        const data = await response.json();
        const range = quill.getSelection(true);
        quill.insertEmbed(range?.index ?? quill.getLength(), 'image', data.url, 'user');
        quill.setSelection((range?.index ?? quill.getLength()) + 1);
    });
}

function insertYoutubeVideo(quill) {
    const url = window.prompt('Dán link YouTube (ví dụ: https://www.youtube.com/watch?v=...)');

    if (!url) {
        return;
    }

    const videoId = extractYoutubeId(url.trim());

    if (!videoId) {
        window.alert('Link YouTube không hợp lệ.');

        return;
    }

    const range = quill.getSelection(true);
    const index = range?.index ?? quill.getLength();
    quill.insertEmbed(index, 'videoEmbed', videoId, 'user');
    quill.insertText(index + 1, '\n', 'user');
    quill.setSelection(index + 2);
}

function decorateYoutubeButton(quill) {
    const button = quill.getModule('toolbar')?.container?.querySelector('.ql-youtube');

    if (!button) {
        return;
    }

    button.innerHTML = '<i class="fa-brands fa-youtube" aria-hidden="true"></i>';
    button.setAttribute('aria-label', 'Chèn video YouTube');
    button.setAttribute('title', 'Chèn video YouTube');
}

function initProductDescriptionEditor() {
    const textarea = document.querySelector('[data-rich-text-editor]');

    if (!textarea || textarea.dataset.richTextReady === 'true') {
        return;
    }

    const uploadUrl = textarea.dataset.uploadUrl;
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';

    const wrapper = document.createElement('div');
    wrapper.className = 'rich-text-editor mt-1';

    const editorHost = document.createElement('div');
    editorHost.className = 'min-h-48 rounded-lg border border-gray-300 bg-white text-sm';
    editorHost.setAttribute('aria-label', 'Trình soạn mô tả chi tiết');

    textarea.classList.add('hidden');
    textarea.parentNode.insertBefore(wrapper, textarea);
    wrapper.appendChild(editorHost);
    wrapper.appendChild(textarea);

    const quill = new Quill(editorHost, {
        theme: 'snow',
        modules: {
            toolbar: {
                container: toolbarOptions,
                handlers: {
                    image: () => uploadDescriptionImage(quill, uploadUrl, csrfToken),
                    youtube: () => insertYoutubeVideo(quill),
                },
            },
        },
        placeholder: 'Nhập mô tả chi tiết sản phẩm...',
    });

    decorateYoutubeButton(quill);

    if (textarea.value.trim() !== '') {
        quill.clipboard.dangerouslyPasteHTML(textarea.value);
    }

    const form = textarea.closest('form');

    if (form) {
        form.addEventListener('submit', () => {
            syncEditorValue(quill, textarea);
        });
    }

    textarea.dataset.richTextReady = 'true';
}

document.addEventListener('DOMContentLoaded', initProductDescriptionEditor);
