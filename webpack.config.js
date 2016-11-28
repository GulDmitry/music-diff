var webpack = require('webpack');
var ExtractTextPlugin = require('extract-text-webpack-plugin');

process.env.ENV = process.env.ENV || 'dev';

var config = {
    devtool: 'eval-cheap-module-source-map',
    entry: [
        'babel-polyfill',
        './web/js/app.js'
    ],
    output: {
        filename: 'bundle.js',
        path: 'web/assets/',
        publicPath: '/assets/'
    },
    module: {
        preLoaders: [
            {
                test: /\.js$/,
                loaders: ['eslint'],
                exclude: /node_modules/,
            }
        ],
        loaders: [
            {
                test: /\.jsx$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            },
            {
                test: /\.js$/,
                exclude: /node_modules/,
                loader: 'babel-loader'
            },
            {
                test: /\.css$/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader')
            },
            {
                test: /\.less$/,
                loader: ExtractTextPlugin.extract('style-loader', 'css-loader!less-loader')
            },
            {
                test: /\.png$/,
                loader: 'url-loader?limit=100000'
            },
            {
                test: /\.jpg$/,
                loader: 'file-loader'
            },
            {
                test: /\.(woff|woff2)(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url?limit=10000&mimetype=application/font-woff'
            },
            {
                test: /\.ttf(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url?limit=10000&mimetype=application/octet-stream'
            },
            {
                test: /\.eot(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'file'
            },
            {
                test: /\.svg(\?v=\d+\.\d+\.\d+)?$/,
                loader: 'url?limit=10000&mimetype=image/svg+xml'
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('bundle.css'),
        new webpack.DefinePlugin({
            // 'true' for boolean, '"string"' for string.
            'ENV': `"${process.env.ENV}"`
        }),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery'
        }),
        new webpack.NoErrorsPlugin()
    ],
    externals: {
        // "react": "React" causes error "React is not defined".
        'React': 'React'
    },
    resolve: {extensions: ['', '.js', '.jsx']}
};

if (process.env.ENV === 'prod') {
    config.devtool = 'source-map';
    config.plugins.push(
        new webpack.optimize.UglifyJsPlugin({
            beautify: false,
            comments: false,
            compress: {
                sequences: true,
                booleans: true,
                loops: true,
                unused: true,
                warnings: false,
                drop_console: true,
                unsafe: true
            }
        }),
        new webpack.optimize.OccurenceOrderPlugin()
    );

} else {
    // config.plugins.push(
    //     new webpack.HotModuleReplacementPlugin()
    // );
}

module.exports = config;
