import { basePropTypes } from './index';
import Text from './text';
import { formatToLocalDate } from '../../utils/date';

export default function Date( props ) {
	return (
		<Text
			value={ props.value }
			isEditMode={ props.isEditMode }
			onChange={ props.onChange }
			field={ props.field }
		>
			{ props.value && formatToLocalDate( props.value ) }
		</Text>
	);
}

Date.propTypes = { ...basePropTypes };
