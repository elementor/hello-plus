import { createRoot } from 'react-dom/client';
import { AdminPage } from './pages/admin-page.js';
import { DashboardProvider } from './providers/dashboard-provider';

const App = () => {
	return (
		<DashboardProvider>
			<AdminPage />
		</DashboardProvider>
	);
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'ehp-admin-home' );

	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
