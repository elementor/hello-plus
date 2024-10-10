import Stack from '@elementor/ui/Stack';
import { Navigation } from '../navigation';
import Typography from '@elementor/ui/Typography';
import Alert from '@elementor/ui/Alert';
import { __ } from '@wordpress/i18n';
import Grid from '@elementor/ui/Grid';
import Image from '@elementor/ui/Image';

export const InstallKit = ( { message, kits = [], setPreviewKit, severity } ) => {
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

					<Grid container rowSpacing={ 3 } columnSpacing={ 5 } >
						{ kits.map( ( kit ) => (
							<Grid key={ kit._id } item xs={ 12 } sm={ 6 } md={ 3 }>
								<Stack direction="column" gap={ 2 } >
									<Typography variant="body2" sx={ { height: 40 } }>{ kit.title }</Typography>
									<Image src={ kit.thumbnail } alt={ kit.title } sx={ { cursor: 'pointer', borderRadius: 1 } } onClick={ () => {
										setPreviewKit( 'english-pub-hp' );
									} } />
								</Stack>
							</Grid>
						) ) }
					</Grid>
				</Stack>
			</Stack>
		</Stack>
	);
};
