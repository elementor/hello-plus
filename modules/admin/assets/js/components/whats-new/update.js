import Stack from '@elementor/ui/Stack';
import Typography from '@elementor/ui/Typography';

export default function Update( { title, description } ) {
    return (
	<Stack direction={ 'column' }>
		<Typography variant={ 'h6' }>{ title }</Typography>
		<div dangerouslySetInnerHTML={ { __html: description } } />
	</Stack>
    );
}
