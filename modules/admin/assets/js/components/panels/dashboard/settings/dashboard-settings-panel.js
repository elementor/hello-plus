import { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';
import api from '@wordpress/api';
import { SettingsBody } from './components/settings-body';
import { Alert, Box, Button, CircularProgress, Stack } from '@elementor/ui';
import { GridWithActionLinks } from '../../../../layouts/grids/grid-with-action-links';

export const DashboardSettingsPanel = () => {
	const [ settingsData, setSettingsData ] = useState( {} );
	const [ message, setMessage ] = useState( '' );
	const [ severity, setSeverity ] = useState( 'success' );

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
	const saveSettings = async () => {
		const data = {};

		Object.values( SETTINGS ).forEach( ( value ) => data[ `${ settingsPrefix }${ value }` ] = settingsData[ value ] ? 'true' : '' );

		try {
			const settings = new api.models.Settings( data );

			await settings.save();
			setMessage( __( 'Settings Saved', 'hello-plus' ) );
			setSeverity( 'success' );
		} catch ( e ) {
			setMessage( __( 'Failed to save settings', 'hello-plus' ) );
			setSeverity( 'error' );
		}
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
		<GridWithActionLinks>
			<Stack gap={ 1 }>
				{ message && <Alert severity={ severity } onClose={ () => setMessage( '' ) }>{ message }</Alert> }
				<SettingsBody
					settingsData={ settingsData }
					updateSettings={ updateSettings }
					settings={ SETTINGS }
			/>
				<Button variant="contained" onClick={ saveSettings }>
					{ __( 'Save Settings', 'hello-plus' ) }
				</Button>
			</Stack>
		</GridWithActionLinks>
	);
};
