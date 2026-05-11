export function formatCurrency(number) {
    if (number === null || number === undefined) {
        return 'Rp 0 ,-';
    }
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', minimumFractionDigits: 0 }).format(number) + ' ,-';
}
