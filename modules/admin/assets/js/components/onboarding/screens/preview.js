import Stack from '@elementor/ui/Stack';
import Box from '@elementor/ui/Box';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';
import ChevronRightIcon from '@elementor/icons/ChevronRightIcon';
import Button from '@elementor/ui/Button';

export const Preview = ( { slug, title, setPreviewKit, setStep } ) => {
	return (
		<>
			<Stack direction="row" sx={ { alignItems: 'center', height: 50, px: 2, backgroundColor: 'background.default', justifyContent: 'space-between' } }>
				<Stack
					direction="row"
					spacing={ 1 }
					alignItems="center"
					sx={ { borderRight: '1px solid var(--divider-divider, rgba(0, 0, 0, 0.12))', width: 'fit-content', p: 2, cursor: 'pointer' } }
					onClick={ () => setPreviewKit( '' ) }
				>
					<ChevronRightIcon sx={ { transform: 'rotate(180deg)' } } color="action" />
					<Typography variant="subtitle1" color="action">{ __( 'Back to Wizard', 'hello-plus' ) }</Typography>
				</Stack>
				<Stack direction="row" gap={ 1 }>
					<Button variant="outlined" color="primary">
						{ __( 'Overview', 'hello-plus' ) }
					</Button>
					<Button variant="contained" color="primary" onClick={ () => {
						setPreviewKit( '' );
						setStep( 2 );
					} }>
						{ __( 'Apply Kit', 'hello-plus' ) }
					</Button>
				</Stack>
			</Stack>
			<Box sx={ { position: 'relative', width: '100%', height: '100%' } }>
				<iframe
					src={ `https://library.elementor.com/${ slug }/` }
					style={ { position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', border: 'none' } }
					title={ title }
				/>
			</Box>
		</>

	);
};
