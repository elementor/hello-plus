import { createHistory, LocationProvider, Router } from '@reach/router';
import { NoticesProvider } from './context/notices-context';
import { SettingsProvider } from './context/settings-context';
import Notices from './components/notices';
import Item from './pages/item';
import Index from './pages';
import { createHashSource } from 'reach-router-hash-history';

const history = createHistory( createHashSource() );

export default function App() {
	return (
		<div id="elementor-form-submissions">
			<SettingsProvider value={ window.elementorSubmissionsConfig }>
				<NoticesProvider>
					<Notices />
					<LocationProvider history={ history }>
						<Router>
							<Index path="/" />
							<Item path="/:id" />
						</Router>
					</LocationProvider>
				</NoticesProvider>
			</SettingsProvider>
		</div>
	);
}
