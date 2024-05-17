export function formatReal(number) {

    var formatter = new Intl.NumberFormat('pt-BR', {
        style: 'currency',
        currency: 'BRL',
    });

    if (!isNaN(parseFloat(number))) {
        return formatter.format(number);
    }
    return '-'
}