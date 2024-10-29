import { basePropTypes } from './index';
import useFormFieldOptions from '../../hooks/use-form-field-options';

const { useState } = React;

export default function Radio( props ) {
	const [ localValue, setLocalValue ] = useState( props.value );

	const options = useFormFieldOptions( props.field.options );

	return options.map( ( option ) => {
		const id = props.field.id + '-' + option.value;

		return (
			<label className="e-form-submissions-value-label" key={ option.value } htmlFor={ id }>
				<input
					id={ id }
					type="radio"
					value={ option.value }
					checked={ props.isEditMode ? option.value === localValue : option.value === props.value }
					onChange={ () => {
						setLocalValue( option.value );
						props.onChange( option.value );
					} }
					disabled={ ! props.isEditMode }
				/>
				{ option.label }
			</label>
		);
	} );
}

Radio.propTypes = { ...basePropTypes };
