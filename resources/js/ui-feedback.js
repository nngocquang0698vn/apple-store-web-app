export function showFlash(message, type = 'success') {
    const classes = {
        success: 'border-green-200 bg-green-50 text-green-800',
        error: 'border-red-200 bg-red-50 text-red-800',
        warning: 'border-amber-200 bg-amber-50 text-amber-800',
    };

    const $container = $('[data-flash-container]');

    if ($container.length === 0) {
        return;
    }

    $container.html(`
        <div class="mb-4 rounded-lg border px-4 py-3 text-sm ${classes[type] ?? classes.success}" role="alert">
            ${message}
        </div>
    `);
}
