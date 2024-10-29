import * as PropTypes from 'prop-types';

const { useState, useCallback } = React;

export function BulkActionSelect( props ) {
	const [ value, setValue ] = useState( '' ),
		applyAction = useCallback( ( e ) => {
			e.preventDefault();

			const action = props.actions.find( ( item ) => item.value === value );

			if ( ! action ) {
				return;
			}

			action.onApply();
			setValue( '' );
		}, [ value, props.actions ] );

	return (
		<form className={ `actions bulkactions ${ props.className }` } onSubmit={ applyAction }>
			<label
				htmlFor="bulk-action-selector-top"
				className="screen-reader-text"
			>
				{ __( 'Select bulk action', 'elementor-pro' ) }
			</label>
			<select
				name="action"
				value={ value }
				onChange={ ( e ) => setValue( e.target.value ) }
			>
				<option value="" disabled>{ __( 'Bulk actions', 'elementor-pro' ) }</option>
				{
					props.actions.map( ( action ) => {
						return (
							<option key={ action.value } value={ action.value }>
								{ action.label }
							</option>
						);
					} )
				}
			</select>
			<input type="submit" className="button action" value={ __( 'Apply', 'elementor-pro' ) } />
		</form>
	);
}

BulkActionSelect.propTypes = {
	className: PropTypes.string,
	actions: PropTypes.arrayOf(
		PropTypes.shape( {
			label: PropTypes.string.isRequired,
			value: PropTypes.string.isRequired,
			onApply: PropTypes.func.isRequired,
		} ),
	).isRequired,
};

BulkActionSelect.defaultProps = {
	className: '',
};
