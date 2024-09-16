import { __ } from '@wordpress/i18n';
// TODO: minimize to only necessary imports
import { Container, Box, Tabs, Tab, TabPanel, useTabs } from '@elementor/ui';
import { ThemeProvider } from '@elementor/ui/styles';

import { SettingsPanel } from '../panels/settings-panel';
import { HomePanel } from '../panels/home-panel';
import { ActionLinksPanel } from '../panels/action-links-panel';

export const SettingsPage = ( props ) => {
	const tabs = [
		{
			name: 'HOME',
			component: <HomePanel />,
			title: __( 'Home', 'hello-plus' ),
		},
		{
			name: 'SETTINGS',
			component: <SettingsPanel />,
			title: __( 'Settings', 'hello-plus' ),
		},
	];

	const params = {
		tab: tabs[ 0 ].name,
	};

	const { getTabsProps, getTabProps, getTabPanelProps } = useTabs( params.tab );

	return (
		<ThemeProvider colorScheme="auto">
			<Box className="hello_plus__header" component="div">
				<Box className="hello_plus__container" component="div">
					<Box className="hello_plus__title" component="div">
						<h1>{ __( 'Hello+ Dashboard', 'hello-plus' ) }</h1>
					</Box>
				</Box>
			</Box>
			<Box className="hello_plus__main" component="div">
				<Container sx={ { width: '100%' } }>
					<Box sx={ { borderBottom: 1, borderColor: 'divider' } }>
						<Tabs { ...props } { ...getTabsProps() }>
							{
								tabs.map( ( tab ) => <Tab key={ tab.name } label={ tab.title } { ...getTabProps( tab.name ) } /> )
							}
						</Tabs>
					</Box>
					{
						tabs.map( ( tab ) => <TabPanel key={ tab.name } { ...getTabPanelProps( tab.name ) }>{ tab.component }</TabPanel> )
					}
				</Container>
				<ActionLinksPanel />
			</Box>
			<Box className="hello_plus__notices" component="div">
			</Box>
		</ThemeProvider>
	);
};
