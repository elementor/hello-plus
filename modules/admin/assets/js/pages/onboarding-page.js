import Box from '@elementor/ui/Box';
import { ThemeProvider } from '@elementor/ui/styles';
import { __ } from '@wordpress/i18n';
import { useCallback, useState } from 'react';
import { useAdminContext } from '../hooks/use-admin-context';
import { useGetCurrentStep } from '../hooks/use-get-current-step';
import Modal from '@elementor/ui/Modal';

import { TopBarContent } from '../components/top-bar/top-bar-content';
import { GetStarted } from '../components/onboarding/screens/get-started';
import Spinner from '../components/spinner/spinner';
import { InstallKit } from '../components/onboarding/screens/install-kit';
import { ReadyToGo } from '../components/onboarding/screens/ready-to-go';

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
	const [ open, setOpen ] = useState( true );
	const [ loading, setLoading ] = useState( false );

	const { onboardingSettings: { nonce, modalCloseRedirectUrl } = {} } = useAdminContext();
	const { stepAction, buttonText, step } = useGetCurrentStep();

	const onClick = useCallback( async () => {
		setMessage( '' );

		const data = {
			step: stepAction,
			_wpnonce: nonce,
			slug: 'elementor',
		};

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
						}
					}
					break;
				case 'activate-elementor':
					setMessage( __( 'Activating Elementor plugin…', 'hello-plus' ) );
					await wp.ajax.post( 'hello_plus_setup_wizard', data );
					setMessage( __( 'Elementor plugin has been activated.' ) );
					data.slug = 'elementor';
					break;
				case 'install-kit':
					break;
			}
		} catch ( error ) {
			setMessage( error.errorMessage );
			setSeverity( 'error' );
		}
	}, [ nonce, stepAction ] );

	const onClose = () => {
		window.location.href = modalCloseRedirectUrl;
	};

	return (
		<ThemeProvider colorScheme="auto">
			<Modal open={ open } sx={ { zIndex: 100000 } } >
				<Box style={ { ...style, display: 'flex', flexDirection: 'column' } }>
					<TopBarContent onClose={ onClose } sx={ { borderBottom: '1px solid var(--divider-divider, rgba(0, 0, 0, 0.12))', mb: 4 } } iconSize="small" />
					{ 0 === step && ! loading && ( <GetStarted severity={ severity } message={ message } buttonText={ buttonText } onClick={ onClick } /> ) }
					{ 1 === step && ! loading && ( <InstallKit severity={ severity } message={ message } onClick={ onClick } /> ) }
					{ 2 === step && ! loading && ( <ReadyToGo severity={ severity } message={ message } onClick={ onClick } /> ) }
					{ loading && <Spinner /> }
				</Box>
			</Modal>

		</ThemeProvider>
	);
};
