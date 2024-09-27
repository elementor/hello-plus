import { AdminProvider } from './providers/admin-provider';
import { createRoot } from 'react-dom/client';
import { TopBar } from './components/top-bar/top-bar';

const App = () => {
	return (
		<AdminProvider>
			<TopBar />
		</AdminProvider>
	);
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'ehp-admin-top-bar-root' );

	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
