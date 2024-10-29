import useDataAction, { STATUS_LOADING } from './use-data-action';
import downloadBlob from '../utils/download-blob';
import { useNoticesContext } from '../context/notices-context';

export const EXPORT_MODE_ALL = 'all';
export const EXPORT_MODE_SELECTED = 'selected';
export const EXPORT_MODE_FILTERED = 'filtered';

const { useState, useMemo } = React;

const ZIP_NAME_FORMAT = 'elementor-submissions-export-{DATE}';
const EXPORT_FILE_NAME_FORMAT = 'elementor-submissions-export-{FORM_LABEL}-{DATE}';

const DEFAULT_MAX_ROWS_PER_REQUEST = 10000;

const defaultOptions = {
	maxRowsPerRequest: DEFAULT_MAX_ROWS_PER_REQUEST,
	checked: [],
	filter: {},
};

/**
 * Export functionality
 *
 * @param {any} hookOptions
 * @return {(any|{mode: (string), progress: {success: number, count: number}, status: string})[]} [ exportSubmissions, { mode, progress, status: exportStatus } ]
 */
export function useExport( hookOptions ) {
	const options = useMemo( () => ( {
		...defaultOptions,
		...hookOptions,
	} ), [ hookOptions ] );

	const { notifyError } = useNoticesContext();
	const [ progress, setProgress ] = useState( { count: 0, success: 0 } );

	const mode = useMemo( () => {
		if ( options.checked.length > 0 ) {
			return EXPORT_MODE_SELECTED;
		}

		const filter = Object.fromEntries(
			Object.entries( options.filter ).filter( ( [ , value ] ) => value ),
		);

		if ( 1 === Object.keys( filter ).length && 'all' === filter.status ) {
			return EXPORT_MODE_ALL;
		}

		return EXPORT_MODE_FILTERED;
	}, [ options.checked, options.filter ] );

	const [ exportSubmissions, { status: exportStatus } ] = useDataAction( ( [ query, total ], { abortController, status } ) => {
		if ( status === STATUS_LOADING && abortController ) {
			abortController.abort();

			return Promise.reject( { message: 'Aborted' } );
		}

		const numberOfRequests = Math.ceil( total / options.maxRowsPerRequest );
		let exportDataByForm = {};
		let promise = Promise.resolve();

		setProgress( { count: numberOfRequests, success: 0 } );

		for ( let i = 1; i <= numberOfRequests; i++ ) {
			promise = promise
				.then(
					() => $e.data.get(
						'form-submissions/export',
						{ ...query, per_page: options.maxRowsPerRequest, page: i },
						{ refresh: true, signal: abortController.signal },
					),
				)
				.then( ( result ) => {
					const shouldSaveHeaders = 1 === i;

					exportDataByForm = mergeFormExportData(
						result.data.data,
						exportDataByForm,
						shouldSaveHeaders,
					);

					setProgress( ( prev ) => ( { ...prev, success: i } ) );
				} );
		}

		return promise
			.then( () => downloadExportsResults( Object.values( exportDataByForm ) ) )
			.catch( ( error ) => notifyError( error.message ) );
	}, [ options.maxRowsPerRequest ] );

	return [ exportSubmissions, { mode, progress, status: exportStatus } ];
}

/**
 * Merge data from one response into the current data from all the other responses.
 *
 * @param {Array}   response
 * @param {any}     current
 * @param {boolean} shouldSaveHeaders
 * @return {any} Form results
 */
function mergeFormExportData( response, current, shouldSaveHeaders ) {
	response.forEach( ( formResult ) => {
		// The first row of each csv response is the headers row. When it merges all csv responses, it deletes
		// the headers for each response except from the first one, the result will be only one row headers in the final csv.
		if ( ! shouldSaveHeaders ) {
			delete formResult.content[ 0 ];
		}

		current = {
			...current,
			[ formResult.id ]: {
				...formResult,
				content: ( current[ formResult.id ]?.content || '' ) + formResult.content.join( '\n' ),
			},
		};
	} );

	return current;
}

/**
 * Merge all the promises result into one csv and download it.
 *
 * @param {Array} dataResults
 */
function downloadExportsResults( dataResults ) {
	const currentDate = wp.date.date( 'Y-m-d' );

	const files = dataResults.map( ( item ) => transformFormResultIntoBlob( item, currentDate ) );

	// If there is only one form file, just download it as csv instead of compressing it into a zip file.
	if ( 1 === files.length ) {
		downloadBlob(
			files[ 0 ].blob,
			files[ 0 ].filename,
		);

		return;
	}

	import( /* webpackChunkName: "jszip.vendor" */ 'jszip' ).then( ( { default: JSZip } ) => {
		const zip = new JSZip();

		files.forEach( ( { filename, blob } ) => zip.file( filename, blob ) );

		return zip.generateAsync( { type: 'blob' } );
	} ).then( ( zipBlob ) => {
		downloadBlob(
			zipBlob,
			ZIP_NAME_FORMAT.replace( '{DATE}', currentDate ),
		);
	} );
}

/**
 * Transform the merged result from the server into a blob.
 *
 * @param {any}  formResult
 * @param {Date} currentDate
 * @return {{filename: string, blob: Blob}} Filename and blob
 */
function transformFormResultIntoBlob( formResult, currentDate ) {
	return {
		filename: EXPORT_FILE_NAME_FORMAT
			.replace( '{FORM_LABEL}', formResult.form_label )
			.replace( '{DATE}', currentDate )
			.concat( `.${ formResult.extension }` ),
		blob: new Blob(
			[
				// UTF-8 BOM to support microsoft excel
				// ref: https://stackoverflow.com/questions/31959487/utf-8-encoidng-issue-when-exporting-csv-file-javascript
				'\ufeff',
				formResult.content,
			],
			{ type: formResult.mimetype },
		),
	};
}
