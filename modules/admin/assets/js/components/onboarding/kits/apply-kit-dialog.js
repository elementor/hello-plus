import { DialogModal } from '../../dialog/dialog';
import { __ } from '@wordpress/i18n';

export const ApplyKitDialog = ( { title, startImportProcess, onClose, isLoading } ) => {
	return ( <DialogModal
		// Translators: %s is the kit name.
		title={ __( 'Apply %s?', 'hello-plus' ).replace( '%s', title ) }
		text={ __( 'By applying the kit, you\'ll override any styles, settings or content already on your site.', 'hello-plus' ) }
		approveButtonText={ __( 'Apply All', 'hello-plus' ) }
		approveButtonColor="primary"
		approveButtonOnClick={ () => startImportProcess( true ) }
		onClose={ onClose }
		isLoading={ isLoading }
	/> );
};
