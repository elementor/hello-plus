import Bulk from './bulk';
import * as PropTypes from 'prop-types';

const { useCallback } = React;

export default function Checkbox( props ) {
	const onChange = useCallback( ( e ) => {
		const value = e.target.checked
			? [ ...props.checkedGroup, props.value ]
			: props.checkedGroup.filter( ( checkedItem ) => checkedItem !== props.value );

		props.onChange( value );
	}, [ props.onChange, props.checkedGroup, props.value ] );

	return (
		<input
			type="checkbox"
			checked={ props.checkedGroup.includes( props.value ) }
			onChange={ onChange }
		/>
	);
}

Checkbox.propTypes = {
	checkedGroup: PropTypes.array.isRequired,
	value: PropTypes.number.isRequired,
	onChange: PropTypes.func.isRequired,
};

Checkbox.Bulk = Bulk;
