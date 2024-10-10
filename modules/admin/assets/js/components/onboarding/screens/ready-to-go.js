import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import Box from '@elementor/ui/Box';
import Button from '@elementor/ui/Button';
import { __ } from '@wordpress/i18n';

export const ReadyToGo = ( { message, buttonText, onClick, severity } ) => {
	return (
		<Stack direction="column" alignItems="center" justifyContent="center">
			<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
				<Navigation />
				<Stack alignItems="center" justifyContent="center" gap={ 4 }>
					<Typography variant="h4" align="center" px={ 2 }>
						{ __( 'Congratulations, you’ve created your website!', 'hello-plus' ) }
					</Typography>
					<Typography variant="body1" align="center" px={ 2 } color="text.secondary">
						{
							__(
								'It’s time to make it yours—add your content, style, and personal touch.',
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
	);
};
