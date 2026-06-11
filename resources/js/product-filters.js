const SEARCH_DEBOUNCE_MS = 400;
const DESKTOP_PER_PAGE = 12;
const MOBILE_PER_PAGE = 3;
const MOBILE_MAX_WIDTH = 1023;

function resolvePerPage() {
    return window.matchMedia(`(max-width: ${MOBILE_MAX_WIDTH}px)`).matches
        ? MOBILE_PER_PAGE
        : DESKTOP_PER_PAGE;
}

function withListingPerPage(url) {
    const next = new URL(url, window.location.origin);

    next.searchParams.set('per_page', String(resolvePerPage()));

    return next.toString();
}

function scrollProductsPageToTop() {
    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    window.scrollTo({
        top: 0,
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
    });
}

function closeFilterDrawer() {
    $('[data-filter-drawer]').addClass('hidden');
    $('[data-action="toggle-filter-drawer"]').attr('aria-expanded', 'false');
}

function syncMobileFilterControls() {
    const url = new URL(window.location.href);
    const sort = url.searchParams.get('sort') || 'newest';

    $('[data-filter-sort-mobile]').val(sort);
}

function syncMobileFilterChips(html) {
    const $source = $('<div>').html(html).find('[data-mobile-filter-chips-source]').children();

    if ($source.length) {
        $('[data-mobile-filter-chips]').html($source.html());
    }
}

function setResultsLoading(isLoading) {
    const $results = $('[data-product-results]');
    const $loading = $('[data-product-results-loading]');

    $results.toggleClass('opacity-50 pointer-events-none', isLoading);
    $loading.toggleClass('hidden', !isLoading).toggleClass('flex', isLoading);
}

function buildQueryFromForm($form) {
    const params = new URLSearchParams();

    $form.serializeArray().forEach(({ name, value }) => {
        if (value !== '') {
            params.append(name, value);
        }
    });

    return params;
}

function fetchProductResults(url, pushState = true) {
    setResultsLoading(true);

    const listingUrl = withListingPerPage(url);

    return $.ajax({
        url: listingUrl,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .done((html) => {
            $('[data-product-results]').html(html);

            if (pushState) {
                window.history.pushState({ productFilters: true }, '', listingUrl);
            }

            const $mobileCount = $('[data-mobile-product-count]');
            const countText = $('[data-product-count]').first().text();

            if ($mobileCount.length && countText) {
                $mobileCount.text(countText);
            }

            syncMobileFilterControls();
            syncMobileFilterChips(html);
        })
        .fail(() => {
            window.location.href = listingUrl;
        })
        .always(() => {
            setResultsLoading(false);
        });
}

function submitFilterForm($form, pushState = true) {
    const params = buildQueryFromForm($form);
    const baseUrl = $form.attr('action') ?? window.location.pathname;

    params.set('per_page', String(resolvePerPage()));

    const query = params.toString();
    const url = query ? `${baseUrl}?${query}` : baseUrl;

    return fetchProductResults(url, pushState);
}

export function initProductFilters() {
    const $page = $('[data-products-index]');

    if ($page.length === 0) {
        return;
    }

    let searchTimer = null;

    syncMobileFilterControls();

    $page.on('input', '[data-filter-search]', function () {
        const $input = $(this);
        const $form = $input.closest('form');

        window.clearTimeout(searchTimer);
        searchTimer = window.setTimeout(() => {
            submitFilterForm($form);
        }, SEARCH_DEBOUNCE_MS);
    });

    $page.on('change', '[data-filter-auto-submit]', function () {
        const $form = $(this).closest('form');

        submitFilterForm($form);
    });

    $page.on('change', '[data-filter-sort-mobile]', function () {
        const url = new URL(window.location.href);

        url.searchParams.set('sort', $(this).val());
        url.searchParams.delete('page');
        fetchProductResults(url.toString());
    });

    $page.on('click', '[data-filter-category-chip]', function (event) {
        const href = $(this).attr('href');

        if (!href) {
            return;
        }

        event.preventDefault();
        fetchProductResults(href, true);
    });

    $page.on('submit', '[data-product-filters]', function (event) {
        event.preventDefault();

        submitFilterForm($(this)).done(() => {
            closeFilterDrawer();
            scrollProductsPageToTop();
        });
    });

    $page.on('click', '[data-product-pagination] a', function (event) {
        const href = $(this).attr('href');

        if (!href) {
            return;
        }

        event.preventDefault();
        fetchProductResults(href, true).done(() => {
            scrollProductsPageToTop();
        });
    });

    window.addEventListener('popstate', () => {
        fetchProductResults(window.location.href, false);
    });

    const currentPerPage = new URL(window.location.href).searchParams.get('per_page');
    const desiredPerPage = String(resolvePerPage());

    if (currentPerPage !== desiredPerPage) {
        fetchProductResults(window.location.href, true);
    }
}
