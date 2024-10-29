export default function Footer( props ) {
	return <tfoot>{ props.children }</tfoot>;
}

Footer.propTypes = {
	children: PropTypes.any,
};
