import Box from '@elementor/ui/Box';
import { ThemeProvider } from '@elementor/ui/styles';
import { __ } from '@wordpress/i18n';
import { useCallback, useState } from 'react';

import { useAdminContext } from '../hooks/use-admin-context';
import Modal from '@elementor/ui/Modal';

import { TopBarContent } from '../components/top-bar/top-bar-content';
import { GetStarted } from '../components/onboarding/screens/get-started';
import Spinner from '../components/spinner/spinner';
import { InstallKit } from '../components/onboarding/screens/install-kit';
import { ReadyToGo } from '../components/onboarding/screens/ready-to-go';
import { Preview } from '../components/onboarding/screens/preview';

const style = {
	position: 'fixed',
	top: 0,
	left: 0,
	width: '100%',
	height: '100%',
	background: 'white',
	boxShadow: 24,
	p: 4,
};

export const OnboardingPage = () => {
	const [ message, setMessage ] = useState( '' );
	const [ severity, setSeverity ] = useState( 'info' );
	const [ previewKit, setPreviewKit ] = useState( null );
	const [ allowTracking, setAllowTracking ] = useState( true );

	const {
		isLoading,
		setIsLoading,
		stepAction,
		buttonText,
		step,
		onboardingSettings: { nonce, modalCloseRedirectUrl, kits } = {},
	} = useAdminContext();

	const onClick = useCallback( async () => {
		setMessage( '' );

		const data = {
			step: stepAction,
			_wpnonce: nonce,
			slug: 'elementor',
			allowTracking,
		};

		setIsLoading( true );

		try {
			switch ( stepAction ) {
				case 'install-elementor':
					setMessage( __( 'Installing Elementor plugin…', 'hello-plus' ) );
					const response = await wp.ajax.post( 'hello_plus_setup_wizard', data );

					if ( response.activateUrl ) {
						setMessage( __( 'Activating Elementor plugin…', 'hello-plus' ) );
						const activate = await fetch( response.activateUrl );

						if ( activate.ok ) {
							setSeverity( 'success' );
							setMessage( __( 'Elementor plugin has been installed and activated' ) );
						} else {
							throw new Error( __( 'Failed to activate Elementor plugin', 'hello-plus' ) );
						}
					}
					window.location.reload();
					break;
				case 'activate-elementor':
					setMessage( __( 'Activating Elementor plugin…', 'hello-plus' ) );
					await wp.ajax.post( 'hello_plus_setup_wizard', data );
					data.slug = 'elementor';
					window.location.reload();
					break;
				case 'install-kit':
					break;
			}
		} catch ( error ) {
			setMessage( error.errorMessage );
			setSeverity( 'error' );
		} finally {
			setIsLoading( false );
		}
	}, [ allowTracking, nonce, setIsLoading, stepAction ] );

	const onClose = () => {
		window.location.href = modalCloseRedirectUrl;
	};

	return (
		<ThemeProvider colorScheme="auto">
			<Modal open sx={ { zIndex: 100000 } } >
				<Box style={ { ...style, display: 'flex', flexDirection: 'column', py: 4 } }>
					{ ! previewKit && ( <TopBarContent onClose={ onClose } sx={ { borderBottom: '1px solid var(--divider-divider, rgba(0, 0, 0, 0.12))', mb: 4 } } iconSize="small" /> ) }
					{ 0 === step && ! isLoading && ! previewKit && (
						<GetStarted allowTracking={ allowTracking } setAllowTracking={ setAllowTracking } severity={ severity } message={ message } buttonText={ buttonText } onClick={ onClick } />
					) }
					{ 1 === step && ! isLoading && ! previewKit && ( <InstallKit setPreviewKit={ setPreviewKit } severity={ severity } message={ message } onClick={ onClick } kits={ kits } /> ) }
					{ 2 === step && ! isLoading && ! previewKit && ( <ReadyToGo modalCloseRedirectUrl={ modalCloseRedirectUrl } /> ) }
					{ previewKit && <Preview kit={ previewKit } setPreviewKit={ setPreviewKit } /> }
					{ isLoading && <Spinner /> }
				</Box>
			</Modal>
		</ThemeProvider>
	);
};
