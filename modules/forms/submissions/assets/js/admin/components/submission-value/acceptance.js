import { basePropTypes } from './index';

const { useState } = React;

export default function Acceptance( props ) {
	const [ localValue, setLocalValue ] = useState( props.value ),
		id = props.field.id + '-' + props.value;

	return (
		// eslint-disable-next-line jsx-a11y/label-has-associated-control
		<label className="e-form-submissions-value-label" htmlFor={ id }>
			<input
				id={ id }
				type="checkbox"
				value="on"
				checked={ props.isEditMode ? 'on' === localValue : 'on' === props.value }
				onChange={ ( e ) => {
					const value = e.target.checked ? 'on' : '';

					setLocalValue( value );
					props.onChange( value );
				} }
				disabled={ ! props.isEditMode }
			/>
		</label>
	);
}

Acceptance.propTypes = { ...basePropTypes };
