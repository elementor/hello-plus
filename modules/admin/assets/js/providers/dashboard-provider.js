import { createContext, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

export const DashboardContext = createContext();

export const DashboardProvider = ( { children } ) => {
	const [ promotionsLinks, setPromotionsLinks ] = React.useState( [] );
	useEffect( () => {
		apiFetch( { path: '/elementor-hello-plus/v1/promotions' } ).then( ( links ) => {
			setPromotionsLinks( links.links );
		} );
	}, [] );

	return (
		<DashboardContext.Provider value={ { promotionsLinks } }>
			{ children }
		</DashboardContext.Provider>
	);
};
