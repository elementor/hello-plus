import Paper from '@elementor/ui/Paper';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';

export const Welcome = () => {
	return (
		<Paper elevation={ 1 } sx={ { px: 4, py: 3 } }>
			<Typography variant="h6">{ __( 'Welcome to Hello Plus', 'hello-plus' ) }</Typography>
			<Typography variant="body2" sx={ { mb: 3 } }>
				{ __( 'Here you’ll find links to some site settings that will help you set up and get running as soon as possible. With Hello+ you’ll find creating your website is a breeze.', 'hello-plus' ) }
			</Typography>
		</Paper>
	);
};
