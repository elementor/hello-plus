// WordPress webpack config.
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

// Plugins.
const RemoveEmptyScriptsPlugin = require( 'webpack-remove-empty-scripts' );
const CopyWebpackPlugin = require( 'copy-webpack-plugin' );

// Utilities.
const path = require( 'path' );
const imagesPath = path.resolve( __dirname, './build/images' );

const modulesDir = process.cwd() + '/modules/';

const entryPoints = {
	// Admin module:
	'js/helloplus-onboarding': path.resolve( modulesDir, 'admin/assets/js', 'hello-plus-onboarding.js' ),

	// Content module
	'css/helloplus-zigzag': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-zigzag.scss' ),
	'css/helloplus-hero': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-hero.scss' ),
	'css/helloplus-cta': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-cta.scss' ),
	'css/helloplus-flex-hero': path.resolve( modulesDir, 'content/assets/scss', 'hello-plus-flex-hero.scss' ),
	'js/helloplus-content-fe': path.resolve( modulesDir, 'content/assets/js/frontend', 'frontend.js' ),

	// Template Parts module
	'css/helloplus-template-parts-preview': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-template-parts-preview.scss' ),
	'css/helloplus-header': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-header.scss' ),
	'css/helloplus-footer': path.resolve( modulesDir, 'template-parts/assets/scss', 'hello-plus-footer.scss' ),
	'js/helloplus-header': path.resolve( modulesDir, 'template-parts/assets/js', 'hello-plus-header.js' ),
	'js/helloplus-editor': path.resolve( modulesDir, 'template-parts/assets/js', 'editor.js' ),

	// Forms module
	'css/helloplus-forms': path.resolve( modulesDir, 'forms/assets/scss/widgets', 'hello-plus-forms.scss' ),
	'js/helloplus-forms-editor': path.resolve( modulesDir, 'forms/assets/js', 'editor.js' ),
	'js/helloplus-forms-editor-fe': path.resolve( modulesDir, 'forms/assets/js/frontend', 'frontend.js' ),
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

			new CopyWebpackPlugin( {
				patterns: [
					{
						from: path.resolve( modulesDir, 'forms/assets/images' ),
						to: imagesPath,
					},
					{
						from: path.resolve( modulesDir, 'template-parts/assets/images' ),
						to: imagesPath,
					},
				],
			} ),

			// Removes the empty `.js` files generated by webpack but
			// sets it after WP has generated its `*.asset.php` file.
			new RemoveEmptyScriptsPlugin( {
				stage: RemoveEmptyScriptsPlugin.STAGE_AFTER_PROCESS_PLUGINS,
			} ),
		],
	},
};
