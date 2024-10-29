import Header from './header';
import Body from './body';
import Footer from './footer';
import Row from './row';
import Cell from './cell';
import OrderableCell from './orderable-cell';
import RowActions from './row-actions';

export default function WpTable( props ) {
	return (
		<table
			className={ `wp-list-table widefat fixed table-view-list ${ props.className }` }
			style={ props.style }
		>
			{ props.children }
		</table>
	);
}

WpTable.propTypes = {
	children: PropTypes.any,
	style: PropTypes.object,
	className: PropTypes.string,
};

WpTable.defaultProps = {
	className: '',
};

WpTable.Header = Header;
WpTable.Body = Body;
WpTable.Footer = Footer;
WpTable.Row = Row;
WpTable.Cell = Cell;
WpTable.OrderableCell = OrderableCell;
WpTable.RowActions = RowActions;

