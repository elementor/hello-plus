import { Fragment } from 'react';
import { __ } from '@wordpress/i18n';
// TODO: minimize to only necessary imports
import { Container, Box, Tabs, Tab, TabPanel, useTabs } from '@elementor/ui';
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
		<Fragment>
			<div className="hello_plus__header">
				<div className="hello_plus__container">
					<div className="hello_plus__title">
						<h1>{ __( 'Hello+ Dashboard', 'hello-plus' ) }</h1>
					</div>
				</div>
			</div>
			<div className="hello_plus__main">
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
			</div>
			<div className="hello_plus__notices">
			</div>
		</Fragment>
	);
};
