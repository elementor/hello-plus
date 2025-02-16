import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Box from '@elementor/ui/Box';
import Image from '@elementor/ui/Image';
import EyeIcon from '@elementor/icons/EyeIcon';
import { __ } from '@wordpress/i18n';
import { styled } from '@elementor/ui/styles';

const Container = styled( Stack )( ( { theme } ) => ( {
	border: `1px solid ${ theme.palette.divider }`,
	borderRadius: theme.shape.borderRadius,
} ) );

const Title = styled( Typography )( ( { theme } ) => ( {
	color: theme.palette.text.secondary,
	fontWeight: 500,
	padding: theme.spacing( 1.25 ),
} ) );

const ImageContainer = styled( Box )( ( { theme } ) => ( {
	position: 'relative',
	cursor: 'pointer',
	display: 'flex',
	overflow: 'hidden',
	padding: theme.spacing( 1.25 ),
	borderTop: `1px solid ${ theme.palette.divider }`,
} ) );

const Overlay = styled( Box )( () => ( {
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
} ) );

export const PreviewWithImage = ( { title, thumbnail, onClick } ) => {
	return (
		<Container direction="column">
			<Box sx={ { minHeight: 40 } }>
				<Title variant="body1">{ title }</Title>
			</Box>

			<ImageContainer>
				<Image
					src={ thumbnail }
					alt={ title }
					sx={ {
						width: '100%',
						height: 'auto',
						objectFit: 'cover',
				} } />
				<Overlay
					onClick={ onClick }
				>
					<EyeIcon sx={ { mr: 1 } } />
					<Typography variant="body2" sx={ { color: 'theme.palette.common.white' } }>{ __( 'View Demo', 'hello-plus' ) }</Typography>
				</Overlay>
			</ImageContainer>
		</Container>
	);
};
