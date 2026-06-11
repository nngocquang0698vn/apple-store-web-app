const FLASH_DISMISS_MS = 4000;

const FLASH_CLASSES = {
    success: 'border-green-200 bg-green-50 text-green-800',
    error: 'border-red-200 bg-red-50 text-red-800',
    warning: 'border-amber-200 bg-amber-50 text-amber-800',
    info: 'border-blue-200 bg-blue-50 text-blue-800',
};

function clearDismissTimer($alert) {
    const timer = $alert.data('dismissTimer');

    if (timer) {
        clearTimeout(timer);
        $alert.removeData('dismissTimer');
    }
}

function scheduleDismiss($alert, ms = FLASH_DISMISS_MS) {
    if (!$alert || $alert.length === 0) {
        return;
    }

    clearDismissTimer($alert);

    const timer = setTimeout(() => {
        $alert.fadeOut(200, function () {
            $(this).remove();
        });
    }, ms);

    $alert.data('dismissTimer', timer);
}

export function showFlash(message, type = 'success') {
    const $container = $('[data-flash-container]');

    if ($container.length === 0) {
        return;
    }

    $container.find('[data-flash-alert]').each(function () {
        clearDismissTimer($(this));
    });

    $container.html(`
        <div
            class="mb-4 rounded-lg border px-4 py-3 text-sm ${FLASH_CLASSES[type] ?? FLASH_CLASSES.success}"
            role="alert"
            data-flash-alert
        >
            ${message}
        </div>
    `);

    scheduleDismiss($container.find('[data-flash-alert]'));
}

export function initFlashAutoDismiss() {
    $('[data-flash-alert]').each(function () {
        scheduleDismiss($(this));
    });
}
