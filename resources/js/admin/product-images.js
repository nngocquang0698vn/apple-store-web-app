import jQuery from 'jquery';

const $ = jQuery;

const MAX_FILE_SIZE = 4 * 1024 * 1024;
const ALLOWED_TYPES = ['image/jpeg', 'image/png', 'image/webp'];

function csrfToken() {
    return document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') ?? '';
}

function routeFor(routes, key, imageId) {
    return routes[key].replace('__ID__', String(imageId));
}

function formatFileSize(bytes) {
    if (bytes < 1024) {
        return `${bytes} B`;
    }

    if (bytes < 1024 * 1024) {
        return `${(bytes / 1024).toFixed(1)} KB`;
    }

    return `${(bytes / (1024 * 1024)).toFixed(1)} MB`;
}

function escapeHtml(value) {
    return String(value)
        .replaceAll('&', '&amp;')
        .replaceAll('<', '&lt;')
        .replaceAll('>', '&gt;')
        .replaceAll('"', '&quot;');
}

function validateFile(file) {
    if (!ALLOWED_TYPES.includes(file.type)) {
        return 'Ảnh phải có định dạng JPG, PNG hoặc WebP.';
    }

    if (file.size > MAX_FILE_SIZE) {
        return 'Ảnh không được vượt quá 4MB.';
    }

    return null;
}

function showError($root, message) {
    const $error = $root.find('[data-image-error]');
    const $success = $root.find('[data-image-success]');

    $success.addClass('hidden').text('');

    if (!message) {
        $error.addClass('hidden').text('');
        return;
    }

    $error.removeClass('hidden').text(message);
}

function showSuccess($root, message) {
    const $success = $root.find('[data-image-success]');
    const $error = $root.find('[data-image-error]');

    $error.addClass('hidden').text('');

    if (!message) {
        $success.addClass('hidden').text('');
        return;
    }

    $success.removeClass('hidden').text(message);
}

function highlightImageCard($root, imageId) {
    if (!imageId) {
        return;
    }

    const $card = $root.find(`[data-existing-image][data-image-id="${imageId}"]`);

    if ($card.length === 0) {
        return;
    }

    $card[0].scrollIntoView({ behavior: 'smooth', inline: 'center', block: 'nearest' });
    $card.addClass('ring-2 ring-blue-500 ring-offset-2');

    window.setTimeout(() => {
        $card.removeClass('ring-2 ring-blue-500 ring-offset-2');
    }, 1200);
}

function setUploadLoading($root, isLoading) {
    const $trigger = $root.find('[data-image-upload-trigger]');
    const $submit = $root.find('[data-image-upload-submit]');
    const $label = $root.find('[data-image-upload-submit-label]');

    $trigger.prop('disabled', isLoading);
    $submit.prop('disabled', isLoading);
    $root.find('[data-image-input]').prop('disabled', isLoading);

    if (isLoading) {
        $label.html('<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i> Đang tải ảnh...');
    } else {
        $label.text('Tải ảnh lên');
    }
}

function renderExistingImageCard(image, productName, routes, position, total) {
    const primaryBadge = image.is_primary
        ? '<span class="inline-flex items-center rounded-full bg-blue-100 px-2 py-0.5 text-xs font-medium text-blue-800">Ảnh chính</span>'
        : '';

    const positionBadge = total > 1
        ? `<span class="inline-flex items-center rounded-full bg-gray-900/75 px-2 py-0.5 text-xs font-medium text-white">Vị trí ${position}/${total}</span>`
        : '';

    const primaryButton = image.is_primary
        ? '<p class="text-xs text-blue-700">Ảnh này đang là ảnh chính trên thẻ sản phẩm.</p>'
        : `<button type="button" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-blue-200 bg-blue-50 px-3 py-2 text-xs font-medium text-blue-700 hover:bg-blue-100" data-set-primary-image data-image-id="${image.id}">
                <i class="fa-solid fa-star" aria-hidden="true"></i>
                Đặt làm ảnh chính
           </button>`;

    const isFirst = position <= 1;
    const isLast = position >= total;
    const showOrderControls = total > 1;

    const orderControls = showOrderControls
        ? `<div class="rounded-lg border border-gray-200 bg-gray-50 p-3">
                <p class="text-xs font-medium text-gray-800">Thứ tự trong bộ ảnh</p>
                <p class="mt-1 text-xs text-gray-500">Đổi vị trí khi khách xem ảnh trên trang sản phẩm.</p>
                <div class="mt-2 grid grid-cols-1 gap-2">
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                        data-move-image-up
                        data-image-id="${image.id}"
                        ${isFirst ? 'disabled' : ''}
                        title="${isFirst ? 'Ảnh này đã ở vị trí đầu tiên' : 'Cho ảnh này hiển thị trước ảnh kế bên'}"
                        aria-label="Hiển thị trước ảnh kế bên"
                    >
                        <i class="fa-solid fa-arrow-left" aria-hidden="true"></i>
                        Hiển thị trước
                    </button>
                    <button
                        type="button"
                        class="inline-flex items-center justify-center gap-1.5 rounded-lg border border-gray-300 bg-white px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50 disabled:cursor-not-allowed disabled:opacity-40"
                        data-move-image-down
                        data-image-id="${image.id}"
                        ${isLast ? 'disabled' : ''}
                        title="${isLast ? 'Ảnh này đã ở vị trí cuối cùng' : 'Cho ảnh này hiển thị sau ảnh kế bên'}"
                        aria-label="Hiển thị sau ảnh kế bên"
                    >
                        Hiển thị sau
                        <i class="fa-solid fa-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>
           </div>`
        : '';

    return `
        <article class="w-[240px] shrink-0 snap-start rounded-xl border bg-white p-3 shadow-sm transition-shadow duration-300" data-existing-image data-image-id="${image.id}" data-sort-order="${image.sort_order}" data-is-primary="${image.is_primary ? '1' : '0'}">
            <div class="relative">
                <img src="${escapeHtml(image.url)}" alt="${escapeHtml(image.alt_text || productName)}" class="aspect-square w-full rounded-lg bg-gray-50 object-contain">
                <div class="absolute left-2 top-2 flex flex-col items-start gap-1">${primaryBadge}</div>
                ${positionBadge ? `<div class="absolute bottom-2 right-2">${positionBadge}</div>` : ''}
            </div>
            <div class="mt-3 space-y-3">
                <div>
                    <label class="block text-xs font-medium text-gray-600">Mô tả ảnh (dành cho hỗ trợ đọc màn hình)</label>
                    <input
                        type="text"
                        class="mt-1 w-full rounded-lg border border-gray-300 px-3 py-2 text-sm"
                        value="${escapeHtml(image.alt_text || '')}"
                        data-image-alt-input
                        maxlength="180"
                        placeholder="Ví dụ: iPhone màu xanh, góc nghiêng"
                    >
                    <button type="button" class="mt-2 w-full rounded-lg border border-gray-300 px-3 py-2 text-xs font-medium text-gray-700 hover:bg-gray-50" data-save-image-alt data-image-id="${image.id}">
                        Lưu mô tả ảnh
                    </button>
                </div>
                <div class="space-y-2">
                    ${primaryButton}
                </div>
                ${orderControls}
                <button type="button" class="inline-flex w-full items-center justify-center gap-1.5 rounded-lg border border-red-200 bg-red-50 px-3 py-2 text-xs font-medium text-red-700 hover:bg-red-100" data-delete-image data-image-id="${image.id}">
                    <i class="fa-solid fa-trash" aria-hidden="true"></i>
                    Xóa ảnh này
                </button>
            </div>
        </article>
    `;
}

function renderExistingImages($root, images, movedImageId = null) {
    const $grid = $root.find('[data-existing-images-grid]');
    const $empty = $root.find('[data-image-empty-state]');
    const productName = $root.data('product-name');
    const routes = $root.data('routes');

    if (!images.length) {
        $grid.empty();
        $empty.removeClass('hidden');
        return;
    }

    $empty.addClass('hidden');

    const parts = [];

    images.forEach((image, index) => {
        if (index > 0) {
            parts.push('<div class="flex w-6 shrink-0 items-center justify-center text-gray-300" aria-hidden="true"><i class="fa-solid fa-arrow-right-long"></i></div>');
        }

        parts.push(renderExistingImageCard(
            image,
            productName,
            routes,
            index + 1,
            images.length,
        ));
    });

    $grid.html(parts.join(''));
    highlightImageCard($root, movedImageId);
}

function renderPreviewItem(file, previewId, objectUrl) {
    return `
        <li class="rounded-xl border bg-white p-3 shadow-sm" data-image-preview-item data-preview-id="${previewId}">
            <img src="${objectUrl}" alt="${escapeHtml(file.name)}" class="aspect-square w-full rounded-lg bg-gray-50 object-cover">
            <p class="mt-2 truncate text-xs font-medium text-gray-800" title="${escapeHtml(file.name)}">${escapeHtml(file.name)}</p>
            <p class="text-xs text-gray-500">${formatFileSize(file.size)}</p>
            <button type="button" class="mt-2 inline-flex items-center gap-1 text-xs text-red-600 hover:text-red-800" data-image-remove-preview data-preview-id="${previewId}">
                <i class="fa-solid fa-xmark" aria-hidden="true"></i>
                Bỏ khỏi danh sách
            </button>
        </li>
    `;
}

function syncPreviewSection($root, previewState) {
    const $section = $root.find('[data-image-preview-section]');
    const $list = $root.find('[data-image-preview-list]');

    if (!previewState.length) {
        $section.addClass('hidden');
        $list.empty();
        return;
    }

    $section.removeClass('hidden');
    $list.html(
        previewState.map(({ file, previewId, objectUrl }) => renderPreviewItem(file, previewId, objectUrl)).join(''),
    );
}

function rebuildFileInput($root, previewState) {
    const input = $root.find('[data-image-input]')[0];

    if (!input) {
        return;
    }

    const transfer = new DataTransfer();

    previewState.forEach(({ file }) => {
        transfer.items.add(file);
    });

    input.files = transfer.files;
}

async function ajaxRequest(url, method, body = null) {
    const options = {
        method,
        headers: {
            Accept: 'application/json',
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
        },
    };

    if (body instanceof FormData) {
        options.body = body;
    } else if (body) {
        options.headers['Content-Type'] = 'application/json';
        options.body = JSON.stringify(body);
    }

    const response = await fetch(url, options);
    const payload = await response.json().catch(() => ({}));

    if (!response.ok) {
        const message = payload?.message
            ?? Object.values(payload?.errors ?? {}).flat().join(' ')
            ?? 'Không thể xử lý yêu cầu.';

        throw new Error(message);
    }

    return payload;
}

function initProductImageUpload($root) {
    const routes = $root.data('routes');
    let previewState = [];
    let previewCounter = 0;

    renderExistingImages($root, $root.data('initial-images') ?? []);

    function addFiles(fileList) {
        const errors = [];

        Array.from(fileList).forEach((file) => {
            const validationError = validateFile(file);

            if (validationError) {
                errors.push(`${file.name}: ${validationError}`);
                return;
            }

            const previewId = `preview-${previewCounter += 1}`;
            const objectUrl = URL.createObjectURL(file);

            previewState.push({ file, previewId, objectUrl });
        });

        syncPreviewSection($root, previewState);
        rebuildFileInput($root, previewState);

        if (errors.length) {
            showError($root, errors.join(' '));
        } else {
            showError($root, '');
        }
    }

    function clearPreviews() {
        previewState.forEach(({ objectUrl }) => URL.revokeObjectURL(objectUrl));
        previewState = [];
        syncPreviewSection($root, previewState);
        rebuildFileInput($root, previewState);
        showError($root, '');
    }

    function openFilePicker() {
        $root.find('[data-image-input]').trigger('click');
    }

    async function uploadPreviews() {
        if (!previewState.length) {
            showError($root, 'Vui lòng chọn ít nhất một ảnh.');
            return;
        }

        setUploadLoading($root, true);
        showError($root, '');

        try {
            let lastPayload = null;

            for (const { file } of previewState) {
                const formData = new FormData();
                formData.append('image', file);
                lastPayload = await ajaxRequest(routes.upload, 'POST', formData);
            }

            clearPreviews();
            await refreshImagesFromResponse(lastPayload);
        } catch (error) {
            showError($root, error.message);
        } finally {
            setUploadLoading($root, false);
        }
    }

    async function refreshImagesFromResponse(payload) {
        if (payload?.data?.images) {
            renderExistingImages($root, payload.data.images, payload.data.moved_image_id ?? null);
        }

        if (payload?.message) {
            showSuccess($root, payload.message);
        }
    }

    async function handleImageAction(url, method, body = null) {
        const payload = await ajaxRequest(url, method, body);
        await refreshImagesFromResponse(payload);
        return payload;
    }

    async function handleMove($button, direction) {
        const imageId = $button.data('image-id');

        if ($button.prop('disabled')) {
            return;
        }

        $button.prop('disabled', true);
        showError($root, '');

        try {
            await handleImageAction(routeFor(routes, 'move', imageId), 'PATCH', { direction });
        } catch (error) {
            showError($root, error.message);
        } finally {
            $button.prop('disabled', false);
        }
    }

    $root.on('click', '[data-image-upload-trigger], [data-image-empty-trigger]', function (event) {
        event.preventDefault();
        event.stopPropagation();
        openFilePicker();
    });

    $root.on('click', '[data-image-dropzone]', function (event) {
        if ($(event.target).closest('[data-image-upload-trigger], [data-image-remove-preview], button').length) {
            return;
        }

        openFilePicker();
    });

    $root.on('keydown', '[data-image-dropzone]', function (event) {
        if (event.key === 'Enter' || event.key === ' ') {
            event.preventDefault();
            openFilePicker();
        }
    });

    $root.on('change', '[data-image-input]', function () {
        if (this.files?.length) {
            addFiles(this.files);
        }
    });

    $root.on('dragover', '[data-image-dropzone]', function (event) {
        event.preventDefault();
        $(this).addClass('border-blue-400 bg-blue-50');
    });

    $root.on('dragleave', '[data-image-dropzone]', function (event) {
        event.preventDefault();
        $(this).removeClass('border-blue-400 bg-blue-50');
    });

    $root.on('drop', '[data-image-dropzone]', function (event) {
        event.preventDefault();
        $(this).removeClass('border-blue-400 bg-blue-50');

        const files = event.originalEvent?.dataTransfer?.files;

        if (files?.length) {
            addFiles(files);
        }
    });

    $root.on('click', '[data-image-remove-preview]', function () {
        const previewId = $(this).data('preview-id');
        const item = previewState.find((entry) => entry.previewId === previewId);

        if (item) {
            URL.revokeObjectURL(item.objectUrl);
            previewState = previewState.filter((entry) => entry.previewId !== previewId);
            syncPreviewSection($root, previewState);
            rebuildFileInput($root, previewState);
        }
    });

    $root.on('click', '[data-image-clear-previews]', clearPreviews);

    $root.on('click', '[data-image-upload-submit]', uploadPreviews);

    $root.on('click', '[data-set-primary-image]', async function () {
        const imageId = $(this).data('image-id');
        const $card = $(this).closest('[data-existing-image]');

        $card.addClass('opacity-60');

        try {
            await handleImageAction(routeFor(routes, 'primary', imageId), 'PATCH');
        } catch (error) {
            showError($root, error.message);
        } finally {
            $card.removeClass('opacity-60');
        }
    });

    $root.on('click', '[data-move-image-up]', function (event) {
        event.preventDefault();
        handleMove($(this), 'up');
    });

    $root.on('click', '[data-move-image-down]', function (event) {
        event.preventDefault();
        handleMove($(this), 'down');
    });

    $root.on('click', '[data-delete-image]', async function () {
        const imageId = $(this).data('image-id');

        if (!window.confirm('Bạn có chắc muốn xóa ảnh này không?')) {
            return;
        }

        const $card = $(this).closest('[data-existing-image]');
        $card.addClass('opacity-60');

        try {
            await handleImageAction(routeFor(routes, 'destroy', imageId), 'DELETE');
        } catch (error) {
            showError($root, error.message);
        } finally {
            $card.removeClass('opacity-60');
        }
    });

    $root.on('click', '[data-save-image-alt]', async function () {
        const imageId = $(this).data('image-id');
        const $card = $(this).closest('[data-existing-image]');
        const altText = $card.find('[data-image-alt-input]').val();

        $(this).prop('disabled', true);

        try {
            await handleImageAction(routeFor(routes, 'update', imageId), 'PATCH', {
                alt_text: altText,
            });
        } catch (error) {
            showError($root, error.message);
        } finally {
            $(this).prop('disabled', false);
        }
    });
}

document.addEventListener('DOMContentLoaded', () => {
    $('[data-image-upload]').each(function () {
        initProductImageUpload($(this));
    });
});
