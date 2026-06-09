/**
 * Format integer VND for display only. Business values always come from the server.
 */
export function formatVnd(amount) {
    const value = Number(amount);

    if (!Number.isFinite(value)) {
        return '0 ₫';
    }

    return `${Math.round(value).toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')} ₫`;
}
