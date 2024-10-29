import { basePropTypes } from './index';
import useFormFieldOptions from '../../hooks/use-form-field-options';

const { useState } = React;

export default function Select( props ) {
	const value = props.field.is_multiple
		? props.value.split( ', ' )
		: props.value;

	const [ localValue, setLocalValue ] = useState( value );

	const options = useFormFieldOptions( props.field.options );

	return (
		<select
			value={ props.isEditMode ? localValue : value }
			multiple={ props.field.is_multiple }
			onChange={ ( e ) => {
				const selectedValues = Array.from( e.target.selectedOptions, ( option ) => option.value );

				setLocalValue( props.field.is_multiple ? selectedValues : selectedValues[ 0 ] );
				props.onChange( selectedValues.join( ', ' ) );
			} }
			disabled={ ! props.isEditMode }
		>
			{
				options.map( ( option ) => (
					<option
						value={ option.value }
						key={ option.value }
					>
						{ option.label }
					</option>
				) )
			}
		</select>
	);
}

Select.propTypes = { ...basePropTypes };
