/**
 * A util function to make sure the user download the blob data.
 *
 * @param {any}    blob
 * @param {string} filename
 */
export default function downloadBlob( blob, filename = '' ) {
	const link = document.createElement( 'a' );
	link.setAttribute( 'href', URL.createObjectURL( blob ) );
	link.style.visibility = 'hidden';

	if ( filename ) {
		link.setAttribute( 'download', filename );
	}

	document.body.appendChild( link );
	link.click();
	document.body.removeChild( link );
}
