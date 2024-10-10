import { useAdminContext } from './use-admin-context';
import { useEffect, useState } from 'react';
import { __ } from '@wordpress/i18n';

export const useGetCurrentStep = () => {
	const [ stepAction, setStepAction ] = useState( '' );
	const [ step, setStep ] = useState( 0 );
	const [ buttonText, setButtonText ] = useState( '' );
	const { onboardingSettings: { elementorInstalled, elementorActive } = {} } = useAdminContext();

	useEffect( () => {
		if ( false === elementorInstalled ) {
			setStepAction( 'install-elementor' );
			setButtonText( __( 'Start building my website', 'hello-plus' ) );
		}
		if ( elementorInstalled && false === elementorActive ) {
			setStepAction( 'activate-elementor' );
			setButtonText( __( 'Start building my website', 'hello-plus' ) );
		}
		if ( elementorInstalled && elementorActive ) {
			setStepAction( 'install-kit' );
			setButtonText( __( 'Install Kit', 'hello-plus' ) );
			setStep( 0 );
		}
	}, [ elementorInstalled, elementorActive ] );

	return {
		stepAction,
		setStepAction,
		buttonText,
		step,
		setStep,
	};
};
