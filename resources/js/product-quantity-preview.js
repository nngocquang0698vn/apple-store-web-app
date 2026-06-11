import { formatVnd } from './vnd-money';

function normalizeQuantity(value, min = 1, max = 99) {
    const parsed = Number.parseInt(String(value), 10);

    if (!Number.isFinite(parsed)) {
        return null;
    }

    return Math.min(max, Math.max(min, parsed));
}

export function updateProductPreview($root) {
    const unitPrice = Number($root.data('unitPrice')) || 0;
    const $input = $root.find('[data-product-quantity]');
    const max = Number($input.attr('max')) || 99;
    const quantity = normalizeQuantity($input.val(), 1, max) ?? 1;

    $root.find('[data-product-preview-subtotal]').text(formatVnd(unitPrice * quantity));
}
