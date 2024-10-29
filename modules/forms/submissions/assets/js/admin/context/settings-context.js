const { createContext, useContext } = React;

const SettingsContext = createContext( {} );

/**
 * Consume the context
 *
 * @return {{emptyTrashDays: number}} Settings context
 */
export function useSettingsContext() {
	return useContext( SettingsContext );
}

/**
 * Settings Provider
 *
 * @param {any} props
 * @return {JSX.Element} Element
 * @class
 */
export function SettingsProvider( props ) {
	return (
		<SettingsContext.Provider value={ props.value }>
			{ props.children }
		</SettingsContext.Provider>
	);
}

SettingsProvider.propTypes = {
	children: PropTypes.any,
	value: PropTypes.object.isRequired,
};
