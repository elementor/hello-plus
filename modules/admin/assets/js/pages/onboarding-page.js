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

export const OnboardingPage = () => {
	const [ message, setMessage ] = useState( '' );
	const [ severity, setSeverity ] = useState( 'info' );
	const [ previewKit, setPreviewKit ] = useState( null );

	const {
		isLoading,
		setIsLoading,
		stepAction,
		step,
		onboardingSettings: { nonce, modalCloseRedirectUrl, kits, getStartedText = {}, readyToGoText = {}, installKitText = {} } = {},
	} = useAdminContext();

	const {
		title = '',
		description = '',
		disclaimer = '',
		termsUrl = '',
		termsText = '',
		buttonText: getStartedButtonText = '',
	} = getStartedText;

	const {
		title: readyTitle = '',
		description: readyDescription = '',
		viewSite = '',
		customizeSite = '',
	} = readyToGoText;

	const {
		title: kitTitle = '',
		description: kitDescription = '',
	} = installKitText;

	const onClick = useCallback( async () => {
		setMessage( '' );

		const data = {
			step: stepAction,
			_wpnonce: nonce,
			slug: 'elementor',
		};

		setIsLoading( true );

		try {
			switch ( stepAction ) {
				case 'install-elementor':
					const response = await wp.ajax.post( 'helloplus_setup_wizard', data );

					if ( response.activateUrl ) {
						const activate = await fetch( response.activateUrl );

						if ( activate.ok ) {
							window.location.reload();
						} else {
							throw new Error( __( 'Failed to activate Elementor plugin', 'hello-plus' ) );
						}
					}
					break;
				case 'activate-elementor':
					await wp.ajax.post( 'helloplus_setup_wizard', data );

					window.location.reload();
					break;
				default:
					break;
			}
		} catch ( error ) {
			setMessage( error.errorMessage );
			setSeverity( 'error' );
		} finally {
			setIsLoading( false );
		}
	}, [ nonce, setIsLoading, stepAction ] );

	const onClose = () => {
		window.location.href = modalCloseRedirectUrl;
	};

	return (
		<ThemeProvider colorScheme="auto">
			<Modal open sx={ { zIndex: 100000 } } >
				<Box sx={ {
						backgroundColor: 'background.default',
						position: 'fixed',
						top: 0,
						left: 0,
						width: '100%',
						height: '100%',
						boxShadow: 24,
						display: 'flex',
						flexDirection: 'column',
					} }>
					{ ! previewKit && ( <TopBarContent onClose={ onClose } sx={ { borderBottom: '1px solid var(--divider-divider, rgba(0, 0, 0, 0.12))', mb: 4 } } iconSize="small" /> ) }
					{ 0 === step && ! isLoading && ! previewKit && (
						<GetStarted
							severity={ severity }
							message={ message }
							buttonText={ getStartedButtonText }
							onClick={ onClick }
							title={ title }
							description={ description }
							disclaimer={ disclaimer }
							termsUrl={ termsUrl }
							termsText={ termsText }
						/>
					) }
					{ 1 === step && ! isLoading && ! previewKit && (
						<InstallKit
							setPreviewKit={ setPreviewKit }
							severity={ severity }
							message={ message }
							kits={ kits }
							title={ kitTitle }
							description={ kitDescription }
						/>
					) }
					{ 2 === step && ! isLoading && ! previewKit && (
						<ReadyToGo
							modalCloseRedirectUrl={ modalCloseRedirectUrl }
							title={ readyTitle }
							description={ readyDescription }
							viewSite={ viewSite }
							customizeSite={ customizeSite }
						/>
					) }
					{ previewKit && <Preview kit={ previewKit } setPreviewKit={ setPreviewKit } /> }
					{ isLoading && <Spinner /> }
				</Box>
			</Modal>
		</ThemeProvider>
	);
};
