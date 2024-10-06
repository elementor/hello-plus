import Button from '@elementor/ui/Button';
import Box from '@elementor/ui/Box';
import { ThemeProvider } from '@elementor/ui/styles';
import { __ } from '@wordpress/i18n';
import { useCallback, useState } from 'react';
import Alert from '@elementor/ui/Alert';
import { useAdminContext } from '../hooks/use-admin-context';
import { useGetCurrentStep } from '../hooks/use-get-current-step';
import { Navigation } from '../components/onboarding/navigation';
import { TwoCol } from '../layouts/two-col';
import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Modal from '@elementor/ui/Modal';
import { TopBar } from '../components/top-bar/top-bar';
import { TopBarLinks } from '../components/top-bar/top-bar-links';
import { TopBarContent } from '../components/top-bar/top-bar-content';

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

	const { onboardingSettings: { nonce } = {} } = useAdminContext();
	const { step, buttonText } = useGetCurrentStep();

	const onClick = useCallback( async () => {
		setMessage( '' );

		const data = {
			step,
			_wpnonce: nonce,
			slug: 'elementor',
		};

		try {
			switch ( step ) {
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
	}, [ nonce, step ] );

	return (
		<ThemeProvider colorScheme="auto">
			<Modal open={ open } sx={ { zIndex: 100000 } } >
				<Box style={ style }>
					<TopBarContent />
					<Navigation />
					<TwoCol>
						<Stack>
							<Box>
								<Typography variant="h6">
									{ __( 'Welcome! Let’s create your website.', 'hello-plus' ) }
								</Typography>
								<Typography variant="body1">
									{ __( 'Welcome! Let’s create your website.', 'hello-plus' ) }
								</Typography>
							</Box>
							{ message && <Alert severity={ severity }>{ message }</Alert> }
							<Box p={ 1 }>
								{ buttonText && <Button onClick={ onClick }>{ buttonText }</Button> }
							</Box>
						</Stack>
						<Stack />
					</TwoCol>
				</Box>
			</Modal>

		</ThemeProvider>
	);
};
