import { DialogModal } from '../../dialog/dialog';
import { __ } from '@wordpress/i18n';
import { useEffect } from 'react';

export default function ConnectDialog( { onClose, onSuccess, connectUrl } ) {
	useEffect( () => {
		elementorCommon.elements.$window.on( 'elementor/connect/success/cb1', onSuccess );

		return () => {
			elementorCommon.elements.$window.off( 'elementor/connect/success/cb1', onSuccess );
		};
	}, [ onSuccess ] );

	return (
		<DialogModal
			title={ __( 'Connect to Template Library', 'hello-plus' ) }
			text={ __( 'Access this template and our entire library by creating a free personal account', 'hello-plus' ) }
			approveButtonText={ __( 'Get Started', 'hello-plus' ) }
			approveButtonUrl={ connectUrl }
			approveButtonOnClick={ () => {

			} }
			approveButtonColor="primary"
			dismissButtonText={ __( 'Cancel', 'hello-plus' ) }
			dismissButtonOnClick={ () => onClose() }
			onClose={ () => onClose() }
		/>
	);
}
