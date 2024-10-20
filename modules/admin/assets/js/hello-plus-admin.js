import { createRoot } from 'react-dom/client';
import { AdminPage } from './pages/admin-page.js';
import { AdminProvider } from './providers/admin-provider';
import Container from '@elementor/ui/Container';
import Box from '@elementor/ui/Box';

const App = () => {
	return (
		<AdminProvider>
			<Box sx={ { pr: 1 } }>
				<Container disableGutters={ true } maxWidth="lg" sx={ { display: 'flex', flexDirection: 'column', pt: { xs: 2, md: 6 }, pb: 2 } }>
					<AdminPage />
				</Container>
			</Box>
		</AdminProvider>
	);
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'ehp-admin-home' );

	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
