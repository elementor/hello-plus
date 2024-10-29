const { useEffect, useRef, useState } = React;

function renderSelect2( el, onChange ) {
	jQuery( el )
		.select2( {
			allowClear: true,
			placeholder: __( 'All Pages', 'elementor-pro' ),
			dir: elementorCommon.config.isRTL ? 'rtl' : 'ltr',
			ajax: {
				delay: 400,
				transport( { data: { search } }, success, failure ) {
					return $e.data.get( 'form-submissions/referer', { search }, { refresh: true } )
						.then( success )
						.catch( failure );
				},
				data( params ) {
					return {
						search: params.term,
					};
				},
				processResults( { data } ) {
					return {
						results: data.data.map( ( { value, label } ) => ( {
							id: encodeURIComponent( value ),
							text: label,
						} ) ),
					};
				},
				cache: true,
			},
			minimumInputLength: 3,
		} )
		.on( 'select2:select select2:unselect', ( e ) => {
			onChange( e.target.value );
		} );
}

export default function RefererFilter( props ) {
	const [ options, setOptions ] = useState( [
		{ value: '', label: __( 'All Pages', 'elementor-pro' ) },
	] );
	const ref = useRef();

	useEffect( () => {
		let $select2 = null;

		if ( props.value ) {
			$e.data.get( 'form-submissions/referer', { value: props.value }, { refresh: true } )
				.then( ( { data } ) => setOptions( ( prev ) => [ ...prev, ...data.data ] ) )
				.then( () => $select2 = renderSelect2( ref.current, props.onChange ) );
		} else {
			$select2 = renderSelect2( ref.current, props.onChange );
		}

		return () => {
			if ( ! $select2 ) {
				return;
			}

			$select2.select2( 'destroy' ).off( 'select2:select select2:unselect' );
		};
	}, [] );

	return ( <>
		{
			<label htmlFor="filter-by-referer" className="screen-reader-text">
				{ __( 'Filter by Page', 'elementor-pro' ) }
			</label>
		}
		<select
			ref={ ref }
			id="filter-by-referer"
			value={ props.value }
		>
			{ options.map( ( { value, label } ) => {
				return (
					<option key={ value } value={ encodeURIComponent( value ) }> { label } </option>
				);
			} ) }
		</select>
	</> );
}

RefererFilter.propTypes = {
	value: PropTypes.string,
	onChange: PropTypes.func.isRequired,
};

RefererFilter.defaultProps = {
	value: '',
};
