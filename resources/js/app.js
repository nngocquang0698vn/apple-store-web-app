import jQuery from 'jquery';

window.$ = window.jQuery = jQuery;

$(function () {
    $('[data-action="toggle-mobile-nav"]').on('click', function () {
        const $button = $(this);
        const $nav = $('[data-mobile-nav]');
        const isExpanded = $button.attr('aria-expanded') === 'true';

        $nav.toggleClass('hidden flex');
        $button.attr('aria-expanded', isExpanded ? 'false' : 'true');
    });

    $('[data-action="toggle-admin-sidebar"]').on('click', function () {
        const $button = $(this);
        const $nav = $('[data-admin-sidebar-nav]');
        const isExpanded = $button.attr('aria-expanded') === 'true';

        $nav.toggleClass('hidden block');
        $button.attr('aria-expanded', isExpanded ? 'false' : 'true');
    });
});
