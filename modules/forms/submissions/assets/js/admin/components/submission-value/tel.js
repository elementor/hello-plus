import { basePropTypes } from './index';
import Text from './text';

export default function Tel( props ) {
	return (
		<Text
			value={ props.value }
			isEditMode={ props.isEditMode }
			onChange={ props.onChange }
			field={ props.field }
		>
			{ props.value && <a href={ `tel:${ props.value }` }>{ props.value }</a> }
		</Text>
	);
}

Tel.propTypes = { ...basePropTypes };
