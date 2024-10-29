import { basePropTypes } from './index';
import Text from './text';
import { formatToLocalTime } from '../../utils/date';

export default function Time( props ) {
	return (
		<Text
			value={ props.value }
			isEditMode={ props.isEditMode }
			onChange={ props.onChange }
			field={ props.field }
		>
			{ props.value && formatToLocalTime( `2000-01-01 ${ props.value }` ) }
		</Text>
	);
}

Time.propTypes = { ...basePropTypes };
