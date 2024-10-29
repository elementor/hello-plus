import PostBox from './post-box';
import { formatToLocalDateTime } from '../utils/date';
import noticeMessages from '../notice-messages';

export default function FormActionsLog( props ) {
	return (
		<PostBox
			header={ <h2>{ __( 'Actions Log', 'elementor-pro' ) }</h2> }
		>
			{ 0 === props.actions.length && (
				<div className="inside">
					<p style={ { margin: 0 } }> { __( 'No form actions.', 'elementor-pro' ) } </p>
				</div>
			) }
			{ props.actions.map( ( actionLog ) => {
				return (
					<div className={ `inside e-form-submissions-action-log e-form-submissions-action-log--${
						'success' === actionLog.status ? 'success' : 'error' }`
					} key={ actionLog.name }>
						<p>
							<strong className="e-form-submissions-action-log__label"> { actionLog.label } </strong>
							<i className={ `${
								'success' === actionLog.status
									? 'eicon-success eicon-check-circle-o'
									: 'eicon-error eicon-warning'
							} e-form-submissions-action-log__icon` } />
							<span className="e-form-submissions-action-log__date"> { formatToLocalDateTime( actionLog.created_at ) } </span>
						</p>
						<p className={ `e-form-submissions-action-log__message` }>
							{ actionLog.log || noticeMessages.actionLogs[ actionLog.status ]() }
						</p>
					</div>
				);
			} ) }
		</PostBox>
	);
}

FormActionsLog.propTypes = {
	actions: PropTypes.arrayOf( PropTypes.shape( {
		status: PropTypes.oneOf( [ 'success', 'failed' ] ),
		name: PropTypes.string,
		label: PropTypes.string,
		created_at: PropTypes.string,
		log: PropTypes.string,
	} ) ).isRequired,
};

