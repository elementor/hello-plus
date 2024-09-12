import Typography from '@elementor/ui/Typography';
import IconButton from '@elementor/ui/IconButton';

export const AdminTopBarLink = ( { linkData } ) => {
	const { label, hrefStr, children, color, aria } = linkData;
	return (
		<IconButton
			size="medium"
			edge="end"
			color={ color }
			aria-label={ aria }
			href={ hrefStr }
		>
			{ children }
			<Typography
				fontSize="medium"
				align="center"
			>
				{ label }
			</Typography>
		</IconButton>
	);
};
