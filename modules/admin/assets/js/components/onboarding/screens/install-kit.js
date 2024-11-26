import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import { __ } from '@wordpress/i18n';
import Grid from '@elementor/ui/Grid';
import { PreviewWithImage } from '../../preview/preview-with-image';

export const InstallKit = ( { message, kits = [], setPreviewKit, severity } ) => {
	let md = 3;

	if ( 0 !== kits.length % 4 && 0 === kits.length % 3 ) {
		// if a number is divisble by 3 update to 3
		md = 4;
	}

	return (
		<Stack direction="column" alignItems="center" justifyContent="center" sx={ { overflowY: 'auto' } }>
			<Stack sx={ { maxWidth: 900 } } alignItems="center" justifyContent="center" gap={ 4 }>
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

					<Grid container rowSpacing={ 3 } columnSpacing={ 5 } >
						{ kits.map( ( kit ) => (
							<Grid key={ kit._id } item xs={ 12 } sm={ 6 } md={ md }>
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
