const glob = require('glob');
const path = require( 'path' );
const defaultConfig = require( '@wordpress/scripts/config/webpack.config' );

const indexFiles = glob.sync('./src/*/index.js');
const pluginIndexFiles = glob.sync('./src/plugins/**/index.js');

/**
 * Webpack config (Development mode)
 *
 * @see https://developer.wordpress.org/block-editor/packages/packages-scripts/#provide-your-own-webpack-config
 */
module.exports = {
	...defaultConfig,
	entry: {
		...defaultConfig.entry,
		index: [...indexFiles,...pluginIndexFiles],
	},
	output: {
		...defaultConfig.output,
		filename: '[name].js',
		path: path.resolve(__dirname, 'build'),
	}
};
