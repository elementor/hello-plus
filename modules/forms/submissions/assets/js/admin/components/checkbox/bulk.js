import * as PropTypes from 'prop-types';

const { useCallback } = React;

export default function Bulk( props ) {
	const onChange = useCallback( ( e ) => {
		const value = e.target.checked ? [ ...props.allValues ] : [];

		props.onChange( value );
	}, [ props.onChange, props.checkedGroup, props.allValues ] );

	return (
		<input
			type="checkbox"
			checked={ props.checkedGroup.length === props.allValues.length && props.allValues.length > 0 }
			onChange={ onChange }
		/>
	);
}

Bulk.propTypes = {
	checkedGroup: PropTypes.array.isRequired,
	allValues: PropTypes.array.isRequired,
	onChange: PropTypes.func.isRequired,
};
