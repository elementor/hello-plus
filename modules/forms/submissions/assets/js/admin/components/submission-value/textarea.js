import { basePropTypes } from './index';

const { useState } = React;

export default function Textarea( props ) {
	const [ localValue, setLocalValue ] = useState( props.value );

	return props.isEditMode
		? (
			<textarea
				value={ props.isEditMode ? localValue : props.value }
				rows="4"
				onChange={ ( e ) => {
					const value = e.target.value;

					setLocalValue( value );
					props.onChange( value );
				} }
			/>
		)
		: props.value;
}

Textarea.propTypes = { ...basePropTypes };
