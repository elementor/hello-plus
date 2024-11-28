import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import Box from '@elementor/ui/Box';
import Button from '@elementor/ui/Button';
import Checkbox from '@elementor/ui/Checkbox';
import Link from '@elementor/ui/Link';
import { __ } from '@wordpress/i18n';

export const GetStarted = ( { message, buttonText, onClick, severity, allowTracking, setAllowTracking } ) => {
	return (
		<>
			<Stack direction="column" alignItems="center" justifyContent="center">
				<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
					<Navigation />
					<Stack alignItems="center" justifyContent="center" gap={ 4 }>
						<Typography variant="h4" align="center" px={ 2 }>
							{ __( 'Welcome! Let’s create your website.', 'hello-plus' ) }
						</Typography>
						<Typography variant="body1" align="center" px={ 2 } color="text.secondary">
							{
								__(
									'Thanks for installing the Hello Biz theme by Elementor. This setup wizard will help you create a website in moments.',
									'hello-plus',
								)
							}
						</Typography>
						{ message && <Alert severity={ severity }>{ message }</Alert> }
						<Box p={ 1 } mt={ 6 }>
							{ buttonText && <Button variant="contained" onClick={ onClick }>{ buttonText }</Button> }
						</Box>
					</Stack>
				</Stack>
			</Stack>
			<Stack direction="column" alignItems="center" justifyContent="center" sx={ { marginTop: 'auto', pb: 4 } }>
				<Stack sx={ { maxWidth: 'fit-content' } } alignItems="center" justifyContent="center">
					<Stack direction="row" alignItems="center" justifyContent="center" >
						<Checkbox sx={ { p: 0.5 } } size="small" checked={ allowTracking } onClick={ () => setAllowTracking( ! allowTracking ) } color="default" />
						<Typography variant="body1" align="center" color="text.tertiary">
							{ __( 'Become a super contributor by sharing non-sensitive plugin data to help us improve the tools you use every day.', 'hello-plus' ) }
						</Typography>
						<Link variant="body1" color="info.main" ml={ 1 } underline="hover" target="_blank" href="https://elementor.com/help/share-usage-data/">
							{ __( 'Learn more', 'hello-plus' ) }
						</Link>
					</Stack>
					<Stack direction="row" alignItems="center" justifyContent="center" >
						<Typography color="text.tertiary" variant="body1" align="center">
							{ __( 'By clicking "Start building my website", I agree to install & activate the Elementor plugin. I accept the Elementor.', 'hello-plus' ) }
						</Typography>
						<Link variant="body1" color="info.main" ml={ 1 } underline="hover" target="_blank" href="https://elementor.com/terms/">
							{ __( 'Terms and Conditions', 'hello-plus' ) }
						</Link>
					</Stack>
				</Stack>
			</Stack>
		</>

	);
};
