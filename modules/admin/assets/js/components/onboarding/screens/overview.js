import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Image from '@elementor/ui/Image';
import Box from '@elementor/ui/Box';
import Grid from '@elementor/ui/Grid';
import { PreviewWithImage } from '../../preview/preview-with-image';
import { __ } from '@wordpress/i18n';

export const Overview = ( { title, description, setIsLoading, setPreviewUrl, setIsOverview, pages, kit } ) => {
	return (
		<Stack direction="row" sx={ { height: '100%' } }>
			<Stack
				sx={ {
						flex: '0 0 30%',
						maxWidth: '17rem',
						padding: 3,
						gap: 2,
						overflowY: 'auto',
						borderRight: '1px solid rgba(0, 0, 0, 0.12)',
					} }
				>
				<Typography variant="h6" sx={ { color: 'text.primary' } }>{ title }</Typography>
				<Image
					src={ kit.thumbnail }
					alt={ kit.title }
					sx={ { borderRadius: 1, width: '100%', height: 'auto', mb: 3 } }
					/>
				<Typography variant="body1" sx={ { color: 'text.secondary' } }>{ description }</Typography>
			</Stack>
			<Box
				sx={ {
						flex: '1',
						padding: 3,
						overflowY: 'auto',
					} }
				>
				{ pages.length && (
				<>
					<Typography variant="h6" sx={ { mb: 2, color: 'text.primary' } }>{ __( 'Pages', 'hello-plus' ) }</Typography>
					<Grid container rowSpacing={ 3 } columnSpacing={ 5 }>
						{ pages.map( ( [ id, data ] ) => (
							<Grid key={ id } item xs={ 12 } sm={ 6 } lg={ 3 }>
								<PreviewWithImage { ...data } onClick={ () => {
									setIsLoading( true );
									setPreviewUrl( data.url );
									setIsOverview( false );
								} } />
							</Grid>
						) ) }
					</Grid>
				</>
					) }

			</Box>
		</Stack>
		);
};
