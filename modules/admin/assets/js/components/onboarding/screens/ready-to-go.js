import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Button from '@elementor/ui/Button';
import { __ } from '@wordpress/i18n';

export const ReadyToGo = ( { modalCloseRedirectUrl } ) => {
	return (
		<Stack direction="column" alignItems="center" justifyContent="center">
			<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
				<Navigation />
				<Stack alignItems="center" justifyContent="center" gap={ 4 }>
					<Typography variant="h4" align="center" px={ 2 } sx={ { color: 'text.primary' } }>
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
					<Stack direction="row" gap={ 1 } mt={ 5 }>
						<Button variant="outlined" color="secondary" onClick={ () => {
							window.location.href = '/';
						} }>
							{ __( 'View my site', 'hello-plus' ) }
						</Button>
						<Button variant="contained" color="primary" onClick={ () => {
							window.location.href = modalCloseRedirectUrl;
						} }>
							{ __( 'Customize my site', 'hello-plus' ) }
						</Button>
					</Stack>
				</Stack>
			</Stack>
		</Stack>
	);
};
