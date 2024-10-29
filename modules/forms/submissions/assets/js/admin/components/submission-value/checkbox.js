import { basePropTypes } from './index';
import useWatch from '../../hooks/use-watch';
import useFormFieldOptions from '../../hooks/use-form-field-options';

const { useState, useMemo } = React;

export default function Checkbox( props ) {
	const value = useMemo( () => props.value.split( ', ' ), [ props.value ] ),
		[ localValue, setLocalValue ] = useState( value );

	useWatch( () => props.onChange( localValue.join( ', ' ) ), [ localValue ] );

	const options = useFormFieldOptions( props.field.options );

	return options.map( ( option ) => {
		const id = props.field.id + '-' + option.value;

		return (
			<label className="e-form-submissions-value-label" key={ option.value } htmlFor={ id }>
				<input
					id={ id }
					type="checkbox"
					value={ option.value }
					checked={ props.isEditMode ? localValue.includes( option.value ) : value.includes( option.value ) }
					onChange={ ( e ) => {
						const checked = e.target.checked;

						setLocalValue( ( prev ) => {
							if ( ! checked ) {
								prev = prev.filter( ( item ) => item !== option.value );
							} else {
								prev = [ ...prev, option.value ];
							}

							return prev;
						} );
					} }
					disabled={ ! props.isEditMode }
				/>
				{ option.label }
			</label>
		);
	} );
}

Checkbox.propTypes = { ...basePropTypes };
