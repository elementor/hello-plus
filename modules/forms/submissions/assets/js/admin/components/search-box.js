const { useState, useRef, useCallback } = React;

// In milliseconds
const DEFAULT_DEBOUNCE_TIMEOUT = 600;

/**
 * First render - nothing happens.
 * Second render - the query string value fills the search (should not trigger the debounce here in order to avoid onChange call)
 * Third render - the user type and the debounce get activate, and then it send the value up in the component tree.
 *
 * @param {any} props
 * @return {JSX.Element} Element
 * @class
 */
export default function SearchBox( props ) {
	const [ localValue, setLocalValue ] = useState( props.value || '' ),
		debounceHandler = useRef( null );

	const onChange = useCallback( ( e ) => {
		if ( debounceHandler.current ) {
			clearTimeout( debounceHandler.current );
		}

		const value = e.target.value;

		setLocalValue( value );
		debounceHandler.current = setTimeout( () => props.onChange( value ), props.debounceTimeout );
	}, [] );

	return (
		<p className="search-box e-form-submissions-search">
			{ props.label && <label className="screen-reader-text" htmlFor="search-input">{ props.label }</label> }
			{ props.isSearching && <span className="e-form-submissions-search__spinner">
				<i className="eicon-loading eicon-animation-spin" />
			</span> }
			<input
				type="search"
				id="search-input"
				name="s"
				value={ localValue }
				onChange={ onChange }
				placeholder={ __( 'Search...', 'elementor-pro' ) }
			/>
		</p>
	);
}

SearchBox.propTypes = {
	value: PropTypes.string,
	onChange: PropTypes.func.isRequired,
	label: PropTypes.string,
	debounceTimeout: PropTypes.number,
	isSearching: PropTypes.bool,
};

SearchBox.defaultProps = {
	debounceTimeout: DEFAULT_DEBOUNCE_TIMEOUT,
	isSearching: false,
};
