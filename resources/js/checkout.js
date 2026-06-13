import { formatVnd } from './vnd-money';
import { showFlash } from './ui-feedback';

function applyCheckoutSummary(data) {
    if (!data) {
        return;
    }

    if (data.cart_subtotal !== undefined) {
        $('[data-checkout-subtotal]').text(formatVnd(data.cart_subtotal));
    }

    if (data.shipping_fee !== undefined) {
        $('[data-checkout-shipping]').text(formatVnd(data.shipping_fee));
    }

    if (data.grand_total !== undefined) {
        $('[data-checkout-grand-total]').text(formatVnd(data.grand_total));
    }

    if (Array.isArray(data.items)) {
        data.items.forEach((item) => {
            const variantId = item.variant_id;

            $(`[data-checkout-line-subtotal="${variantId}"]`).text(formatVnd(item.line_subtotal));

            const $warning = $(`[data-checkout-line-warning="${variantId}"]`);

            if (item.is_purchasable) {
                $warning.addClass('hidden');
            } else {
                $warning.removeClass('hidden');
            }
        });
    }

    const $warnings = $('[data-checkout-warnings]');

    if (Array.isArray(data.warnings)) {
        if (data.warnings.length === 0) {
            $warnings.empty();
        } else {
            $warnings.html(
                data.warnings
                    .map((warning) => `<p class="rounded-lg border border-amber-200 bg-amber-50 px-3 py-2 text-amber-800">${warning}</p>`)
                    .join(''),
            );
        }
    }

    const $submit = $('[data-checkout-submit]');

    if (data.can_checkout !== undefined) {
        $submit.prop('disabled', !data.can_checkout);
    }
}

function setSummaryLoading(isLoading) {
    const $summary = $('[data-checkout-summary]');

    $summary.toggleClass('opacity-60 pointer-events-none', isLoading);
}

function refreshCheckoutSummary(url) {
    setSummaryLoading(true);

    return $.ajax({
        url,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
            Accept: 'application/json',
        },
    })
        .done((response) => {
            if (response?.data) {
                applyCheckoutSummary(response.data);
            }
        })
        .fail((xhr) => {
            const response = xhr.responseJSON ?? {};

            if (xhr.status === 409 && response.data) {
                applyCheckoutSummary(response.data);
                showFlash(response.message ?? 'Giá hoặc tồn kho đã thay đổi.', 'warning');

                return;
            }

            if (xhr.status === 422) {
                showFlash(response.message ?? 'Giỏ hàng không hợp lệ.', 'error', { autoDismiss: false });

                return;
            }

            showFlash('Không thể cập nhật tổng tiền. Vui lòng thử lại.', 'error');
        })
        .always(() => {
            setSummaryLoading(false);
        });
}

export function initCheckoutSummary() {
    const $page = $('[data-checkout-page]');

    if ($page.length === 0) {
        return;
    }

    const summaryUrl = $page.data('summaryUrl');

    $page.on('submit', '[data-action="place-order"]', function () {
        const $form = $(this);
        const $submit = $form.find('[data-checkout-submit]');

        $submit.prop('disabled', true);
        $submit.find('[data-checkout-submit-label]').text('Đang xử lý...');
    });

    refreshCheckoutSummary(summaryUrl);
}
