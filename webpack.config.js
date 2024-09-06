/**
 * Grunt webpack task config
 *
 * @package
 */
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const TerserPlugin = require( 'terser-webpack-plugin' );

const entry = {
	'hello-plus-editor': path.resolve( __dirname, './assets/dev/js/editor/hello-plus-editor.js' ),
	'hello-plus-frontend': path.resolve( __dirname, './assets/dev/js/frontend/hello-plus-frontend.js' ),
	'hello-plus-admin': [
		path.resolve( __dirname, './assets/dev/js/admin/hello-plus-admin.js' ),
		path.resolve( __dirname, './assets/dev/scss/admin/hello-plus-admin.scss' ),
	],
	'hello-plus-notice': [
		path.resolve( __dirname, './assets/dev/js/modules/hello-plus-notice.js' ),
		path.resolve( __dirname, './assets/dev/scss/modules/hello-plus-notice.scss' ),
	],
	'hello-plus-content': [
		path.resolve( __dirname, './modules/content/assets/js/zig-zag.js' ),
		path.resolve( __dirname, './modules/content/assets/scss/zig-zag.scss' ),
	],
};

const moduleRules = {
	rules: [
		...defaultConfig.module.rules,
		{
			test: /\.js$/,
			exclude: /node_modules/,
			use: [
				{
					loader: 'babel-loader',
					options: {
						presets: [ '@babel/preset-env', '@babel/preset-react' ],
						plugins: [
							[ '@babel/plugin-proposal-class-properties' ],
							[ '@babel/plugin-transform-runtime' ],
							[ '@babel/plugin-transform-modules-commonjs' ],
							[ '@babel/plugin-proposal-optional-chaining' ],
						],
					},
				},
			],
		},
	],
};

const commonConfig = {
	...defaultConfig,
	target: 'web',
	context: __dirname,
	module: moduleRules,
	entry,
	output: {
		...defaultConfig.output,
		path: path.resolve( __dirname, './assets/js' ),
		filename: '[name].js',
	},
};

const webpackConfig = {
	...commonConfig,
	mode: 'development',
	output: {
		...commonConfig.output,
		devtoolModuleFilenameTemplate: './[resource]',
	},
	entry: {
		...entry,
	},
	devtool: 'source-map',
};

const webpackProductionConfig = {
	...commonConfig,
	mode: 'production',
	optimization: {
		...defaultConfig.optimization || {},
		minimize: false,
		minimizer: [
			new TerserPlugin( {
				terserOptions: {
					keep_fnames: true,
				},
				include: /\.min\.js$/,
			} ),
		],
	},
	performance: { hints: false },
};

// Add minified entry points
Object.entries( webpackProductionConfig.entry ).forEach( ( [ wpEntry, value ] ) => {
	webpackProductionConfig.entry[ wpEntry + '.min' ] = value;
} );

webpackProductionConfig.plugins = defaultConfig.plugins;

module.exports = ( env ) => {
	if ( env.developmentLocal ) {
		return { ...webpackConfig, watch: true };
	}

	if ( env.production ) {
		return webpackProductionConfig;
	}

	if ( env.development ) {
		return webpackConfig;
	}

	throw new Error( 'missing or invalid --env= development/production/developmentWithWatch/productionWithWatch' );
};
