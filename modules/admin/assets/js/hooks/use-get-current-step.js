import { useAdminContext } from './use-admin-context';
import { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';

export const useGetCurrentStep = () => {
	const [ step, setStep ] = useState( '' );
	const [ buttonText, setButtonText ] = useState( '' );
	const { onboardingSettings: { elementorInstalled, elementorActive } = {} } = useAdminContext();

	useEffect( () => {
		if ( false === elementorInstalled ) {
			setStep( 'install-elementor' );
			setButtonText( __( 'Install Elementor', 'hello-plus' ) );
		}
		if ( elementorInstalled && false === elementorActive ) {
			setStep( 'activate-elementor' );
			setButtonText( __( 'Activate Elementor', 'hello-plus' ) );
		}
		if ( elementorInstalled && elementorActive ) {
			setStep( 'install-kit' );
			setButtonText( __( 'Install Kit', 'hello-plus' ) );
		}
	}, [ elementorInstalled, elementorActive ] );

	return {
		step,
		setStep,
		buttonText,
	};
};
