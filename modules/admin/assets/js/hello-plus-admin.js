import { createRoot } from 'react-dom/client';
import { SettingsPage } from './pages/settings-page.js';

const App = () => {
	return <SettingsPage />;
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'hello-plus-dashboard' );

	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
