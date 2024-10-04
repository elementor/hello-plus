import Stack from '@elementor/ui/Stack';
import Box from '@elementor/ui/Box';
import { __ } from '@wordpress/i18n';
import Typography from '@elementor/ui/Typography';

export const Navigation = () => {
	const steps = [
		{
		text: __( 'Get Started', 'hello-plus' ),
		number: 1,
		},
		{
			text: __( 'Choose a Kit', 'hello-plus' ),
			number: 2,
		},
		{
			text: __( 'Ready to Go', 'hello-plus' ),
			number: 3,
		},
	];
	return (
		<Stack direction="row" sx={ { width: '100%', justifyContent: "space-evenly" } }>
			{ steps.map( ( step ) => {
				return (
					<Stack
						direction="row"
						key={ step.number }
						sx={ {alignItems: 'center' } }
						spacing={ 1 }
						p={2}
					>
						<Typography variant="body1">{ step.number }</Typography>
						<Typography variant="body2">{ step.text }</Typography>
					</Stack>
				);
			} ) }
		</Stack>
	);
};
