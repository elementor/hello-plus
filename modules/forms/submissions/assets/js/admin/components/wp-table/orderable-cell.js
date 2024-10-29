/* eslint-disable jsx-a11y/anchor-is-valid */
import Cell from './cell';

export default function OrderableCell( props ) {
	const className = `${ props.className } sortable ${
		props.order.current.by === props.order.key && `sorted ${ props.order.current.direction }`
	}`;

	return (
		<Cell
			component={ props.component }
			style={ props.style }
			className={ className }
		>
			<a href="#" onClick={ () => props.order.onChange( {
				by: props.order.key,
				direction: props.order.key === props.order.current.by && 'asc' === props.order.current.direction
					? 'desc'
					: 'asc',
			} ) }>
				<span>{ props.children }</span>
				<span className="sorting-indicator" />
			</a>
		</Cell>
	);
}

OrderableCell.propTypes = {
	...Cell.propTypes,
	order: PropTypes.shape( {
		key: PropTypes.string.isRequired,
		current: PropTypes.shape( {
			by: PropTypes.string,
			direction: PropTypes.oneOf( [ 'asc', 'desc' ] ),
		} ).isRequired,
		onChange: PropTypes.func.isRequired,
	} ).isRequired,
};

OrderableCell.defaultProps = {
	...Cell.defaultProps,
	component: 'th',
};
