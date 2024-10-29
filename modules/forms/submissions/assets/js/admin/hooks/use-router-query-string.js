import { useLocation } from '@reach/router';

const { useEffect, useState } = React;

/**
 * Manage the router query string base on "hash" history.
 *
 * @param {string[]} queryArgs
 * @return {Array<any, any>} [ query, setQuery ]
 */
export default function useRouterQueryString( queryArgs = [] ) {
	const location = useLocation(),
		[ isLocationRead, setIsLocationRead ] = useState( false ),
		[ query, setQuery ] = useState( false );

	// Read the query string (only at first render)
	useEffect( () => {
		if ( ! location?.pathname || isLocationRead ) {
			return;
		}

		const parsedQueryString = queryArgs.reduce( ( current, arg ) => {
			const value = wp.url.getQueryArg( location.pathname, arg );

			if ( undefined === value ) {
				return current;
			}

			return {
				...current,
				[ arg ]: value,
			};
		}, {} );

		setQuery( parsedQueryString );
		setIsLocationRead( true );
	}, [ location ] );

	// Update the query string based on the query.
	useEffect( () => {
		if ( ! query ) {
			return;
		}

		const basePath = location.pathname.split( '?' )[ 0 ] || '/';

		history.pushState(
			undefined,
			undefined,
			`#${ wp.url.addQueryArgs( basePath, query ) }`,
		);
	}, [ query ] );

	return [
		query,
		setQuery,
	];
}

