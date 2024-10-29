import Checkbox from '../components/checkbox';
import DateFilter from '../components/date-filter';
import ExportButton from '../components/export-button';
import Link from '../components/link';
import LinksFilter from '../components/links-filter';
import noticeMessages from '../notice-messages';
import Pagination from '../components/pagination';
import RefererFilter from '../components/referer-filter';
import ResourceFilter from '../components/resource-filter';
import SearchBox from '../components/search-box';
import SubmissionRow from '../components/submission-row';
import useDataAction, { STATUS_LOADING } from '../hooks/use-data-action';
import useRestDataList from '../hooks/use-rest-data-list';
import WpTable from '../components/wp-table';
import { BulkActionSelect } from '../components/bulk-action-select';
import { EXPORT_MODE_SELECTED, useExport } from '../hooks/use-export';
import { useNoticesContext } from '../context/notices-context';
import { useSettingsContext } from '../context/settings-context';

const { useMemo, useState } = React;

const ORDERABLE_CELLS = [
	'id',
	'created_at',
	'main_meta_id',
];

const STATUSES = [
	{
		value: 'all',
		label: __( 'All', 'elementor-pro' ),
	},
	{
		value: 'unread',
		label: __( 'Unread', 'elementor-pro' ),
	},
	{
		value: 'read',
		label: __( 'Read', 'elementor-pro' ),
	},
	{
		value: 'trash',
		label: __( 'Trash', 'elementor-pro' ),
	},
];

export default function Index() {
	const { notifyError, notifySuccess } = useNoticesContext();
	const { isTrashEnabled } = useSettingsContext();

	const [ checked, setChecked ] = useState( [] );

	const {
		data,
		meta,
		fetchData,
		flatQuery,
		query: {
			order,
			filter,
		},
		statuses: {
			fetchDataStatus,
		},
		actions: {
			setFilter,
			setOrder,
			setPage,
		},
	} = useRestDataList( 'form-submissions/index', {
		allowedFilters: [ 'search', 'status', 'form', 'referer', 'after', 'before' ],
		useRouterQueryString: true,
		hooks: {
			afterFetch: () => setChecked( [] ),
		},
	} );

	const isTrashFilterOn = useMemo(
		() => 'trash' === filter.status,
		[ filter.status ],
	);

	const [ exportSubmissions, exportOptions ] = useExport(
		{ checked, filter },
	);

	const [ updateRead ] = useDataAction(
		( [ query, isRead ], { abortController } ) => {
			if ( ! validateRestAction( query ) ) {
				return Promise.reject();
			}

			const messages = noticeMessages[ isRead ? 'markedAsRead' : 'markedAsUnread' ];

			return $e.data.update( 'form-submissions/index', { is_read: isRead }, query, { signal: abortController.signal } )
				.then( ( result ) => notifySuccess( messages.success( result.data.meta?.affected || 1 ) ) )
				.then( fetchData )
				.catch( () => notifyError( messages.error() ) );
		},
	);

	const [ restoreItems ] = useDataAction(
		( [ query ], { abortController } ) => {
			if ( ! validateRestAction( query ) ) {
				return Promise.reject();
			}

			return $e.data.update( 'form-submissions/restore', {}, query, { signal: abortController.signal } )
				.then( ( result ) => notifySuccess( noticeMessages.restored.success( result.data.meta?.affected || 1 ) ) )
				.then( fetchData )
				.catch( () => notifyError( noticeMessages.restored.error() ) );
		},
	);

	const [ deleteItems ] = useDataAction(
		( [ query ], { abortController } ) => {
			if ( ! validateRestAction( query ) ) {
				return Promise.reject();
			}

			const messages = noticeMessages[ query.force ? 'deleted' : 'trashed' ];

			return $e.data.delete( 'form-submissions/index', query, { signal: abortController.signal } )
				.then( ( result ) => notifySuccess(
					messages.success( result.data.meta?.affected || 1 ),
					query.force ? null : () => restoreItems( query ),
				) )
				.then( fetchData )
				.catch( () => notifyError( messages.error() ) );
		},
	);

	const rowActions = useMemo( () => [
		{
			key: 'view',
			label: __( 'View', 'elementor-pro' ),
			shouldShow: ( item ) => 'trash' !== item.status,
			props: ( item ) => ( {
				to: `/${ item.id }`,
				// Override the default behavior of click event
				onClick: undefined,
			} ),
			component: Link,
		},
		{
			key: 'restore',
			label: __( 'Restore', 'elementor-pro' ),
			onApply: ( item ) => restoreItems( { id: item.id } ),
			shouldShow: ( item ) => 'trash' === item.status,
		},
		{
			key: 'trash',
			label: __( 'Trash', 'elementor-pro' ),
			onApply: ( item ) => deleteItems( { id: item.id } ),
			shouldShow: ( item ) => 'trash' !== item.status && isTrashEnabled,
			className: 'trash',
		},
		{
			key: 'delete',
			label: __( 'Delete Permanently', 'elementor-pro' ),
			onApply: ( item ) => deleteItems( { id: item.id, force: 1 } ),
			shouldShow: ( item ) => 'trash' === item.status || ! isTrashEnabled,
			className: 'trash',
		},
		{
			key: 'read',
			label: __( 'Mark as Read', 'elementor-pro' ),
			onApply: ( item ) => updateRead( { id: item.id }, true ),
			shouldShow: ( item ) => 'trash' !== item.status && ! item.is_read,
		},
		{
			key: 'unread',
			label: __( 'Mark as Unread', 'elementor-pro' ),
			onApply: ( item ) => updateRead( { id: item.id }, false ),
			shouldShow: ( item ) => 'trash' !== item.status && item.is_read,
		},
	], [] );

	const bulkActions = useMemo( () => {
		return [
			{
				label: __( 'Mark as Read', 'elementor-pro' ),
				value: 'read',
				onApply: () => updateRead( { ids: checked }, true ),
				shouldShow: () => ! isTrashFilterOn,
			},
			{
				label: __( 'Mark as Unread', 'elementor-pro' ),
				value: 'unread',
				onApply: () => updateRead( { ids: checked }, false ),
				shouldShow: () => ! isTrashFilterOn,
			},
			{
				label: __( 'Move to Trash', 'elementor-pro' ),
				value: 'trash',
				onApply: () => deleteItems( { ids: checked } ),
				shouldShow: () => ! isTrashFilterOn && isTrashEnabled,
			},
			{
				label: __( 'Delete Permanently', 'elementor-pro' ),
				value: 'delete',
				onApply: () => deleteItems( { ids: checked, force: 1 } ),
				shouldShow: () => isTrashFilterOn || ! isTrashEnabled,
			},
			{
				label: __( 'Restore', 'elementor-pro' ),
				value: 'restore',
				onApply: () => restoreItems( { ids: checked } ),
				shouldShow: () => isTrashFilterOn,
			},
		].filter( ( action ) => action.shouldShow() );
	}, [ checked, isTrashFilterOn ] );

	const tableTitles = useMemo( () => ( {
		main_meta_id: { label: __( 'Main', 'elementor-pro' ), className: 'column-primary' },
		actions_log_status: { label: __( 'Actions Status', 'elementor-pro' ), className: 'column-actions' },
		form: { label: __( 'Form', 'elementor-pro' ), className: 'column-form' },
		page: { label: __( 'Page', 'elementor-pro' ), className: 'column-page' },
		id: { label: __( 'ID', 'elementor-pro' ), className: 'column-id' },
		created_at: { label: __( 'Submission Date', 'elementor-pro' ), className: 'column-date' },
	} ), [] );

	const headerFooterRow = (
		<WpTable.Row>
			<WpTable.Cell className="manage-column bulk-checkbox-column">
				<Checkbox.Bulk
					checkedGroup={ checked }
					onChange={ setChecked }
					allValues={ data.map( ( checkedItem ) => checkedItem.id ) }
				/>
			</WpTable.Cell>
			{
				Object.entries( tableTitles ).map( ( [ key, tableTitle ] ) => {
					const cellProps = { component: 'th', className: `manage-column ${ tableTitle.className || '' }` };

					if ( ORDERABLE_CELLS.includes( key ) ) {
						return (
							<WpTable.OrderableCell
								{ ...cellProps }
								key={ key }
								order={ {
									key,
									current: order,
									onChange: ( value ) => setOrder( value ),
								} }
							>{ tableTitle.label }</WpTable.OrderableCell>
						);
					}

					return <WpTable.Cell key={ key } { ...cellProps }>{ tableTitle.label }</WpTable.Cell>;
				} )
			}
		</WpTable.Row>
	);

	const exportButton = (
		<div className="alignright">
			<ExportButton
				onClick={ () => {
					exportSubmissions(
						EXPORT_MODE_SELECTED === exportOptions.mode ? { ids: checked } : { ...flatQuery },
						checked.length || meta.pagination?.total,
					);
				} }
				isLoading={ STATUS_LOADING === exportOptions.status }
				progress={ exportOptions.progress }
				mode={ exportOptions.mode }
				disabled={ ! meta.pagination?.total && STATUS_LOADING !== exportOptions.status }
			/>
		</div>
	);

	const tablePagination = ( meta.pagination &&
		<Pagination
			total={ meta.pagination.total }
			currentPage={ meta.pagination.current_page }
			lastPage={ meta.pagination.last_page }
			perPage={ meta.pagination.per_page }
			onChange={ ( value ) => setPage( value ) }
		/>
	);

	return (
		<>
			<div>
				<SearchBox
					key={ 'search' }
					value={ filter.search }
					onChange={ ( value ) => setFilter( { search: value } ) }
					isSearching={ ! ! filter.search && fetchDataStatus === STATUS_LOADING }
				/>

				<LinksFilter
					options={ STATUSES.map( ( { value, label } ) => ( {
						value,
						label,
						count: meta.count?.[ value ],
						shouldShow: 'all' === value || meta.count?.[ value ] > 0,
					} ) ) }
					value={ filter.status }
					onChange={ ( value ) => setFilter( { status: value } ) }
				/>
				<div className="clear" />
				<div className="tablenav top">
					<BulkActionSelect actions={ bulkActions } className="alignleft" />
					<div className="alignleft actions">
						<RefererFilter
							value={ filter.referer || '' }
							onChange={ ( ( value ) => setFilter( { referer: value } ) ) }
						/>

						<ResourceFilter
							value={ filter.form || '' }
							onChange={ ( ( value ) => setFilter( { form: value } ) ) }
							options={ [ { value: '', label: __( 'All Forms', 'elementor-pro' ) } ] }
							resourceOptions={ {
								type: 'resource',
								command: 'forms/index',
								args: {
									context: 'options',
								},
							} }
							name={ 'form' }
							label={ __( 'Filter by form', 'elementor-pro' ) }
						/>

						<DateFilter
							value={ {
								after: filter.after,
								before: filter.before,
							} }
							onChange={ ( { after, before } ) => {
								setFilter( {
									...after !== undefined ? { after } : {},
									...before !== undefined ? { before } : {},
								} );
							} }
							label={ __( 'Filter by date', 'elementor-pro' ) }
							name="date"
						/>
					</div>
					{ exportButton }
					{ tablePagination }
				</div>
				<WpTable className="e-form-submissions-list-table striped table-view-list">
					{ headerFooterRow && <WpTable.Header>{ headerFooterRow }</WpTable.Header> }
					<WpTable.Body>
						{
							! [ 'loading', 'idle' ].includes( fetchDataStatus ) && 0 === data.length &&
								<WpTable.Row className="no-items">
									<WpTable.Cell className="colspanchange" colSpan={ 7 }>
										{ __( 'No submissions found.', 'elementor-pro' ) }
									</WpTable.Cell>
								</WpTable.Row>
						}
						{ data.map( ( item ) => {
							const filteredRowActions = rowActions
								.filter( ( action ) => action.shouldShow( item ) );

							return <SubmissionRow
								key={ item.id }
								item={ item }
								tableTitles={ tableTitles }
								checkBoxComponent={
									<Checkbox
										checkedGroup={ checked }
										onChange={ setChecked }
										value={ item.id }
									/>
								}
								rowActionComponent={
									<WpTable.RowActions actions={ filteredRowActions } item={ item } />
								}
							/>;
						} ) }
					</WpTable.Body>
					{ headerFooterRow && <WpTable.Footer>{ headerFooterRow }</WpTable.Footer> }
				</WpTable>
				<div className="tablenav bottom">
					<BulkActionSelect actions={ bulkActions } className="alignleft" />
					{ exportButton }
					{ tablePagination }
				</div>
			</div>
		</>
	);
}

function validateRestAction( query ) {
	return (
		query.id ||
		( query.ids && query.ids.length > 0 )
	);
}
