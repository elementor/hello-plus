/* eslint-disable jsx-a11y/anchor-is-valid */
import {
	NOTICE_TYPE_SUCCESS,
	NOTICE_TYPE_ERROR,
} from '../context/notices-context';

export default function Notice( props ) {
	return (
		<div className={ `notice notice-${ props.model.type } ${ props.model.dismissible ? 'is-dismissible' : '' }` }>
			<p>
				{ props.model.message }
				{
					props.model.undoAction && (
						<>
							&nbsp;
							<a href="#" onClick={ ( e ) => {
								e.preventDefault();

								props.model.undoAction();
							} }>{ __( 'Undo', 'elementor-pro' ) }</a>
						</>
					)
				}
			</p>
			{
				props.model.dismissible && (
					<button type="button" className="notice-dismiss" onClick={ props.dismiss }>
						<span className="screen-reader-text">{ __( 'Dismiss this notice.', 'elementor-pro' ) }</span>
					</button>
				)
			}
		</div>
	);
}

Notice.propTypes = {
	model: PropTypes.shape( {
		key: PropTypes.number.isRequired,
		message: PropTypes.string.isRequired,
		type: PropTypes.oneOf( [ NOTICE_TYPE_SUCCESS, NOTICE_TYPE_ERROR ] ).isRequired,
		dismissible: PropTypes.bool,
		undoAction: PropTypes.func,
	} ),
	dismiss: PropTypes.func,
};
