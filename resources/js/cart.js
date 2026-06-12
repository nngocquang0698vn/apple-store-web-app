import { formatVnd } from './vnd-money';
import { updateProductPreview } from './product-quantity-preview';
import { showFlash } from './ui-feedback';

const BUTTON_DEBOUNCE_MS = 300;
const INPUT_DEBOUNCE_MS = 500;

let pendingCartRequests = 0;
const debounceTimers = new Map();

function csrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function moneyText(data, key) {
    const formatted = data.formatted?.[key];

    if (formatted) {
        return formatted;
    }

    if (data[key] !== undefined) {
        return formatVnd(data[key]);
    }

    return null;
}

function updateCartBadge(count) {
    $('[data-cart-badge]').text(count);
    $('[data-cart-link]').attr('aria-label', `Giỏ hàng, ${count} sản phẩm`);
}

function setCheckoutButtonsDisabled(disabled) {
    $('[data-cart-checkout-button]').prop('disabled', disabled);
}

function trackPending(jqXHR) {
    pendingCartRequests += 1;
    setCheckoutButtonsDisabled(true);

    jqXHR.always(() => {
        pendingCartRequests = Math.max(0, pendingCartRequests - 1);
        setCheckoutButtonsDisabled(pendingCartRequests > 0);
    });

    return jqXHR;
}

function debounce(key, callback, delay) {
    if (debounceTimers.has(key)) {
        clearTimeout(debounceTimers.get(key));
    }

    debounceTimers.set(
        key,
        setTimeout(() => {
            debounceTimers.delete(key);
            callback();
        }, delay),
    );
}

function flushDebounce(key) {
    if (debounceTimers.has(key)) {
        clearTimeout(debounceTimers.get(key));
        debounceTimers.delete(key);
    }
}

function applyCartSummary(data) {
    if (!data) {
        return;
    }

    if (data.cart_count !== undefined) {
        updateCartBadge(data.cart_count);
        $('[data-cart-header-count]').text(`${data.cart_count} sản phẩm trong giỏ`);
    }

    const subtotalText = moneyText(data, 'cart_subtotal');

    if (subtotalText) {
        $('[data-cart-subtotal]').text(subtotalText);
    }

    const shippingText = moneyText(data, 'shipping_fee');

    if (shippingText) {
        $('[data-cart-shipping]').text(shippingText);
    }

    const grandTotalText = moneyText(data, 'grand_total');

    if (grandTotalText) {
        $('[data-cart-grand-total]').text(grandTotalText);
    }

    if (Array.isArray(data.items)) {
        data.items.forEach((item) => {
            const variantId = item.variant_id;
            const $input = $(`[data-quantity-input="${variantId}"]`);

            if ($input.length && item.quantity !== undefined) {
                $input.val(item.quantity);
                $input.attr('data-last-valid-quantity', item.quantity);
                $input.attr('max', Math.max(1, item.stock_quantity ?? $input.attr('max')));
            }

            const lineSubtotal = item.formatted?.line_subtotal ?? formatVnd(item.line_subtotal);
            $(`[data-line-subtotal="${variantId}"]`).text(lineSubtotal);

            const unitPrice = item.formatted?.unit_price ?? formatVnd(item.unit_price);
            $(`[data-line-unit-price="${variantId}"]`).text(unitPrice);
        });
    }

    if (data.variant_id !== undefined) {
        const variantId = data.variant_id;
        const $input = $(`[data-quantity-input="${variantId}"]`);

        if (data.quantity !== undefined && $input.length) {
            $input.val(data.quantity);
            $input.attr('data-last-valid-quantity', data.quantity);
        }

        if (data.stock_quantity !== undefined && $input.length) {
            $input.attr('max', Math.max(1, data.stock_quantity));
        }

        const lineSubtotal = data.formatted?.line_subtotal ?? formatVnd(data.line_subtotal);
        $(`[data-line-subtotal="${variantId}"]`).text(lineSubtotal);

        const unitPrice = data.formatted?.unit_price ?? formatVnd(data.unit_price);
        $(`[data-line-unit-price="${variantId}"]`).text(unitPrice);
    }
}

function setRowLoading(variantId, isLoading) {
    const $row = $(`[data-cart-item="${variantId}"]`);

    $row.toggleClass('opacity-70', isLoading);
    $row.find('[data-quantity-decrease], [data-quantity-increase], [data-quantity-input]').prop('disabled', isLoading);
    $row.find('[data-row-loading]').toggleClass('hidden', !isLoading);
}

function setFormLoading($form, isLoading) {
    const $submit = $form.find('[type="submit"]');

    $form.toggleClass('opacity-70 pointer-events-none', isLoading);
    $submit.prop('disabled', isLoading);

    if (isLoading) {
        $submit.data('original-html', $submit.html());
        $submit.html('<i class="fa-solid fa-spinner fa-spin" aria-hidden="true"></i>');
    } else if ($submit.data('original-html')) {
        $submit.html($submit.data('original-html'));
    }
}

function showEmptyCartState() {
    $('[data-cart-items-container]').addClass('hidden');
    $('[data-cart-summary-panel]').addClass('hidden');
    $('[data-cart-clear-form]').addClass('hidden');
    $('[data-cart-empty-state]').removeClass('hidden');
}

function handleCartError(xhr, context = {}) {
    const response = xhr.responseJSON ?? {};
    const { $input, variantId } = context;

    if (xhr.status === 422) {
        const errors = response.errors ?? {};
        const firstError = Object.values(errors).flat()[0] ?? response.message ?? 'Dữ liệu không hợp lệ.';

        if ($input?.length) {
            const lastValid = $input.attr('data-last-valid-quantity');

            if (lastValid) {
                $input.val(lastValid);
            }
        }

        showFlash(firstError, 'error', { autoDismiss: false });

        return;
    }

    if (xhr.status === 409 && response.data) {
        applyCartSummary(response.data);
        showFlash(response.message ?? 'Giá hoặc tồn kho đã thay đổi.', 'warning');

        return;
    }

    if (xhr.status === 419) {
        showFlash('Phiên làm việc đã hết hạn. Vui lòng tải lại trang.', 'error');

        return;
    }

    if (xhr.status === 403) {
        showFlash('Bạn không có quyền thực hiện thao tác này.', 'error');

        return;
    }

    if (xhr.status === 404) {
        showFlash('Sản phẩm không còn tồn tại.', 'error');

        if (variantId) {
            $(`[data-cart-item="${variantId}"]`).remove();

            if ($('[data-cart-item]').length === 0) {
                showEmptyCartState();
            }
        }

        return;
    }

    showFlash(response.message ?? 'Có lỗi xảy ra, vui lòng thử lại.', 'error');
}

function sendCartRequest(options) {
    const {
        $form,
        method,
        url,
        data,
        onSuccess,
        silent = false,
        variantId = null,
        $input = null,
    } = options;

    if ($form) {
        setFormLoading($form, true);
    }

    if (variantId) {
        setRowLoading(variantId, true);
    }

    const request = $.ajax({
        url,
        method,
        data,
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
    });

    return trackPending(request)
        .done((response) => {
            if (response?.data) {
                applyCartSummary(response.data);
            }

            if (!silent && response?.message) {
                showFlash(response.message, 'success');
            }

            if (typeof onSuccess === 'function') {
                onSuccess(response);
            }
        })
        .fail((xhr) => {
            handleCartError(xhr, { $input, variantId });
        })
        .always(() => {
            if ($form) {
                setFormLoading($form, false);
            }

            if (variantId) {
                setRowLoading(variantId, false);
            }
        });
}

function normalizeQuantity(value, min = 1, max = 99) {
    const parsed = Number.parseInt(String(value), 10);

    if (!Number.isFinite(parsed)) {
        return null;
    }

    return Math.min(max, Math.max(min, parsed));
}

function queueCartQuantityUpdate($form, variantId, quantity, options = {}) {
    const { immediate = false, silent = true } = options;
    const $input = $form.find('[data-quantity-input]');
    const max = Number($input.attr('max')) || 99;
    const normalized = normalizeQuantity(quantity, 1, max);

    if (normalized === null) {
        return;
    }

    $input.val(normalized);

    const send = () => {
        const current = normalizeQuantity($input.val(), 1, max);

        if (current === null) {
            return;
        }

        const lastValid = Number($input.attr('data-last-valid-quantity'));

        if (current === lastValid) {
            return;
        }

        sendCartRequest({
            method: 'POST',
            url: $form.attr('action'),
            data: {
                _token: csrfToken(),
                _method: 'PATCH',
                quantity: current,
            },
            silent,
            variantId,
            $input,
            onSuccess: () => {
                $input.attr('data-last-valid-quantity', current);
            },
        });
    };

    const key = `cart-qty-${variantId}`;

    if (immediate) {
        flushDebounce(key);
        send();

        return;
    }

    debounce(key, send, options.debounceMs ?? INPUT_DEBOUNCE_MS);
}

function initProductQuantityStepper() {
    const $root = $('[data-product-detail]');

    if ($root.length === 0) {
        return;
    }

    $root.on('click', '[data-quantity-decrease]', function () {
        const $input = $root.find('[data-product-quantity]');
        const current = normalizeQuantity($input.val(), 1, Number($input.attr('max')) || 99) ?? 1;

        if (current <= 1) {
            return;
        }

        $input.val(current - 1);
        updateProductPreview($root);
    });

    $root.on('click', '[data-quantity-increase]', function () {
        const $input = $root.find('[data-product-quantity]');
        const max = Number($input.attr('max')) || 99;
        const current = normalizeQuantity($input.val(), 1, max) ?? 1;

        if (current >= max) {
            return;
        }

        $input.val(current + 1);
        updateProductPreview($root);
    });

    $root.on('input', '[data-product-quantity]', function () {
        updateProductPreview($root);
    });
}

export function initCartHandlers() {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            Accept: 'application/json',
        },
    });

    initProductQuantityStepper();

    $(document).on('submit', '[data-action="add-to-cart"]', function (event) {
        const $form = $(this);

        if ($form.data('ajax') === false) {
            return;
        }

        event.preventDefault();

        sendCartRequest({
            $form,
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
        });
    });

    $(document).on('submit', '[data-action="update-cart-item"]', function (event) {
        const $form = $(this);

        if ($form.data('ajax') === false) {
            return;
        }

        event.preventDefault();

        const variantId = $form.data('variantId');
        const $input = $form.find('[data-quantity-input]');

        queueCartQuantityUpdate($form, variantId, $input.val(), { immediate: true, silent: false });
    });

    $(document).on('click', '[data-quantity-decrease]', function () {
        const $form = $(this).closest('[data-action="update-cart-item"]');

        if ($form.length === 0) {
            return;
        }

        const variantId = $form.data('variantId');
        const $input = $form.find('[data-quantity-input]');
        const current = normalizeQuantity($input.val(), 1, Number($input.attr('max')) || 99) ?? 1;

        if (current <= 1) {
            return;
        }

        queueCartQuantityUpdate($form, variantId, current - 1, { debounceMs: BUTTON_DEBOUNCE_MS });
    });

    $(document).on('click', '[data-quantity-increase]', function () {
        const $form = $(this).closest('[data-action="update-cart-item"]');

        if ($form.length === 0) {
            return;
        }

        const variantId = $form.data('variantId');
        const $input = $form.find('[data-quantity-input]');
        const max = Number($input.attr('max')) || 99;
        const current = normalizeQuantity($input.val(), 1, max) ?? 1;

        if (current >= max) {
            showFlash('Số lượng vượt quá tồn kho hiện có.', 'warning');

            return;
        }

        queueCartQuantityUpdate($form, variantId, current + 1, { debounceMs: BUTTON_DEBOUNCE_MS });
    });

    $(document).on('input', '[data-quantity-input]', function () {
        const $input = $(this);
        const $form = $input.closest('[data-action="update-cart-item"]');

        if ($form.length === 0 || $form.data('ajax') === false) {
            return;
        }

        queueCartQuantityUpdate($form, $form.data('variantId'), $input.val());
    });

    $(document).on('blur', '[data-quantity-input]', function () {
        const $input = $(this);
        const $form = $input.closest('[data-action="update-cart-item"]');

        if ($form.length === 0 || $form.data('ajax') === false) {
            return;
        }

        const max = Number($input.attr('max')) || 99;
        const normalized = normalizeQuantity($input.val(), 1, max);

        if (normalized === null) {
            $input.val($input.attr('data-last-valid-quantity') ?? 1);

            return;
        }

        $input.val(normalized);
        queueCartQuantityUpdate($form, $form.data('variantId'), normalized, { immediate: true });
    });

    $(document).on('submit', '[data-action="remove-cart-item"]', function (event) {
        const $form = $(this);

        event.preventDefault();

        const variantId = $form.data('variantId');

        sendCartRequest({
            $form,
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            silent: true,
            variantId,
            onSuccess: () => {
                $(`[data-cart-item="${variantId}"]`).remove();

                if ($('[data-cart-item]').length === 0) {
                    showEmptyCartState();
                } else {
                    showFlash('Sản phẩm đã được xóa khỏi giỏ hàng.', 'success');
                }
            },
        });
    });

    $(document).on('submit', '[data-action="clear-cart"]', function (event) {
        const $form = $(this);

        event.preventDefault();

        if (!window.confirm('Xóa toàn bộ giỏ hàng?')) {
            return;
        }

        sendCartRequest({
            $form,
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
            silent: true,
            onSuccess: () => {
                showEmptyCartState();
                showFlash('Đã xóa toàn bộ giỏ hàng.', 'success');
            },
        });
    });
}

