import { Box, Card, CardContent, CardHeader, FormControlLabel, Stack, Typography } from '@elementor/ui';

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
