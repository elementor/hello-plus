import { basePropTypes } from './index';
import Text from './text';

export default function Url( props ) {
	return (
		<Text
			value={ props.value }
			isEditMode={ props.isEditMode }
			onChange={ props.onChange }
			field={ props.field }
		>
			{ props.value && <a href={ props.value } target="_blank" rel="noreferrer">
				{ props.value }
			</a> }
		</Text>
	);
}

Url.propTypes = { ...basePropTypes };
