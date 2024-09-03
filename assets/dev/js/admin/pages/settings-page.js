import { Fragment } from 'react';
import { store as noticesStore } from '@wordpress/notices';
import { useDispatch, useSelect } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import { TabPanel, SnackbarList } from '@wordpress/components';
import { SettingsPanel } from '../panels/settings-panel';
import { HomePanel } from '../panels/home-panel';
import { ActionLinksPanel } from '../panels/action-links-panel';

const Notices = () => {
	const notices = useSelect(
		( select ) =>
			select( noticesStore )
				.getNotices()
				.filter( ( notice ) => 'snackbar' === notice.type ),
		[],
	);

	const { removeNotice } = useDispatch( noticesStore );

	return (
		<SnackbarList
			className="edit-site-notices"
			notices={ notices }
			onRemove={ removeNotice }
		/>
	);
};

export const SettingsPage = () => {
	const tabs = [
		{
			name: 'HOME',
			children: <HomePanel />,
			title: __( 'Home', 'hello-plus' ),
			className: 'hello-plus-home-panel',
		},
		{
			name: 'SETTINGS',
			children: <SettingsPanel />,
			title: __( 'Settings', 'hello-plus' ),
			className: 'hello-plus-settings-panel',
		},
	];

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
				<TabPanel className="hello-plus-home-tabs"
					activeClass="active-tab"
					tabs={ tabs } >{ ( tab ) => tab.children }
				</TabPanel>
				<ActionLinksPanel />
			</div>
			<div className="hello_plus__notices">
				<Notices />
			</div>
		</Fragment>
	);
};
