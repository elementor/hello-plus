import { __ } from '@wordpress/i18n';
import { Box, Stack, Typography } from '@elementor/ui';

const HomeHero = () => {
	return (
		<Stack gap={ 1 }>
			<Box>
				<Typography>{ __( 'Welcome to Hello+', 'hello-plus' ) }</Typography>
			</Box>
			<Box>
				<Typography>
					{ __( 'Here you’ll find links to some site settings that will help you setup and get running as soon as possible.', 'hello-plus' ) 	}
				</Typography>
			</Box>
		</Stack>
  );
};

export default HomeHero;
