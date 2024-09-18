import { createContext, useEffect } from 'react';
import apiFetch from '@wordpress/api-fetch';

export const AdminContext = createContext();

export const AdminProvider = ( { children } ) => {
	const [ promotionsLinks, setPromotionsLinks ] = React.useState( [] );

	useEffect( () => {
		apiFetch( { path: '/elementor-hello-plus/v1/promotions' } ).then( ( links ) => {
			setPromotionsLinks( links.links );
		} );
	}, [] );

	return (
		<AdminContext.Provider value={ { promotionsLinks } }>
			{ children }
		</AdminContext.Provider>
	);
};
