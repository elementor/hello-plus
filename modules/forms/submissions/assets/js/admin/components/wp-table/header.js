export default function Header( props ) {
	return <thead>{ props.children }</thead>;
}

Header.propTypes = {
	children: PropTypes.any,
};
