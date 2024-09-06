import { __ } from '@wordpress/i18n';
import { Box, Stack } from '@elementor/ui';

const HomeHero = () => {
	return (
		<Stack gap={ 1 }>
			<Box>{ __( 'Welcome to Hello+', 'hello-plus' ) }</Box>
			<Box>{ __( 'Here you’ll find links to some site settings that will help you setup and get running as soon as possible.', 'hello-plus' ) }</Box>
		</Stack>
  );
};

export default HomeHero;
