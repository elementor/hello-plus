import { Stack, Typography, Button } from '@elementor/ui';

export const PromotionLink = ( { image, alt, title, message, button, link } ) => {
	return (
		<Stack direction="column" sx={ { alignItems: 'center', justifyContent: 'center', marginBlockStart: 2, marginBlockEnd: 1 } }>
			<img src={ image } alt={ alt } />
			<Typography align="center" fontWeight="bold">{ title }</Typography>
			<Typography align="center" >{ message }</Typography>
			<Button href={ link } target="_blank" rel="noreferrer">{ button }</Button>
		</Stack>
	);
};
