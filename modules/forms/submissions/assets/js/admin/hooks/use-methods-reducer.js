/**
 * Creates a more convenient way to use `useReducer`.
 * Inspired by useMethods package
 *
 * @see https://github.com/pelotom/use-methods
 *
 * @param {any} methods
 * @param {any} initialState
 * @param {any} init
 * @return {Array<any>} [state, actions, dispatch]
 */
export default function useMethodsReducer( methods, initialState, init = undefined ) {
	const [ state, dispatch ] = React.useReducer( ( currentState, action ) => {
		if ( ! Object.prototype.hasOwnProperty.call( methods, action.type ) ) {
			throw Error( `The action type ${ action.type } is not exists` );
		}

		return methods[ action.type ]( currentState, action.payload );
	}, initialState, init );

	return [
		state,
		generateActions( methods, dispatch ),
		dispatch,
	];
}

/**
 * Bind all the actions to the dispatcher.
 *
 * @param {any}      methods
 * @param {Function} dispatch
 * @return {{}} Actions
 */
function generateActions( methods, dispatch ) {
	return Object.keys( methods )
		.reduce( ( current, type ) => {
			return {
				...current,
				[ type ]: ( payload ) => dispatch( { type, payload } ),
			};
		}, {} );
}
