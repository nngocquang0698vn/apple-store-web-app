const FLASH_DISMISS_MS = 3000;

const FLASH_CLASSES = {
    success: 'border-green-200 bg-green-50 text-green-800',
    error: 'border-red-200 bg-red-50 text-red-800',
    warning: 'border-amber-200 bg-amber-50 text-amber-800',
    info: 'border-blue-200 bg-blue-50 text-blue-800',
};

const FLASH_DISMISS_BUTTON = `
    <button
        type="button"
        class="inline-flex shrink-0 rounded-md p-1 opacity-70 transition hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-current focus:ring-offset-1"
        data-action="dismiss-flash"
        aria-label="Đóng thông báo"
    >
        <i class="fa-solid fa-xmark" aria-hidden="true"></i>
    </button>
`;

function clearDismissTimer($alert) {
    const timer = $alert.data('dismissTimer');

    if (timer) {
        clearTimeout(timer);
        $alert.removeData('dismissTimer');
    }
}

export function dismissFlash($alert) {
    if (!$alert || $alert.length === 0) {
        return;
    }

    clearDismissTimer($alert);
    $alert.fadeOut(200, function () {
        $(this).remove();
    });
}

export function scrollFlashIntoView() {
    const $container = $('[data-flash-container]');

    if ($container.length === 0) {
        return;
    }

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const top = $container.offset()?.top ?? 0;

    window.scrollTo({
        top: Math.max(0, top - 12),
        behavior: prefersReducedMotion ? 'auto' : 'smooth',
    });
}

function scheduleDismiss($alert, ms = FLASH_DISMISS_MS) {
    if (!$alert || $alert.length === 0 || $alert.is('[data-flash-persistent]')) {
        return;
    }

    clearDismissTimer($alert);

    const timer = setTimeout(() => {
        dismissFlash($alert);
    }, ms);

    $alert.data('dismissTimer', timer);
}

/**
 * @param {string} message
 * @param {'success'|'error'|'warning'|'info'} [type]
 * @param {{ autoDismiss?: boolean, scrollToTop?: boolean }} [options]
 */
export function showFlash(message, type = 'success', options = {}) {
    const autoDismiss = options.autoDismiss !== false;
    const scrollToTop = options.scrollToTop !== false;
    const $container = $('[data-flash-container]');

    if ($container.length === 0) {
        return;
    }

    $container.find('[data-flash-alert]').each(function () {
        clearDismissTimer($(this));
    });

    const persistentAttr = autoDismiss ? 'data-flash-auto-dismiss' : 'data-flash-persistent';

    $container.html(`
        <div
            class="mb-4 flex items-start gap-3 rounded-lg border px-4 py-3 text-sm ${FLASH_CLASSES[type] ?? FLASH_CLASSES.success}"
            role="alert"
            data-flash-alert
            ${persistentAttr}
        >
            <div class="min-w-0 flex-1">${message}</div>
            ${FLASH_DISMISS_BUTTON}
        </div>
    `);

    const $alert = $container.find('[data-flash-alert]');

    if (autoDismiss) {
        scheduleDismiss($alert);
    }

    if (scrollToTop) {
        window.requestAnimationFrame(() => {
            scrollFlashIntoView();
        });
    }
}

export function initFlashAutoDismiss() {
    $('[data-flash-auto-dismiss]').each(function () {
        scheduleDismiss($(this));
    });
}

export function initFlashDismissButtons() {
    $(document).on('click', '[data-action="dismiss-flash"]', function () {
        dismissFlash($(this).closest('[data-flash-alert]'));
    });
}
