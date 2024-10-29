import FormSubmissionsComponent from './data/component';
import FormsComponent from './data/forms-component';
import App from './app';

$e.components.register( new FormSubmissionsComponent() );
$e.components.register( new FormsComponent() );

const AppWrapper = elementorCommon.config.isDebug ? React.StrictMode : React.Fragment;

// eslint-disable-next-line react/no-deprecated
ReactDOM.render(
	<AppWrapper>
		<App />
	</AppWrapper>,
	document.getElementById( 'e-form-submissions' ),
);
