import { sprintf, _n } from '@wordpress/i18n';

const generalError = () => __( 'Something went wrong, please try again later.', 'elementor-pro' );

export default {
	trashed: {
		success: ( count = 1 ) => sprintf(
			_n( '%d submission moved to Trash.', '%d submissions moved to Trash.', count, 'elementor-pro' ),
			count,
		),
		error: generalError,
	},
	deleted: {
		success: ( count = 1 ) => sprintf(
			_n( '%d submission permanently deleted.', '%d submissions permanently deleted.', count, 'elementor-pro' ),
			count,
		),
		error: generalError,
	},
	updated: {
		success: ( count = 1 ) => sprintf(
			_n( 'Submission has been successfully updated.', '%d submissions have been successfully updated.', count, 'elementor-pro' ),
			count,
		),
		error: generalError,
	},
	restored: {
		success: ( count = 1 ) => sprintf(
			_n( '%d submission restored from Trash.', '%d submissions restored from Trash.', count, 'elementor-pro' ),
			count,
		),
		error: generalError,
	},
	markedAsRead: {
		success: () => null,
		error: generalError,
	},
	markedAsUnread: {
		success: () => null,
		error: generalError,
	},
	actionLogs: {
		success: () => __( 'Action completed successfully.', 'elementor-pro' ),
		failed: () => __( 'Action failed to run, please check your integration.', 'elementor-pro' ),
	},
};
