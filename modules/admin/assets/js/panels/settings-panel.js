import { Fragment, useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import api from '@wordpress/api';
import { dispatch } from '@wordpress/data';
import { SettingsBody } from './settings-body';
import { Box, Button, CircularProgress } from '@elementor/ui';

export const SettingsPanel = () => {
	const [ settingsData, setSettingsData ] = useState( {} );

	const settingsPrefix = 'hello_plus_settings';

	const SETTINGS = {
		DESCRIPTION_META_TAG: '_description_meta_tag',
		SKIP_LINK: '_skip_link',
		HEADER_FOOTER: '_header_footer',
		PAGE_TITLE: '_page_title',
		HELLO_PLUS_STYLE: '_hello_plus_style',
		HELLO_PLUS_THEME: '_hello_plus_theme',
	};

	/**
	 * Update settings data.
	 *
	 * @param {string} settingsName
	 * @param {string} settingsValue
	 */
	const updateSettings = ( settingsName, settingsValue ) => {
		setSettingsData( {
			...settingsData,
			[ settingsName ]: settingsValue,
		} );
	};

	/**
	 * Save settings to server.
	 */
	const saveSettings = () => {
		const data = {};

		Object.values( SETTINGS ).forEach( ( value ) => data[ `${ settingsPrefix }${ value }` ] = settingsData[ value ] ? 'true' : '' );

		const settings = new api.models.Settings( data );

		settings.save();

		dispatch( 'core/notices' ).createNotice(
			'success',
			__( 'Settings Saved', 'hello-plus' ),
			{
				type: 'snackbar',
				isDismissible: true,
			},
		);
	};

	useEffect( () => {
		const fetchSettings = async () => {
			try {
				await api.loadPromise;
				const settings = new api.models.Settings();
				const response = await settings.fetch();

				const data = {};
				Object.values( SETTINGS ).forEach( ( value ) => data[ value ] = response[ `${ settingsPrefix }${ value }` ] );

				setSettingsData( data );
				setHasLoaded( true );
			} catch ( error ) {
				// eslint-disable-next-line no-console
				console.error( error );
			}
		};

		if ( hasLoaded ) {
			return;
		}

		fetchSettings();
	}, [ settingsData ] );

	const [ hasLoaded, setHasLoaded ] = useState( false );

	if ( ! hasLoaded ) {
		return (
			<Box display="flex" justifyContent="center" alignItems="center" height="100vh">
				<CircularProgress />
			</Box>
		);
	}

	return (
		<Fragment>
			<SettingsBody
				settingsData={ settingsData }
				updateSettings={ updateSettings }
				settings={ SETTINGS }
			/>
			<Button variant="contained" onClick={ saveSettings }>
				{ __( 'Save Settings', 'hello-plus' ) }
			</Button>
		</Fragment>
	);
};
