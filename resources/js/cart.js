import { formatVnd } from './vnd-money';
import { showFlash } from './ui-feedback';

function csrfToken() {
    return $('meta[name="csrf-token"]').attr('content');
}

function updateCartBadge(count) {
    $('[data-cart-badge]').text(count);
    $('[data-cart-link]').attr('aria-label', `Giỏ hàng, ${count} sản phẩm`);
}

function applyCartSummary(data) {
    if (data.cart_count !== undefined) {
        updateCartBadge(data.cart_count);
        $('[data-cart-header-count]').text(`${data.cart_count} sản phẩm trong giỏ`);
    }

    if (data.cart_subtotal !== undefined) {
        $('[data-cart-subtotal]').text(formatVnd(data.cart_subtotal));
    }

    if (data.shipping_fee !== undefined) {
        $('[data-cart-shipping]').text(formatVnd(data.shipping_fee));
    }

    if (data.grand_total !== undefined) {
        $('[data-cart-grand-total]').text(formatVnd(data.grand_total));
    }

    if (data.variant_id !== undefined && data.line_subtotal !== undefined) {
        $(`[data-line-subtotal="${data.variant_id}"]`).text(formatVnd(data.line_subtotal));
    }

    if (data.variant_id !== undefined && data.unit_price !== undefined) {
        $(`[data-line-unit-price="${data.variant_id}"]`).text(formatVnd(data.unit_price));
    }
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

function handleCartError(xhr) {
    const response = xhr.responseJSON ?? {};

    if (xhr.status === 422) {
        const errors = response.errors ?? {};
        const firstError = Object.values(errors).flat()[0] ?? response.message ?? 'Dữ liệu không hợp lệ.';

        showFlash(firstError, 'error');

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

    showFlash(response.message ?? 'Không thể cập nhật giỏ hàng. Vui lòng thử lại.', 'error');
}

function sendCartRequest(options) {
    const { $form, method, url, data, onSuccess, silent = false } = options;

    setFormLoading($form, true);

    return $.ajax({
        url,
        method,
        data,
        headers: {
            'X-CSRF-TOKEN': csrfToken(),
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
    })
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
            handleCartError(xhr);
        })
        .always(() => {
            setFormLoading($form, false);
        });
}

export function initCartHandlers() {
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

        event.preventDefault();

        sendCartRequest({
            $form,
            method: 'POST',
            url: $form.attr('action'),
            data: $form.serialize(),
        });
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
            onSuccess: () => {
                if (variantId) {
                    $(`[data-cart-line="${variantId}"]`).remove();
                }

                if ($('[data-cart-line]').length === 0) {
                    window.location.reload();
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
                window.location.reload();
            },
        });
    });
}
