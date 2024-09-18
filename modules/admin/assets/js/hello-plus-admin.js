import { createRoot } from 'react-dom/client';
import { AdminPage } from './pages/admin-page.js';
import { AdminProvider } from './providers/admin-provider';

const App = () => {
	return (
		<AdminProvider>
			<AdminPage />
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
