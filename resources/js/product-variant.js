import { formatVnd } from './vnd-money';

function parseVariants() {
    const $data = $('#product-variants-data');

    if ($data.length === 0) {
        return [];
    }

    try {
        return JSON.parse($data.text());
    } catch {
        return [];
    }
}

function findVariant(variants, colorSlug, storageGb) {
    return variants.find((variant) => {
        const colorMatch = colorSlug ? variant.color_slug === colorSlug : true;
        const storageMatch = storageGb !== null && storageGb !== undefined
            ? variant.storage_gb === storageGb
            : true;

        return colorMatch && storageMatch;
    }) ?? null;
}

function resolveVariant(variants, colorSlug, storageGb) {
    const exact = findVariant(variants, colorSlug, storageGb);

    if (exact) {
        return exact;
    }

    if (colorSlug) {
        const byColor = variants.find((variant) => variant.color_slug === colorSlug);

        if (byColor) {
            return byColor;
        }
    }

    return variants.find((variant) => variant.stock_quantity > 0) ?? variants[0] ?? null;
}

function storagesForColor(variants, colorSlug) {
    return [...new Set(
        variants
            .filter((variant) => !colorSlug || variant.color_slug === colorSlug)
            .map((variant) => variant.storage_gb)
            .filter((value) => value !== null && value !== undefined),
    )];
}

function updateUrl(colorSlug, storageGb) {
    const url = new URL(window.location.href);

    if (colorSlug) {
        url.searchParams.set('color', colorSlug);
    } else {
        url.searchParams.delete('color');
    }

    if (storageGb !== null && storageGb !== undefined) {
        url.searchParams.set('storage', String(storageGb));
    } else {
        url.searchParams.delete('storage');
    }

    window.history.replaceState({}, '', url.toString());
}

function setSelectedState($root, colorSlug, storageGb, variants) {
    $root.find('[data-action="select-color"]').each(function () {
        const $button = $(this);
        const isSelected = $button.data('colorSlug') === colorSlug;
        const isDisabled = $button.data('disabled') === true || $button.data('disabled') === 'true';

        $button
            .toggleClass('border-blue-600 bg-blue-50 text-blue-700', isSelected && !isDisabled)
            .toggleClass('border-gray-300 bg-white text-gray-700', !isSelected && !isDisabled)
            .toggleClass('border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed opacity-60', isDisabled)
            .attr('aria-current', isSelected ? 'true' : 'false');
    });

    const availableStorages = storagesForColor(variants, colorSlug);

    $root.find('[data-action="select-storage"]').each(function () {
        const $button = $(this);
        const buttonStorage = Number($button.data('storageGb'));
        const isAvailable = availableStorages.includes(buttonStorage);
        const isSelected = isAvailable && buttonStorage === storageGb;

        $button
            .toggleClass('border-blue-600 bg-blue-50 text-blue-700', isSelected)
            .toggleClass('border-gray-300 bg-white text-gray-700 hover:border-gray-400', isAvailable && !isSelected)
            .toggleClass('border-gray-200 bg-gray-100 text-gray-400 cursor-not-allowed opacity-60', !isAvailable)
            .attr('aria-disabled', isAvailable ? 'false' : 'true')
            .attr('aria-current', isSelected ? 'true' : 'false')
            .data('disabled', !isAvailable);
    });
}

function renderVariant($root, variant, variants) {
    if (!variant) {
        return;
    }

    const inStock = variant.stock_quantity > 0;
    const $salePrice = $root.find('[data-product-sale-price]');
    const $originalPrice = $root.find('[data-product-original-price]');
    const $stock = $root.find('[data-product-stock]');
    const $sku = $root.find('[data-product-sku]');
    const $variantInput = $root.find('[data-product-variant-id]');
    const $quantity = $root.find('[data-product-quantity]');
    const $submit = $root.find('[data-product-add-button]');
    const $image = $root.find('[data-product-primary-image] img');

    $salePrice.text(formatVnd(variant.sale_price));

    if (variant.original_price && variant.original_price > variant.sale_price) {
        $originalPrice.removeClass('hidden').text(formatVnd(variant.original_price));
    } else {
        $originalPrice.addClass('hidden').text('');
    }

    if (inStock) {
        $stock.removeClass('text-red-600').addClass('text-green-700').text(`Còn ${variant.stock_quantity} sản phẩm`);
    } else {
        $stock.removeClass('text-green-700').addClass('text-red-600').text('Hết hàng');
    }

    $sku.text(`SKU: ${variant.sku}`);
    $variantInput.val(variant.id);
    $quantity.attr('max', Math.max(1, variant.stock_quantity));

    if (!inStock) {
        $quantity.prop('disabled', true);
    } else {
        $quantity.prop('disabled', false);
        if (Number($quantity.val()) > variant.stock_quantity) {
            $quantity.val(variant.stock_quantity);
        }
    }

    $submit.prop('disabled', !inStock);

    const label = inStock ? 'Thêm vào giỏ hàng' : 'Hết hàng';
    $submit.find('[data-add-cart-label]').text(label);

    if (variant.image_url && $image.length) {
        $image.attr('src', variant.image_url);
    }

    setSelectedState($root, variant.color_slug, variant.storage_gb, variants);
}

export function initProductVariantSelector() {
    const $root = $('[data-product-detail]');

    if ($root.length === 0) {
        return;
    }

    const variants = parseVariants();

    if (variants.length === 0) {
        return;
    }

    let colorSlug = $root.data('selectedColor') || null;
    let storageGb = $root.data('selectedStorage');
    storageGb = storageGb !== undefined && storageGb !== '' ? Number(storageGb) : null;

    const applySelection = () => {
        const variant = resolveVariant(variants, colorSlug, storageGb);

        if (!variant) {
            return;
        }

        colorSlug = variant.color_slug ?? colorSlug;
        storageGb = variant.storage_gb ?? storageGb;
        renderVariant($root, variant, variants);
        updateUrl(colorSlug, storageGb);
    };

    $root.on('click', '[data-action="select-color"]', function (event) {
        const $button = $(this);

        if ($button.data('disabled') === true || $button.data('disabled') === 'true') {
            event.preventDefault();

            return;
        }

        event.preventDefault();
        colorSlug = $button.data('colorSlug');

        const availableStorages = storagesForColor(variants, colorSlug);

        if (storageGb === null || !availableStorages.includes(storageGb)) {
            storageGb = availableStorages[0] ?? null;
        }

        applySelection();
    });

    $root.on('click', '[data-action="select-storage"]', function (event) {
        const $button = $(this);

        if ($button.data('disabled') === true || $button.data('disabled') === 'true') {
            event.preventDefault();

            return;
        }

        event.preventDefault();
        storageGb = Number($button.data('storageGb'));
        applySelection();
    });

    applySelection();
}
