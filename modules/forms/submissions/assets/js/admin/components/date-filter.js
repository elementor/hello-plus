const { useState, useMemo, useCallback } = React;

export default function DateFilter( props ) {
	const [ forceCustomSelect, setForceCustomSelect ] = useState( false );

	// All the options of the select input
	const options = useMemo( () => {
		const format = ( date ) => wp.date.date( 'Y-m-d', date ),
			now = new Date(),
			yesterday = new Date( now ),
			last7Days = new Date( now ),
			last30Days = new Date( now );

		yesterday.setDate( yesterday.getDate() - 1 );
		last7Days.setDate( last7Days.getDate() - 7 );
		last30Days.setDate( last30Days.getDate() - 30 );

		return [
			{
				label: __( 'All Time', 'elementor-pro' ),
				value: 'all',
				filter: { before: null, after: null },
			},
			{
				label: __( 'Today', 'elementor-pro' ),
				value: 'today',
				filter: { before: null, after: format( now ) },
			},
			{
				label: __( 'Yesterday', 'elementor-pro' ),
				value: 'yesterday',
				filter: { before: format( yesterday ), after: format( yesterday ) },
			},
			{
				label: __( 'Last 7 days', 'elementor-pro' ),
				value: 'last7',
				filter: { before: null, after: format( last7Days ) },
			},
			{
				label: __( 'Last 30 days', 'elementor-pro' ),
				value: 'last_30',
				filter: { before: null, after: format( last30Days ) },
			},
			{
				label: __( 'Custom', 'elementor-pro' ),
				value: 'custom',
				filter: { before: null, after: null },
			},
		];
	}, [] );

	// Response to show the selected value of the select.
	const selectedValue = useMemo( () => {
		if ( forceCustomSelect ) {
			return 'custom';
		}

		const selected = options.find( ( option ) => option.filter.after === props.value.after && option.filter.before === props.value.before );

		if ( ! selected ) {
			return 'custom';
		}

		return selected.value;
	}, [ options, props.value, forceCustomSelect ] );

	// On select changed.
	const onSelectChanged = useCallback( ( { target: { value } } ) => {
		const selected = options.find( ( option ) => option.value === value );

		setForceCustomSelect( 'custom' === selected.value );

		props.onChange( selected.filter );
	}, [ options ] );

	// On date inputs changed.
	const onDateInputChanged = useCallback( ( value ) => {
		if ( selectedValue !== 'custom' ) {
			return;
		}

		props.onChange( value );
	}, [ selectedValue ] );

	return ( <>
		{
			props.label && <label htmlFor={ `filter-by-${ props.name }` } className="screen-reader-text">
				{ props.label }
			</label>
		}
		<select
			id={ `filter-by-${ props.name }` }
			value={ selectedValue }
			onChange={ onSelectChanged }
		>
			{ options.map( ( { value, label } ) => {
				return (
					<option key={ value } value={ value }> { label } </option>
				);
			} ) }
		</select>
		{ 'custom' === selectedValue && (
			<>
				<input
					type="date"
					aria-label={ __( 'Start Date', 'elementor-pro' ) }
					value={ props.value.after || '' }
					onChange={ ( { target: { value } } ) => onDateInputChanged( { after: value } ) }
				/>
				&nbsp;
				-
				&nbsp;
				<input
					type="date"
					aria-label={ __( 'End Date', 'elementor-pro' ) }
					value={ props.value.before || '' }
					onChange={ ( { target: { value } } ) => onDateInputChanged( { before: value } ) }
				/>
			</>
		) }

	</> );
}

DateFilter.propTypes = {
	value: PropTypes.shape( {
		after: PropTypes.string,
		before: PropTypes.string,
	} ),
	label: PropTypes.string,
	onChange: PropTypes.func.isRequired,
	name: PropTypes.string.isRequired,
};

DateFilter.defaultProps = {
	value: {
		after: null,
		before: null,
	},
	options: [],
};
