import { __ } from '@wordpress/i18n';
import { Box } from '@elementor/ui/Box';
import { Stack } from '@elementor/ui/Stack';
import { Typography } from '@elementor/ui/Typography';

const HomeHero = () => {
	return (
		<Stack gap={ 1.5 } mb={ 2 }>
			<Box>
				<Typography variant="h3">{ __( 'Welcome to Hello+', 'hello-plus' ) }</Typography>
			</Box>
			<Box>
				<Typography variant="h6">
					{ __( 'Here you’ll find links to some site settings that will help you setup and get running as soon as possible.', 'hello-plus' ) 	}
				</Typography>
			</Box>
		</Stack>
  );
};

export default HomeHero;
