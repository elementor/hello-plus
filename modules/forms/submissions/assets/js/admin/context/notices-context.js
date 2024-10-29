const { createContext, useState, useCallback, useContext } = React;

export const NOTICE_TYPE_SUCCESS = 'success';
export const NOTICE_TYPE_ERROR = 'error';

const NoticesContext = createContext( { notices: [] } );

/**
 * Use Notifications context.
 *
 * @return {{}} Notices context
 */
export function useNoticesContext() {
	return useContext( NoticesContext );
}

/**
 * Notification Provider.
 *
 * @param {any} props
 * @return {JSX.Element} Element
 * @class
 */
export function NoticesProvider( props ) {
	const [ notices, setNotices ] = useState( [] );

	// Dismiss notification (remove from view).
	const dismiss = useCallback( ( key ) => {
		setNotices( ( prev ) => prev.filter( ( notice ) => notice.key !== key ) );
	}, [] );

	// Add notification (show in view).
	const notify = useCallback( ( { message, undoAction, type, dismissible = true } ) => {
		if ( ! message ) {
			return;
		}

		const key = Date.now() + Math.random();

		setNotices( ( prev ) => [ { key, message, type, undoAction, dismissible }, ...prev ] );

		if ( props.dismissTimeout ) {
			setTimeout( () => dismiss( key ), props.dismissTimeout );
		}
	}, [] );

	// Notify an error message
	const notifyError = useCallback( ( message ) => {
		notify( { message, type: NOTICE_TYPE_ERROR } );
	}, [ notify ] );

	// Notify a success message
	const notifySuccess = useCallback( ( message, undoAction ) => {
		notify( { message, undoAction, type: NOTICE_TYPE_SUCCESS } );
	}, [ notify ] );

	return (
		<NoticesContext.Provider value={ { notices, notify, notifyError, notifySuccess, dismiss } }>
			{ props.children }
		</NoticesContext.Provider>
	);
}

NoticesProvider.propTypes = {
	children: PropTypes.any,
	dismissTimeout: PropTypes.number,
};

NoticesProvider.defaultProps = {
	dismissTimeout: 4000,
};
