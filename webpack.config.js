// WordPress webpack config.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Plugins.
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );

// Utilities.
const path = require( 'path' );

const modulesDir = process.cwd() + '/modules/';

const entryPoints = {
	// Admin module:
	'js/hello-plus-onboarding': path.resolve( modulesDir, 'admin/assets/js', 'hello-plus-onboarding.js' ),

	// Content module
	'css/hello-plus-zigzag': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-zigzag.scss' ),
	'css/hello-plus-hero': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-hero.scss' ),
	'css/hello-plus-cta': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-cta.scss' ),

	// Template Parts module
	'css/hello-plus-template-parts-preview': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-template-parts-preview.scss' ),
	'css/hello-plus-header': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-header.scss' ),
	'css/hello-plus-footer': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-footer.scss' ),
	'js/hello-plus-header': path.resolve( modulesDir, 'template-parts/assets/js', 'hello-plus-header.js' ),
	'js/hello-plus-template-parts-editor': path.resolve( modulesDir, 'template-parts/assets/js', 'hello-plus-template-parts-editor.js' ),
	'js/hello-plus-forms-editor': path.resolve( modulesDir, 'forms/assets/js', 'editor.js' ),
	'js/hello-plus-forms-editor-fe': path.resolve( modulesDir, 'forms/assets/js/frontend', 'frontend.js' ),

	// Theme module
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
