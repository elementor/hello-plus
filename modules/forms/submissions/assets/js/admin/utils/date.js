const momentLocale = moment.localeData();

export function formatToLocalDate( dateString ) {
	return wp.date.format(
		momentLocale.longDateFormat( 'LL' ),
		dateString,
	);
}

export function formatToLocalTime( dateString ) {
	return wp.date.format(
		momentLocale.longDateFormat( 'LT' ),
		dateString,
	);
}

export function formatToLocalDateTime( dateString ) {
	return `${ formatToLocalDate( dateString ) } ${ formatToLocalTime( dateString ) }`;
}
