import { DialogModal } from '../../dialog/dialog';
import { __ } from '@wordpress/i18n';
import { useEffect } from 'react';

export default function ConnectDialog( { onError, onClose, onSuccess, connectUrl, pageId } ) {
	useEffect( () => {
		elementorCommon.elements.$window.on( 'elementor/connect/success/cb1', onSuccess );

		return () => {
			elementorCommon.elements.$window.off( 'elementor/connect/success/cb1', onSuccess );
		};
	}, [] );

	return (
		<DialogModal
			title={ __( 'Connect to Template Library', 'elementor' ) }
			text={ __( 'Access this template and our entire library by creating a free personal account', 'elementor' ) }
			approveButtonText={ __( 'Get Started', 'elementor' ) }
			approveButtonUrl={ connectUrl }
			approveButtonOnClick={ () => {

			} }
			approveButtonColor="primary"
			dismissButtonText={ __( 'Cancel', 'elementor' ) }
			dismissButtonOnClick={ () => onClose() }
			onClose={ () => onClose() }
		/>
	);
}
