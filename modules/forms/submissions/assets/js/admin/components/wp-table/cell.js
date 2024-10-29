export default function Cell( props ) {
	const Component = props.component;

	return (
		<Component className={ props.className } style={ props.style } data-colname={ props.colName } colSpan={ props.colSpan }>
			{ props.children }
		</Component>
	);
}

Cell.propTypes = {
	component: PropTypes.string,
	children: PropTypes.any,
	className: PropTypes.string,
	style: PropTypes.object,
	colName: PropTypes.string,
	colSpan: PropTypes.number,
};

Cell.defaultProps = {
	component: 'td',
	className: '',
};
