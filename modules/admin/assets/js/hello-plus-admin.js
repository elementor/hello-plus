import { createRoot } from 'react-dom/client';
import { DashboardPage } from './pages/dashboard-page.js';

const App = () => {
	return <DashboardPage />;
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'hello-plus-dashboard' );
	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
