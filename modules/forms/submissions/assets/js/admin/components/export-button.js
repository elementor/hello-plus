import {
	EXPORT_MODE_ALL,
	EXPORT_MODE_FILTERED,
	EXPORT_MODE_SELECTED,
} from 'modules/forms/submissions/assets/js/admin/hooks/use-export';

const { useMemo } = React;

const buttonContentOptions = {
	[ EXPORT_MODE_ALL ]: __( 'Export All to CSV', 'elementor-pro' ),
	[ EXPORT_MODE_FILTERED ]: __( 'Export Filtered to CSV', 'elementor-pro' ),
	[ EXPORT_MODE_SELECTED ]: __( 'Export Selected to CSV', 'elementor-pro' ),
};

export default function ExportButton( props ) {
	const ProgressPercentage = useMemo( () => {
		if ( ! props.progress ) {
			return 0;
		}

		const { count, success } = props.progress;

		if ( 0 === count || 0 === success ) {
			return 0;
		}

		return Math.round( success / count * 100 );
	}, [ props.progress ] );

	return (
		<button
			className="button button-primary e-export-button"
			onClick={ () => ! props.disabled && props.onClick() }
			disabled={ props.disabled }
		>
			{
				props.isLoading
					? (
						<>
							<i className="eicon-loading eicon-animation-spin" /> &nbsp;
							<span> { ProgressPercentage }% </span> &nbsp;
							{ __( 'Click to Cancel', 'elementor-pro' ) }
						</>
					)
					: buttonContentOptions[ props.mode ]
			}
		</button>
	);
}

ExportButton.propTypes = {
	onClick: PropTypes.func.isRequired,
	isLoading: PropTypes.bool,
	mode: PropTypes.oneOf( [ EXPORT_MODE_ALL, EXPORT_MODE_SELECTED, EXPORT_MODE_FILTERED ] ),
	disabled: PropTypes.bool,
	progress: PropTypes.shape( {
		count: PropTypes.number,
		success: PropTypes.number,
	} ),
};

ExportButton.defaultProps = {
	isLoading: false,
	hasSelected: false,
	disabled: false,
};
