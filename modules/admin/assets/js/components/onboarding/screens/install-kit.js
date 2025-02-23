import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import { __ } from '@wordpress/i18n';
import Grid from '@elementor/ui/Grid';
import { PreviewWithImage } from '../../preview/preview-with-image';

export const InstallKit = ( { message, kits = [], setPreviewKit, severity } ) => {
	return (
		<Stack direction="column" alignItems="center" pb={ 4 } sx={ { overflowY: 'auto' } }>
			<Stack sx={ { maxWidth: 900 } } alignItems="center" justifyContent="center" gap={ 4 }>
				<Navigation />
				<Stack alignItems="center" justifyContent="center" gap={ 4 }>
					<Typography variant="h4" align="center" px={ 2 } sx={ { color: 'text.primary' } }>
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

					<Grid container rowSpacing={ 3 } columnSpacing={ 5 } >
						{ kits.map( ( kit ) => (
							<Grid key={ kit._id } item xs={ 12 } sm={ 6 } md={ 4 }>
								<PreviewWithImage { ...kit } onClick={ () => {
									setPreviewKit( kit );
								} } />
							</Grid>
						) ) }
					</Grid>
				</Stack>
			</Stack>
		</Stack>
	);
};
