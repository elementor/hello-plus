import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import Box from '@elementor/ui/Box';
import Button from '@elementor/ui/Button';
import Link from '@elementor/ui/Link';

export const GetStarted = ( { message, buttonText, onClick, severity, title, description, disclaimer, termsUrl, termsText } ) => {
	return (
		<>
			<Stack direction="column" alignItems="center" justifyContent="center">
				<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
					<Navigation />
					<Stack alignItems="center" justifyContent="center" gap={ 4 }>
						<Typography variant="h4" align="center" px={ 2 } sx={ { color: 'text.primary' } }>
							{ title }
						</Typography>
						<Typography variant="body1" align="center" px={ 2 } color="text.secondary">
							{ description }
						</Typography>
						{ message && <Alert severity={ severity }>{ message }</Alert> }
						<Box p={ 1 } mt={ 6 }>
							{ buttonText && <Button variant="contained" onClick={ onClick }>{ buttonText }</Button> }
						</Box>
					</Stack>
				</Stack>
			</Stack>
			<Stack direction="column" alignItems="center" justifyContent="center" sx={ { marginTop: 'auto', pb: 4 } }>
				<Stack direction="row" sx={ { maxWidth: 'fit-content' } } alignItems="center" justifyContent="center">
					<Typography color="text.tertiary" variant="body2" align="center">
						{ disclaimer }
					</Typography>
					<Link variant="body2" color="info.main" ml={ 1 } underline="hover" target="_blank" href={ termsUrl }>
						{ termsText }
					</Link>
				</Stack>
			</Stack>
		</>
	);
};
