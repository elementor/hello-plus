import { __ } from '@wordpress/i18n';
// TODO: minimize to only necessary imports
import { Tabs } from '@elementor/ui/Tabs';
import { useTabs } from '@elementor/ui/useTabs';
import { TabPanel } from '@elementor/ui/TabPanel';
import { Tab } from '@elementor/ui/Tab';
import { Box } from '@elementor/ui/Box';
import { Container } from '@elementor/ui/Container';
import { ThemeProvider } from '@elementor/ui/styles';

import { DashboardSettingsPanel } from '../components/panels/dashboard/settings/dashboard-settings-panel';
import { DashboardHomePanel } from '../components/panels/dashboard/home/dashboard-home-panel';

export const DashboardPage = ( props ) => {
	const tabs = [
		{
			name: 'HOME',
			component: <DashboardHomePanel />,
			title: __( 'Home', 'hello-plus' ),
		},
		{
			name: 'SETTINGS',
			component: <DashboardSettingsPanel />,
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
						<Tabs { ...props } { ...getTabsProps() } centered>
							{
								tabs.map( ( tab ) => <Tab key={ tab.name } label={ tab.title } { ...getTabProps( tab.name ) } /> )
							}
						</Tabs>
					</Box>
					{
						tabs.map( ( tab ) => <TabPanel key={ tab.name } { ...getTabPanelProps( tab.name ) }>{ tab.component }</TabPanel> )
					}
				</Container>
			</Box>
			<Box className="hello_plus__notices" component="div">
			</Box>
		</ThemeProvider>
	);
};
