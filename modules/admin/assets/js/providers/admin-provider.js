import { createContext, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

export const AdminContext = createContext();

export const AdminProvider = ( { children } ) => {
	const [ promotionsLinks, setPromotionsLinks ] = React.useState( [] );
	const [ adminSettings, setAdminSettings ] = React.useState( {} );

	useEffect( () => {
		apiFetch( { path: '/elementor-hello-plus/v1/promotions' } ).then( ( links ) => {
			setPromotionsLinks( links.links );
		} );
	}, [] );

	useEffect( () => {
		apiFetch( { path: '/elementor-hello-plus/v1/admin-settings' } ).then( ( settings ) => {
			setAdminSettings( settings.config );
		} );
	}, [] );

	return (
		<AdminContext.Provider value={ { promotionsLinks, adminSettings } }>
			{ children }
		</AdminContext.Provider>
	);
};
