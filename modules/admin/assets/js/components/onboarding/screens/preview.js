import Box from '@elementor/ui/Box';
import Spinner from '../../spinner/spinner';
import { useEffect, useState } from 'react';
import { useAdminContext } from '../../../hooks/use-admin-context';
import ConnectDialog from '../kits/connect-dialog';
import { ApplyKitDialog } from '../kits/apply-kit-dialog';
import { TobBarPreview } from '../../top-bar/top-bar-preview';
import { Overview } from './overview';

export const Preview = ( { kit, setPreviewKit } ) => {
	const [ isLoading, setIsLoading ] = useState( true );
	const [ showConnectDialog, setShowConnectDialog ] = useState( false );
	const [ showApplyKitDialog, setShowApplyKitDialog ] = useState( false );
	const [ isOverview, setIsOverview ] = useState( false );
	const {
		setStep,
		elementorKitSettings,
	} = useAdminContext();

	const { manifest: { site = '', name, description, content: { page = {} } }, title } = kit;
	const [ previewUrl, setPreviewUrl ] = useState( site );

	const pages = Object.entries( page );

	const { library_connect_url: libraryUrl, is_library_connected: isConnected } = elementorKitSettings;

	useEffect( () => {
		setIsLoading( true );
	}, [ site ] );

	return (
		<>
			{ showConnectDialog && ( <ConnectDialog
				pageId={ slug }
				onClose={ () => setShowConnectDialog( false ) }
				onSuccess={ () => {
					setShowConnectDialog( false );
					setShowApplyKitDialog( true );
				} }
				onError={ ( message ) => setError( { message } ) }
				connectUrl={ libraryUrl.replace( '%%page%%', name ) + '&mode=popup&callback_id=cb1' }
			/> ) }
			{
				showApplyKitDialog && ( <ApplyKitDialog
					title={ title }
					startImportProcess={ () => {
						setStep( 2 );
						setPreviewKit( null );
					} }
					onClose={ () => setShowApplyKitDialog( false ) }
				/> )
			}
			<TobBarPreview
				onClickBack={ () => setPreviewKit( null ) }
				onClickRightButton={ () => {
					if ( isConnected ) {
						setShowApplyKitDialog( true );
					} else {
						setShowConnectDialog( true );
					}
				} }
				overview={ isOverview }
				onClickLeftButton={ () => {
					setIsOverview( ! isOverview );
				} }
			/>
			<Box sx={ { position: 'relative', width: '100%', height: '100%' } }>
				{ isLoading && <Spinner /> }
				{ ! isOverview && ( <iframe
					src={ previewUrl }
					style={ { position: 'absolute', top: 0, left: 0, width: '100%', height: '100%', border: 'none' } }
					title={ title }
					onLoad={ () => setIsLoading( false ) }
				/> ) }
				{ isOverview && ( <Overview
					setIsOverview={ setIsOverview }
					setIsLoading={ setIsLoading }
					setPreviewUrl={ setPreviewUrl }
					title={ title }
					description={ description }
					pages={ pages }
					kit={ kit }
					/>
				) }

			</Box>
		</>

	);
};
