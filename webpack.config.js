const path = require('path');
const { VueLoaderPlugin } = require('vue-loader');
const {WebpackManifestPlugin} = require("webpack-manifest-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");

module.exports = {
    mode: 'production',
    entry: ['./resources/js/app.js', './resources/css/app.css'],
    output: {
        path: path.resolve(__dirname, 'public/build'),
        filename: 'app.js',
    },
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js'),
        },
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                use: 'vue-loader',
            },
            {
                test: /\.js$/,
                use: 'babel-loader',
            },
            {
                test: /\.css$/,
                use: [MiniCssExtractPlugin.loader, 'css-loader'],
            },
        ],
    },
    plugins: [
        new VueLoaderPlugin(),
        new WebpackManifestPlugin({
            fileName: 'manifest.json',
            publicPath: '/public/'
        }),
        new MiniCssExtractPlugin({
            filename: 'style.css'
        })
    ],
};
