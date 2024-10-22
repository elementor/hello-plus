import Dialog from '@elementor/ui/Dialog';
import DialogActions from '@elementor/ui/DialogActions';
import DialogContent from '@elementor/ui/DialogContent';
import DialogContentText from '@elementor/ui/DialogContentText';
import DialogHeader from '@elementor/ui/DialogHeader';
import DialogTitle from '@elementor/ui/DialogTitle';
import Button from '@elementor/ui/Button';
import { __ } from '@wordpress/i18n';

export const DialogModal = (
	{
		onClose,
		approveButtonText,
		approveButtonOnClick,
		approveButtonUrl,
		title,
		text,
	},
) => {
	return (
		<Dialog
			open
			onClose={ onClose }
			aria-labelledby="alert-dialog-title"
			aria-describedby="alert-dialog-description"
			sx={ { zIndex: 100001 } }
		>
			<DialogHeader onClose={ () => onClose() }>
				<DialogTitle>{ title }</DialogTitle>
			</DialogHeader>

			<DialogContent >
				<DialogContentText id="alert-dialog-description">
					{ text }
				</DialogContentText>
			</DialogContent>

			<DialogActions>
				<Button onClick={ onClose } color="secondary">
					{ __( 'Cancel', 'hello-plus' ) }
				</Button>
				<Button target="_blank" rel="opener" href={ approveButtonUrl } onClick={ approveButtonOnClick } variant="contained">
					{ approveButtonText }
				</Button>
			</DialogActions>
		</Dialog>
	);
};
