const { useRef, useCallback, useState, useMemo } = React;

export const STATUS_IDLE = 'idle';
export const STATUS_LOADING = 'loading';
export const STATUS_SUCCESS = 'success';
export const STATUS_ERROR = 'error';

export default function useDataAction( action, deps = [] ) {
	const abortControllerRef = useRef();
	const [ status, setStatus ] = useState( STATUS_IDLE );

	// To avoid multiple declaration of the action callback, it memorize the argument that SHOULD
	// trigger a new declaration of the action callback.
	const calculatedDeps = useMemo( () => [ ...deps, status ], [ deps, status ] );

	const wrappedAction = useCallback( ( ...args ) => {
		if ( abortControllerRef.current ) {
			abortControllerRef.current.abort();
		}

		abortControllerRef.current = new AbortController();

		setStatus( STATUS_LOADING );

		return action( args, { abortController: abortControllerRef.current, status } )
			.then( ( result ) => {
				setStatus( STATUS_SUCCESS );

				return result;
			} )
			.catch( ( error ) => {
				setStatus( STATUS_ERROR );

				return Promise.reject( error );
			} );
	}, [ calculatedDeps ] );

	return [
		wrappedAction,
		{
			abortController: abortControllerRef.current,
			status,
		},
	];
}
