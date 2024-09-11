import { Box } from '@elementor/ui/Box';
import { Card } from '@elementor/ui/Card';
import { CardContent } from '@elementor/ui/CardContent';
import { FormControlLabel } from '@elementor/ui/FormControlLabel';
import { Stack } from '@elementor/ui/Stack';

export const SettingCard = ( { label, description, control, code } ) => {
	return (
		<Card>
			<CardContent>
				<Stack gap={ 1 } direction="row" alignContent="center" >
					<Stack flex="0 0 30%" alignContent="center">
						<FormControlLabel
							control={
								control
							}
							label={ label }
							labelPlacement="bottom"
						/>
					</Stack>
					<Box flex="auto">
						<Stack gap={ 1 } direction="column" >
							<Box>
								{ description }
							</Box>
							{ code }
						</Stack>
					</Box>
				</Stack>
			</CardContent>
		</Card>
	);
};
