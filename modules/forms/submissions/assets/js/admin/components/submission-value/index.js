import Text from './text';
import Email from './email';
import Tel from './tel';
import Url from './url';
import Radio from './radio';
import Select from './select';
import Checkbox from './checkbox';
import Acceptance from './acceptance';
import Upload from './upload';
import Date from './date';
import Time from './time';
import Textarea from './textarea';

const { useMemo } = React;

const defaultComponent = Text;

const components = Object.entries( {
	Email,
	Tel,
	Url,
	Radio,
	Select,
	Checkbox,
	Acceptance,
	Upload,
	Date,
	Time,
	Textarea,
	Text,
} ).reduce( ( current, [ key, component ] ) => ( {
	...current,
	[ key.toLowerCase() ]: component,
} ), {} );

export const basePropTypes = {
	value: PropTypes.string,
	isEditMode: PropTypes.bool,
	onChange: PropTypes.func.isRequired,
	field: PropTypes.shape( {
		id: PropTypes.string.isRequired,
		type: PropTypes.string,
		options: PropTypes.arrayOf( PropTypes.string ),
		is_multiple: PropTypes.bool,
	} ),
};

export default function SubmissionValue( props ) {
	const Component = useMemo( () => {
		const key = props.field?.type;

		return Object.prototype.hasOwnProperty.call( components, key )
			? components[ key ]
			: defaultComponent;
	}, [ props.field, props.value ] );

	return <Component
		value={ props.value }
		field={ props.field }
		isEditMode={ props.isEditMode }
		onChange={ ( value ) => props.onChange( props.field.id, value ) }
	/>;
}

SubmissionValue.propTypes = { ...basePropTypes };

SubmissionValue.defaultProps = {
	isEditMode: false,
};
