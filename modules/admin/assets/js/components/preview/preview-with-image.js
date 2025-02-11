import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Box from '@elementor/ui/Box';
import Image from '@elementor/ui/Image';
import EyeIcon from '@elementor/icons/EyeIcon';
import { __ } from '@wordpress/i18n';

export const PreviewWithImage = ( { title, thumbnail, onClick } ) => {
	return (
		<Stack direction="column" sx={ { border: '1px solid var(--e-a-border-color)', borderRadius: 1 } } >
			<Box sx={ { minHeight: 40 } }>
				<Typography variant="body1" sx={ { color: 'text.secondary', fontWeight: 500, p: 1.25 } }>{ title }</Typography>
			</Box>

			<Box sx={ {
				position: 'relative',
				cursor: 'pointer',
				display: 'flex',
				aspectRatio: '1',
				overflow: 'hidden',
				p: 1.25,
				borderTop: '1px solid var(--e-a-border-color)',
			} }>
				<Image
					src={ thumbnail }
					alt={ title }
					sx={ {
						width: '100%',
						height: 'auto',
						objectFit: 'cover',
				} } />
				<Box
					sx={ {
						position: 'absolute',
						top: 10,
						left: 10,
						width: 'calc(100% - 20px)',
						height: 'calc(100% - 20px)',
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
					<Typography variant="body2" sx={ { color: 'theme.palette.common.white' } }>{ __( 'View Demo', 'hello-plus' ) }</Typography>
				</Box>
			</Box>
		</Stack>
	);
};
