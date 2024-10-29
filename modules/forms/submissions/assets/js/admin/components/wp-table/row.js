export default function Row( props ) {
	return <tr style={ props.style } className={ props.className }>{ props.children }</tr>;
}

Row.propTypes = {
	children: PropTypes.any,
	style: PropTypes.object,
	className: PropTypes.string,
};

Row.defaultProps = {
	style: {},
	className: '',
};
