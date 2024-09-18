import Box from '@elementor/ui/Box';

import { ThemeProvider } from '@elementor/ui/styles';

import { TopBar } from '../components/top-bar/top-bar';
import { GridWithActionLinks } from '../layouts/grids/grid-with-action-links';
import Stack from '@elementor/ui/Stack';
import { QuickLinks } from '../components/papers/quick-links';
import { Welcome } from '../components/papers/welcome';
import { SiteParts } from '../components/papers/site-parts';

export const AdminPage = () => {
	return (
		<ThemeProvider colorScheme="auto">
			<Box className="hello_plus__notices" component="div">
			</Box>
			<TopBar />
			<Box p={ 3 }>
				<GridWithActionLinks>
					<Stack direction="column" gap={ 1 }>
						<Welcome />
						<QuickLinks />
						<SiteParts />
					</Stack>
				</GridWithActionLinks>
			</Box>
		</ThemeProvider>
	);
};
