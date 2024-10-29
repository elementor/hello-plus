import { basePropTypes } from './index';
import Text from './text';

export default function Email( props ) {
	return (
		<Text
			value={ props.value }
			isEditMode={ props.isEditMode }
			onChange={ props.onChange }
			field={ props.field }
		>
			{ props.value && <a href={ `mailto:${ props.value }` }>{ props.value }</a> }
		</Text>
	);
}

Email.propTypes = { ...basePropTypes };
