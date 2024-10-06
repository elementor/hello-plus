import { AdminProvider } from './providers/admin-provider';
import { createRoot } from 'react-dom/client';
import { OnboardingPage } from './pages/onboarding-page';

const App = () => {
	return (
		<AdminProvider>
			<OnboardingPage />
		</AdminProvider>
	);
};

document.addEventListener( 'DOMContentLoaded', () => {
	const container = document.getElementById( 'ehp-admin-onboarding' );

	if ( container ) {
		const root = createRoot( container );
		root.render( <App /> );
	}
} );
