const TerserPlugin = require('terser-webpack-plugin');
const path = require('path');
const HtmlWebpackPlugin = require('html-webpack-plugin');

module.exports = {
	entry: "./dev-react-social-media-carousel.js",
	output: {
        path: path.resolve(__dirname, './dist'),
		filename: "social-media-carousel.js",
        publicPath: "/wp-content/plugins/wp-feedThemSocial-react-extension/plugin-extensions/js/"
	},
    plugins: [new HtmlWebpackPlugin({
                    title: 'My Test app',
                    myPageHeader: 'Social Media Carousel',
                    template: './src/index.html',
                    filename: 'index.html' //relative to root of the application
                })
              ],
    optimization: {
        minimize: true,
        minimizer: [new TerserPlugin()],
    },
	module: {
		rules: [{
				test: /\.s[ac]ss$/i,
				use: [
					// Creates `style` nodes from JS strings
					'style-loader',
					// Translates CSS into CommonJS
					'css-loader',
					// Compiles Sass to CSS
					'sass-loader',
				],
			}, {
				test: /\.css$/i,
				use: ['style-loader', 'css-loader'],
			}, {
				exclude: [/node_modules/, /\.s[ac]ss$/i, /\.html$/],
				loader: 'babel-loader',
				options: {
					presets: [
						'@babel/preset-env',
						'@babel/react', {
							'plugins': ['@babel/plugin-proposal-class-properties']
						}
					]
				}
			}
		]
	}
};
