function setShowcaseSlide($root, index) {
    const $slides = $root.find('[data-showcase-slide]');
    const total = $slides.length;

    if (total === 0) {
        return;
    }

    const nextIndex = ((index % total) + total) % total;
    const $activeSlide = $slides.filter(`[data-index="${nextIndex}"]`);
    const productName = $activeSlide.find('h2').first().text().trim();

    $slides.each(function () {
        const $slide = $(this);
        const isActive = Number($slide.data('index')) === nextIndex;

        $slide.toggleClass('opacity-100 pointer-events-auto', isActive);
        $slide.toggleClass('opacity-0 pointer-events-none', !isActive);
        $slide.attr('aria-hidden', isActive ? 'false' : 'true');
    });

    $root.find('[data-showcase-thumb]').each(function () {
        const $thumb = $(this);
        const isActive = Number($thumb.data('index')) === nextIndex;

        $thumb.toggleClass('border-blue-500 bg-blue-50 ring-2 ring-blue-200', isActive);
        $thumb.toggleClass('border-gray-200 bg-white hover:border-gray-300', !isActive);
        $thumb.attr('aria-selected', isActive ? 'true' : 'false');
    });

    $root.find('[data-showcase-dot]').each(function () {
        const $dot = $(this);
        const isActive = Number($dot.data('index')) === nextIndex;

        $dot.toggleClass('w-5 bg-blue-600', isActive);
        $dot.toggleClass('w-1.5 bg-gray-300 hover:bg-gray-400', !isActive);
        $dot.attr('aria-current', isActive ? 'true' : 'false');
    });

    $root.find('[data-showcase-status]').text(`${productName} — ${nextIndex + 1} / ${total}`);
    $root.data('activeIndex', nextIndex);
}

function startShowcaseAutoplay($root) {
    const intervalMs = Number($root.data('autoplayMs')) || 5000;

    window.clearInterval($root.data('autoplayTimer'));

    if ($root.find('[data-showcase-slide]').length <= 1) {
        return;
    }

    const timer = window.setInterval(() => {
        const currentIndex = Number($root.data('activeIndex') ?? 0);

        setShowcaseSlide($root, currentIndex + 1);
    }, intervalMs);

    $root.data('autoplayTimer', timer);
}

function stopShowcaseAutoplay($root) {
    window.clearInterval($root.data('autoplayTimer'));
    $root.removeData('autoplayTimer');
}

export function initHomeShowcase() {
    const $root = $('[data-home-showcase]');

    if ($root.length === 0) {
        return;
    }

    $root.each(function () {
        const $showcase = $(this);

        setShowcaseSlide($showcase, 0);
        startShowcaseAutoplay($showcase);

        $showcase.on('mouseenter focusin', () => {
            stopShowcaseAutoplay($showcase);
        });

        $showcase.on('mouseleave focusout', () => {
            startShowcaseAutoplay($showcase);
        });

        $showcase.on('click', '[data-showcase-prev]', () => {
            const currentIndex = Number($showcase.data('activeIndex') ?? 0);

            setShowcaseSlide($showcase, currentIndex - 1);
        });

        $showcase.on('click', '[data-showcase-next]', () => {
            const currentIndex = Number($showcase.data('activeIndex') ?? 0);

            setShowcaseSlide($showcase, currentIndex + 1);
        });

        $showcase.on('click', '[data-showcase-thumb], [data-showcase-dot]', function () {
            setShowcaseSlide($showcase, Number($(this).data('index')));
        });
    });
}
