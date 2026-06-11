function resolvePlaceholderSrc($root) {
    return $root.data('placeholderSrc') || '/images/placeholders/product-placeholder.svg';
}

function parseIndex(value, fallback = 0) {
    const parsed = Number(value);

    return Number.isFinite(parsed) ? parsed : fallback;
}

function clampIndex(index, count, loop) {
    if (count <= 0) {
        return 0;
    }

    if (loop) {
        return ((index % count) + count) % count;
    }

    return Math.max(0, Math.min(count - 1, index));
}

function updateNavButtons($root, index, count, loop) {
    const atStart = index <= 0;
    const atEnd = index >= count - 1;

    $root.find('[data-carousel-prev]').prop('disabled', !loop && atStart);
    $root.find('[data-carousel-next]').prop('disabled', !loop && atEnd);
}

function updateThumbState($root, index) {
    $root.find('[data-carousel-thumb]').each(function () {
        const $thumb = $(this);
        const isActive = parseIndex($thumb.data('index')) === index;

        $thumb.toggleClass('border-blue-500 bg-blue-50 ring-2 ring-blue-200', isActive);
        $thumb.toggleClass('border-gray-200 bg-white hover:border-blue-400 hover:bg-blue-50', !isActive);
        $thumb.attr('aria-selected', isActive ? 'true' : 'false');
        $thumb.attr('aria-current', isActive ? 'true' : 'false');
    });
}

function updateDotState($root, index) {
    $root.find('[data-carousel-dot]').each(function () {
        const $dot = $(this);
        const isActive = parseIndex($dot.data('index')) === index;

        $dot.toggleClass('w-5 bg-blue-600', isActive);
        $dot.toggleClass('w-1.5 bg-gray-300 hover:bg-gray-400', !isActive);
        $dot.attr('aria-current', isActive ? 'true' : 'false');
    });
}

function updateSlideState($root, index) {
    $root.find('[data-carousel-slide]').each(function () {
        const $slide = $(this);
        const isActive = parseIndex($slide.data('index')) === index;

        $slide.toggleClass('opacity-100 pointer-events-auto', isActive);
        $slide.toggleClass('opacity-0 pointer-events-none', !isActive);
        $slide.attr('aria-hidden', isActive ? 'false' : 'true');
    });
}

function updateGalleryMainImage($root, index) {
    const $thumb = $root.find(`[data-carousel-thumb][data-index="${index}"]`).first();

    if ($thumb.length === 0) {
        return;
    }

    const src = $thumb.data('imageSrc');
    const alt = $thumb.data('imageAlt');
    const $mainImage = $root.find('[data-carousel-main-image]');

    if (src) {
        $mainImage.attr('src', src);
    }

    if (alt) {
        $mainImage.attr('alt', alt);
    }
}

function updateIndicator($root, index, count, label) {
    const $indicator = $root.find('[data-carousel-indicator]');

    if ($indicator.length === 0) {
        return;
    }

    if (label) {
        $indicator.text(label);
    } else {
        $indicator.text(`${index + 1} / ${count}`);
    }
}

function bindKeyboardNavigation($root, navigate) {
    $root.on('keydown', function (event) {
        if (!['ArrowLeft', 'ArrowRight', 'Home', 'End'].includes(event.key)) {
            return;
        }

        event.preventDefault();

        if (event.key === 'ArrowLeft') {
            navigate(-1);
        } else if (event.key === 'ArrowRight') {
            navigate(1);
        } else if (event.key === 'Home') {
            navigate('start');
        } else if (event.key === 'End') {
            navigate('end');
        }
    });
}

function startAutoplay($root, navigate, intervalMs) {
    window.clearInterval($root.data('autoplayTimer'));

    if (intervalMs <= 0) {
        return;
    }

    const timer = window.setInterval(() => {
        navigate(1);
    }, intervalMs);

    $root.data('autoplayTimer', timer);
}

function stopAutoplay($root) {
    window.clearInterval($root.data('autoplayTimer'));
    $root.removeData('autoplayTimer');
}

function createCarouselController($root) {
    const mode = $root.data('carouselMode') || 'slides';
    const loop = $root.data('carouselLoop') === true || $root.data('carouselLoop') === 'true';
    const autoplayMs = parseIndex($root.data('autoplayMs'), 0);

    function getCount() {
        if (mode === 'gallery') {
            return $root.find('[data-carousel-thumb]').length;
        }

        return $root.find('[data-carousel-slide]').length;
    }

    function getIndex() {
        return parseIndex($root.data('activeIndex'), 0);
    }

    function buildStatusLabel(index) {
        if (mode !== 'slides') {
            return null;
        }

        const $slide = $root.find(`[data-carousel-slide][data-index="${index}"]`).first();
        const productName = $slide.find('h2').first().text().trim();

        return productName ? `${productName} — ${index + 1} / ${getCount()}` : null;
    }

    function setIndex(rawIndex) {
        const count = getCount();

        if (count === 0) {
            return;
        }

        const index = clampIndex(rawIndex, count, loop);

        $root.data('activeIndex', index);

        if (mode === 'gallery') {
            updateGalleryMainImage($root, index);
            updateThumbState($root, index);
        } else {
            updateSlideState($root, index);
            updateThumbState($root, index);
            updateDotState($root, index);
        }

        updateNavButtons($root, index, count, loop);
        updateIndicator($root, index, count, buildStatusLabel(index));
    }

    function navigate(direction) {
        const count = getCount();

        if (count <= 1) {
            return;
        }

        const currentIndex = getIndex();

        if (direction === 'start') {
            setIndex(0);

            return;
        }

        if (direction === 'end') {
            setIndex(count - 1);

            return;
        }

        setIndex(currentIndex + direction);
    }

    bindKeyboardNavigation($root, navigate);

    $root.on('click', '[data-carousel-prev]', () => navigate(-1));
    $root.on('click', '[data-carousel-next]', () => navigate(1));
    $root.on('click', '[data-carousel-thumb], [data-carousel-dot]', function () {
        setIndex(parseIndex($(this).data('index')));
    });

    $root.find('[data-carousel-main-image]').on('error', function () {
        $(this).attr('src', resolvePlaceholderSrc($root));
    });

    if (autoplayMs > 0) {
        $root.on('mouseenter focusin', () => stopAutoplay($root));
        $root.on('mouseleave focusout', () => startAutoplay($root, navigate, autoplayMs));
        startAutoplay($root, navigate, autoplayMs);
    }

    setIndex(0);

    return {
        setIndex,
        navigate,
        getIndex,
        getCount,
    };
}

export function initCarousels() {
    $('[data-carousel]').each(function () {
        const $root = $(this);
        const controller = createCarouselController($root);

        $root.data('carouselController', controller);
    });
}

export function goToCarouselImageBySrc(src) {
    if (!src) {
        return;
    }

    const $gallery = $('[data-product-gallery]');

    if ($gallery.length === 0) {
        return;
    }

    const $match = $gallery.find('[data-carousel-thumb]').filter(function () {
        return $(this).data('imageSrc') === src;
    }).first();

    if ($match.length > 0) {
        const controller = $gallery.data('carouselController');

        if (controller) {
            controller.setIndex(parseIndex($match.data('index')));
        }

        return;
    }

    $gallery.find('[data-carousel-main-image]').attr('src', src);
}
