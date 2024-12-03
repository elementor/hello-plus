import Dialog from '@elementor/ui/Dialog';
import DialogActions from '@elementor/ui/DialogActions';
import DialogContent from '@elementor/ui/DialogContent';
import DialogContentText from '@elementor/ui/DialogContentText';
import DialogHeader from '@elementor/ui/DialogHeader';
import DialogTitle from '@elementor/ui/DialogTitle';
import Button from '@elementor/ui/Button';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';

export const DialogModal = (
	{
		onClose,
		approveButtonText,
		approveButtonOnClick,
		approveButtonUrl,
		title,
		text,
		isLoading,
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
				<DialogTitle><Typography sx={ { color: 'text.secondary' } }>{ title }</Typography></DialogTitle>
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
				<Button disabled={ isLoading } target="_blank" rel="opener" href={ approveButtonUrl } onClick={ approveButtonOnClick } variant="contained">
					{ isLoading ? __( 'Processing…', 'hello-plus' ) : approveButtonText }
				</Button>
			</DialogActions>
		</Dialog>
	);
};
