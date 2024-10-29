const { useState, useEffect } = React;

export default function ResourceFilter( props ) {
	const [ localOptions, setLocalOptions ] = useState( [] );

	useEffect( () => {
		$e.data.get( props.resourceOptions.command, props.resourceOptions.args, { refresh: true } )
			.then( ( result ) => setLocalOptions( result.data.data ) );
	}, [] );

	return ( <>
		{
			props.label && <label htmlFor={ `filter-by-${ props.name }` } className="screen-reader-text">
				{ props.label }
			</label>
		}
		<select
			id={ `filter-by-${ props.name }` }
			value={ props.value }
			onChange={ ( e ) => props.onChange( e.target.value ) }
		>
			{ [ ...props.options, ...localOptions ].map( ( { value, label } ) => {
				return (
					<option key={ value } value={ value }> { label } </option>
				);
			} ) }
		</select>
	</> );
}

ResourceFilter.propTypes = {
	value: PropTypes.string,
	onChange: PropTypes.func.isRequired,
	label: PropTypes.string,
	name: PropTypes.string.isRequired,
	options: PropTypes.arrayOf( PropTypes.shape( {
		label: PropTypes.string,
		value: PropTypes.string,
	} ) ),
	resourceOptions: PropTypes.shape( {
		command: PropTypes.string,
		args: PropTypes.object,
	} ),
};

ResourceFilter.defaultProps = {
	value: '',
	options: [],
};
