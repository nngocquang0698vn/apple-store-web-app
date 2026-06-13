import jQuery from 'jquery';
import { initCartHandlers } from './cart';
import { initCheckoutSummary } from './checkout';
import { initHomeShowcase } from './home-showcase';
import { initProductFilters } from './product-filters';
import { initProductVariantSelector } from './product-variant';
import { initFlashAutoDismiss, initFlashDismissButtons } from './ui-feedback';

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

    $('[data-action="toggle-filter-drawer"]').on('click', function () {
        const $drawer = $('[data-filter-drawer]');
        $drawer.removeClass('hidden');
        $(this).attr('aria-expanded', 'true');
    });

    $('[data-action="close-filter-drawer"]').on('click', function () {
        const $drawer = $('[data-filter-drawer]');
        $drawer.addClass('hidden');
        $('[data-action="toggle-filter-drawer"]').attr('aria-expanded', 'false');
    });

    initFlashDismissButtons();
    initFlashAutoDismiss();
    initHomeShowcase();
    initProductVariantSelector();
    initCartHandlers();
    initCheckoutSummary();
    initProductFilters();
});
