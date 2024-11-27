import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Box from '@elementor/ui/Box';
import Image from '@elementor/ui/Image';
import EyeIcon from '@elementor/icons/EyeIcon';
import { __ } from '@wordpress/i18n';

export const PreviewWithImage = ( { title, thumbnail, onClick } ) => {
	return (
		<Stack direction="column" >
			<Typography variant="body2" sx={ { height: 45 } }>{ title }</Typography>
			<Box sx={ {
				position: 'relative',
				cursor: 'pointer',
				boxShadow: 3,
				display: 'flex',
				aspectRatio: '21 / 20',
				overflow: 'hidden',
			} }>
				<Image
					src={ thumbnail }
					alt={ title }
					sx={ {
						borderRadius: 1,
						width: '100%',
						height: 'auto',
						objectFit: 'cover',
				} } />
				<Box
					sx={ {
						position: 'absolute',
						top: 0,
						left: 0,
						width: '100%',
						height: '100%',
						backgroundColor: 'rgba(0, 0, 0, 0.5)',
						color: 'white',
						display: 'flex',
						alignItems: 'center',
						justifyContent: 'center',
						flexDirection: 'column',
						opacity: 0,
						transition: 'opacity 0.3s',
						'&:hover': {
							opacity: 1,
						},
					} }
					onClick={ onClick }
				>
					<EyeIcon sx={ { mr: 1 } } />
					<Typography variant="body2">{ __( 'View Demo', 'hello-plus' ) }</Typography>
				</Box>
			</Box>
		</Stack>
	);
};
