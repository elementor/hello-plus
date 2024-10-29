import { basePropTypes } from './index';

const { useState, useMemo } = React;

const availableInputTypes = {
	email: 'email',
	date: 'date',
	time: 'time',
	tel: 'tel',
	url: 'url',
	number: 'number',
	text: 'text',
};

const defaultInputType = availableInputTypes.text;

export default function Text( props ) {
	const [ localValue, setLocalValue ] = useState( props.value );

	const inputType = useMemo(
		() => Object.prototype.hasOwnProperty.call( availableInputTypes, props.field?.type )
			? availableInputTypes[ props.field.type ]
			: defaultInputType,
		[ props.field ],
	);

	return props.isEditMode
		? (
			<input
				type={ inputType }
				value={ localValue }
				onChange={ ( { target: { value } } ) => {
					setLocalValue( value );
					props.onChange( value );
				} }
			/>
		)
		: props.children || props.value;
}

Text.propTypes = {
	children: PropTypes.oneOfType( [
		PropTypes.node,
		PropTypes.string,
	] ),
	...basePropTypes,
};
