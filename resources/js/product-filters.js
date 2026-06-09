const SEARCH_DEBOUNCE_MS = 400;

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

    return $.ajax({
        url,
        method: 'GET',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
    })
        .done((html) => {
            $('[data-product-results]').html(html);

            if (pushState) {
                window.history.pushState({ productFilters: true }, '', url);
            }

            const $mobileCount = $('[data-mobile-product-count]');
            const countText = $('[data-product-count]').first().text();

            if ($mobileCount.length && countText) {
                $mobileCount.text(countText);
            }
        })
        .fail(() => {
            window.location.href = url;
        })
        .always(() => {
            setResultsLoading(false);
        });
}

function submitFilterForm($form, pushState = true) {
    const params = buildQueryFromForm($form);
    const baseUrl = $form.attr('action') ?? window.location.pathname;
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

    $page.on('submit', '[data-product-filters]', function (event) {
        event.preventDefault();
        submitFilterForm($(this));
    });

    $page.on('click', '[data-product-pagination] a', function (event) {
        const href = $(this).attr('href');

        if (!href) {
            return;
        }

        event.preventDefault();
        fetchProductResults(href, true);
    });

    window.addEventListener('popstate', () => {
        fetchProductResults(window.location.href, false);
    });
}
