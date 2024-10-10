import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import { __ } from '@wordpress/i18n';

export const InstallKit = ( { message, buttonText, onClick, severity } ) => {
	return (
		<Stack direction="column" alignItems="center" justifyContent="center">
			<Stack sx={ { maxWidth: 662 } } alignItems="center" justifyContent="center" gap={ 4 }>
				<Navigation />
				<Stack alignItems="center" justifyContent="center" gap={ 4 }>
					<Typography variant="h4" align="center" px={ 2 }>
						{ __( 'Choose your website template kit', 'hello-plus' ) }
					</Typography>
					<Typography variant="body1" align="center" px={ 2 } color="text.secondary">
						{
							__(
								'Explore our versatile website kits to find one that fits your style or project.',
								'hello-plus',
							)
						}
					</Typography>
					{ message && <Alert severity={ severity }>{ message }</Alert> }
				</Stack>
			</Stack>
		</Stack>
	);
};
