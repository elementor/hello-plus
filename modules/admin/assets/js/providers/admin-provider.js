import { createContext, useEffect, useState } from 'react';
import apiFetch from '@wordpress/api-fetch';

export const AdminContext = createContext();

export const AdminProvider = ( { children } ) => {
	const [ isLoading, setIsLoading ] = React.useState( true );
	const [ onboardingSettings, setOnboardingSettings ] = React.useState( {} );
	const [ elementorKitSettings, setElementorKitSettings ] = React.useState( {} );
	const [ stepAction, setStepAction ] = useState( '' );
	const [ step, setStep ] = useState( 0 );
	const { elementorInstalled, elementorActive, wizardCompleted } = onboardingSettings;
	const { elementorAppConfig } = window;

	useEffect( () => {
		if ( elementorAppConfig ) {
			setElementorKitSettings( elementorAppConfig[ 'kit-library' ] );
		}
	}, [ elementorAppConfig ] );

	useEffect( () => {
		if ( wizardCompleted ) {
			setStep( 2 );
			return;
		}
		if ( false === elementorInstalled ) {
			setStepAction( 'install-elementor' );
		}
		if ( elementorInstalled && false === elementorActive ) {
			setStepAction( 'activate-elementor' );
		}
		if ( elementorInstalled && elementorActive ) {
			setStepAction( 'install-kit' );
			setStep( 1 );
		}
	}, [ elementorInstalled, elementorActive, wizardCompleted ] );

	useEffect( () => {
		Promise.all( [
			apiFetch( { path: '/elementor-hello-plus/v1/onboarding-settings' } ),
		] ).then( ( [ onboarding ] ) => {
			setOnboardingSettings( onboarding.settings );
		} ).finally( () => {
			setIsLoading( false );
		} );
	}, [] );

	return (
		<AdminContext.Provider value={ {
			onboardingSettings,
			stepAction,
			setStepAction,
			step,
			setStep,
			isLoading,
			elementorKitSettings,
			setIsLoading,
		} }>
			{ children }
		</AdminContext.Provider>
	);
};
