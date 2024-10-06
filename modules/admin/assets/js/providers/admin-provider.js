import { createContext, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

export const AdminContext = createContext();

export const AdminProvider = ( { children } ) => {
	const [ promotionsLinks, setPromotionsLinks ] = React.useState( [] );
	const [ adminSettings, setAdminSettings ] = React.useState( {} );
	const [ onboardingSettings, setOnboardingSettings ] = React.useState( {} );

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

	useEffect( () => {
		apiFetch( { path: '/elementor-hello-plus/v1/onboarding-settings' } ).then( ( settings ) => {
			setOnboardingSettings( settings.settings );
		} );
	}, [] );

	return (
		<AdminContext.Provider value={ { promotionsLinks, adminSettings, onboardingSettings } }>
			{ children }
		</AdminContext.Provider>
	);
};
