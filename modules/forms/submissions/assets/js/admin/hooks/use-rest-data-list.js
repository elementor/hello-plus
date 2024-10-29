import useMethodsReducer from './use-methods-reducer';
import useRouterQueryString from './use-router-query-string';
import useDataAction from './use-data-action';

const { useState, useEffect, useMemo } = React;

/**
 * Default options for the use function
 *
 * @type {{useRouterQueryString: boolean, allowedFilters: Array}}
 */
const defaultOptions = {
	allowedFilters: [],
	useRouterQueryString: false,
	hooks: {
		afterFetch: () => {},
	},
};

const defaultQueryArgs = [ 'order', 'order_by', 'page', 'per_page' ];

let currentFlatQuery = {};

/**
 * Main function
 *
 * @param {any}    command
 * @param {Object} options
 * @return {{fetchData: any, data: any, meta: any, query: any, actions: {setOrder: any, setFilter: any, setPage: any}}} REST data
 */
export default function useRestDataList( command, options = {} ) {
	options = { ...defaultOptions, ...options };

	const
		queryArgs = [ ...defaultQueryArgs, ...options.allowedFilters ],
		[ { data, meta }, setFetchResult ] = useState( { data: [], meta: {} } ),
		{ query, flatQuery, actions: { setFilter, setPage, setOrder, setInitial } } = useQuery(),
		[ routerQueryString, setRouterQueryString ] = useRouterQueryString( queryArgs );

	// There is a weird bug, when calling the fetch data function from another function
	// the flatQuery inside the function is an old version of the flatQuery and not the updated one.
	// this cause a wrong request to the server that retrieve a wrong result.
	currentFlatQuery = flatQuery;

	// The fetch data action.
	const [ fetchData, { status: fetchDataStatus } ] = useDataAction(
		( args, { abortController } ) =>
			$e.data
				.get( command, currentFlatQuery, { refresh: true, signal: abortController.signal } )
				.then( ( response ) => setFetchResult( response.data ) )
				.then( () => options.hooks.afterFetch() ),
		[ command, flatQuery ],
	);

	useEffect( () => {
		const queryStringResult = options.useRouterQueryString ? routerQueryString : {};

		setInitial( {
			filter: {
				search: queryStringResult?.search || null,
				status: queryStringResult?.status || 'all',
				form: queryStringResult?.form || null,
				referer: queryStringResult?.referer || null,
				after: queryStringResult?.after || null,
				before: queryStringResult?.before || null,
			},
			page: queryStringResult.page || 1,
			perPage: queryStringResult.per_page || 50,
			order: {
				by: queryStringResult.order_by || 'created_at',
				direction: queryStringResult.order || 'desc',
			},
		} );
	}, [] );

	// This effect runs every time the query object changes, and it fetch the data from the server.
	useEffect( () => {
		if ( ! query.ready ) {
			return;
		}

		if ( options.useRouterQueryString ) {
			setRouterQueryString( flatQuery );
		}

		fetchData();
	}, [ flatQuery ] );

	return {
		data,
		meta,
		query,
		flatQuery,
		fetchData,
		statuses: {
			fetchDataStatus,
		},
		actions: {
			setFilter,
			setOrder,
			setPage,
		},
	};
}

/**
 * A reducer for the query of the rest list fetch.
 *
 * @return {{perPage: any, page: number}|{filter: any, page: number}|{ready: boolean}|{page: any}|(any|{})|Array|{sort: any}} { query, flatQuery, actions }
 */
function useQuery() {
	const [ query, actions ] = useMethodsReducer(
		{
			setFilter: ( state, filter ) => ( { ...state, page: 1, filter: {
				...state.filter,
				...filter,
			} } ),
			setPage: ( state, page ) => ( { ...state, page } ),
			setPerPage: ( state, perPage ) => ( { ...state, page: 1, perPage } ),
			setOrder: ( state, order ) => ( { ...state, order } ),
			setInitial: ( state, payload ) => ( { ...state, ...payload, ready: true } ),
		},
		{
			ready: false,
			filter: {},
			page: 1,
			perPage: null,
			order: { by: 'id', direction: 'desc' },
		},
	);

	const flatQuery = useMemo( () => {
		return Object.fromEntries(
			Object.entries( {
				...query.filter,
				page: query.page,
				per_page: query.perPage,
				order: query.order.direction,
				order_by: query.order.by,
			} ).filter( ( [ , value ] ) => {
				// Removes all the falsy values from the flatQuery. In JS empty array is not a falsy value
				// so there is a special case that checks array and removes it from the flatQuery if it is an empty array.
				if ( Array.isArray( value ) && 0 === value.length ) {
					return false;
				}

				return !! value;
			} ),
		);
	}, [ query ] );

	return { query, flatQuery, actions };
}
