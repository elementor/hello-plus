/* eslint-disable jsx-a11y/anchor-is-valid */
const { useCallback, useMemo } = React;

export default function LinksFilter( props ) {
	const onChange = useCallback( ( e, value ) => {
		e.preventDefault();

		if ( ! props.onChange ) {
			return;
		}

		props.onChange( value );
	}, [] );

	const options = useMemo( () => {
		return props.options.filter( ( option ) => option.shouldShow );
	}, [ props.options ] );

	return (
		<ul className="subsubsub">
			{ options.map( ( option, index ) => {
				const isLast = index + 1 === options.length;

				return (
					<li key={ option.value }>
						&nbsp;
						<a
							href="#"
							className={ option.value === props.value ? 'current' : '' }
							onClick={ ( e ) => onChange( e, option.value ) }
						>
							{ option.label }
							{ undefined !== option.count && <span className="count"> ({ option.count })</span> }
						</a>
						&nbsp;
						{ isLast ? '' : '|' }
					</li>
				);
			} ) }
		</ul>
	);
}

LinksFilter.propTypes = {
	options: PropTypes.arrayOf(
		PropTypes.shape( {
			value: PropTypes.string,
			label: PropTypes.string,
			count: PropTypes.number,
			shouldShow: PropTypes.bool,
		} ),
	),
	value: PropTypes.string,
	onChange: PropTypes.func,
};
