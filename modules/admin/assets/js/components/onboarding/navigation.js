import Box from '@elementor/ui/Box';
import { __ } from '@wordpress/i18n';
import Step from '@elementor/ui/Step';
import StepLabel from '@elementor/ui/StepLabel';
import Stepper from '@elementor/ui/Stepper';
import { useGetCurrentStep } from '../../hooks/use-get-current-step';

export const Navigation = () => {
	const { step } = useGetCurrentStep();

	const steps = [ __( 'Get Started', 'hello-plus' ), __( 'Choose a Kit', 'hello-plus' ), __( 'Ready to Go', 'hello-plus' ) ];

	return (
		<Box sx={ { width: '100%' } }>
			<Stepper activeStep={ step }>
				{ steps.map( ( label, index ) => {
					return (
						<Step key={ label } completed={ index < step } active={ index === step } >
							<StepLabel >{ label }</StepLabel>
						</Step>
					);
				} ) }
			</Stepper>
		</Box>
	);
};
