import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';
import Button from '@elementor/ui/Button';
import Paper from '@elementor/ui/Paper';
import Image from '@elementor/ui/Image';
import { Feature } from '../promotions/feature';

export const PromotionLink = ( { image, alt, title, message, button, link, features } ) => {
	return (
		<Paper sx={ { p: 3 } }>
			<Stack direction="column" sx={ { alignItems: 'center', justifyContent: 'center' } }>
				<Image src={ image } alt={ alt } variant="square" sx={ { width: 100, height: 100 } } />
				<Typography sx={ { mt: 1 } } align="center" variant="h6">{ title }</Typography>
				<Typography align="center" variant="body2" >{ message }</Typography>
				<Button sx={ { mt: 2 } } color="promotion" variant="contained" href={ link } target="_blank" rel="noreferrer">{ button }</Button>
			</Stack>

			{ features && (
				<Stack gap={ 1 } sx={ { mt: 4 } }>
					{ features.map( ( feature, i ) => {
					return <Feature key={ i } text={ feature } />;
				} ) }
				</Stack>
				 ) }
		</Paper>
	);
};
