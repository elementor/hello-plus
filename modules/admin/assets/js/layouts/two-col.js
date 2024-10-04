import Grid from "@elementor/ui/Grid";

export const TwoCol = ({children}) => {
	return(
		<Grid container spacing={ 2 }>
			<Grid item xs={ 12 } md={ 6 }>
				{ children[0] }
			</Grid>
			<Grid item xs={ 12 } md={ 6 }>
				{ children[1] }
			</Grid>
		</Grid>
	);
}
