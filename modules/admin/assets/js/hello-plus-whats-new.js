import Modal from '@elementor/ui/Modal';
import Box from '@elementor/ui/Box';
import { createRoot } from 'react-dom/client';
import { useEffect, useState } from 'react';
import apiFetch from '@wordpress/api-fetch';
import Stack from '@elementor/ui/Stack';
import Update from './components/whats-new/update';
import Typography from '@elementor/ui/Typography';
import { __ } from '@wordpress/i18n';

const style = {
    position: 'absolute',
    top: '50%',
    left: '50%',
    transform: 'translate(-50%, -50%)',
    bgcolor: 'background.paper',
    border: '1px solid #000',
    boxShadow: 24,
    p: 2,
    maxHeight: '80vh',
    overflowY: 'auto',
};

const App = () => {
    const [ whatsNew, setWhatsNew ] = useState( [] );
    const [ open, setOpen ] = useState( false );

    const handleOpen = ( event ) => {
        event.preventDefault();
        setOpen( true );
    };

    const handleClose = () => setOpen( false );

    useEffect( () => {
        apiFetch( { path: '/elementor-hello-plus/v1/whats-new' } ).then( ( response ) => {
            setWhatsNew( response );
        } ).catch( () => {
            setWhatsNew( [] );
        } );

        const button = document.getElementById( 'hello-plus-whats-new-link' );

        if ( button ) {
            button.addEventListener( 'click', handleOpen );
        }

        return () => {
            if ( button ) {
                button.removeEventListener( 'click', handleOpen );
            }
        };
    }, [] );

    return (
	<div>
		<Modal
			open={ open }
			onClose={ handleClose }
			aria-labelledby="modal-modal-title"
			aria-describedby="modal-modal-description"
                >
			<Box sx={ style }>
				<Typography variant={ 'h4' }>{ __( 'Changelog', 'hello-plus' ) }</Typography>
				<Stack direction={ 'column' } gap={ 1 } sx={ { mt: 2 } }>
					{ whatsNew.map( ( item ) => <Update key={ item.id } { ...item } /> ) }
				</Stack>
			</Box>
		</Modal>
	</div>
    );
};

document.addEventListener( 'DOMContentLoaded', () => {
    const container = document.getElementById( 'hello-plus-whats-new' );

    if ( container ) {
        const root = createRoot( container );
        root.render( <App /> );
    }
} );
