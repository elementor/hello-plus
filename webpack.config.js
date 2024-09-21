// WordPress webpack config.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Plugins.
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// Utilities.
const path = require( 'path' );

const modulesDir = process.cwd() + '/modules/';

const entryPoints = {
	// Editor:
	'js/hello-plus-editor': path.resolve( process.cwd(), 'assets/dev/js/editor', 'hello-plus-editor.js' ),
	'js/hello-plus-frontend': path.resolve( process.cwd(), 'assets/dev/js/frontend', 'hello-plus-frontend.js' ),

	// Customizer module:
	'css/customizer': path.resolve( modulesDir, 'customizer/assets/scss', 'customizer.scss' ),

	// Admin module:
	'js/hello-plus-admin': path.resolve( modulesDir, 'admin/assets/js', 'hello-plus-admin.js' ),
	'css/hello-plus-admin': path.resolve( modulesDir, 'admin/assets/scss', 'hello-plus-admin.scss' ),
	'js/hello-plus-notice': path.resolve( modulesDir, 'admin/assets/js', 'hello-plus-notice.js' ),
	'css/hello-plus-notice': path.resolve( modulesDir, 'admin/assets/scss', 'hello-plus-notice.scss' ),
	'js/hello-plus-admin-top-bar': path.resolve( modulesDir, 'admin/assets/js', 'hello-plus-topbar.js' ),

	// Content module
	'js/hello-plus-content': path.resolve( modulesDir, 'content/assets/js', 'hello-plus-content.js' ),
	'css/hello-plus-content': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-content.scss' ),

	// Theme module
	'css/theme': path.resolve( modulesDir, 'theme/assets/scss', 'theme.scss' ),
	'css/header-footer': path.resolve( modulesDir, 'theme/assets/scss', 'header-footer.scss' ),
	'css/editor': path.resolve( modulesDir, 'theme/assets/scss', 'editor.scss' ),
	'css/classic-editor': path.resolve( modulesDir, 'theme/assets/scss', 'classic-editor.scss' ),
};

module.exports = {
	...defaultConfig,
	...{
		entry: entryPoints,
		output: {
			...defaultConfig.output,
			path: path.resolve( __dirname, './build' ),
		},
		plugins: [
			// Include WP's plugin config.
			...defaultConfig.plugins,

			// Removes the empty `.js` files generated by webpack but
			// sets it after WP has generated its `*.asset.php` file.
			new RemoveEmptyScriptsPlugin( {
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			} ),
		],
	},
};
