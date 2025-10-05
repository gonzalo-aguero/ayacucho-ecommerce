export function priceFormat(value){
    return '$' + new Intl.NumberFormat("de-DE").format(
        value,
    );
}

export function decimalFormat(value){
    return new Intl.NumberFormat("de-DE").format(
        value,
    );
}
