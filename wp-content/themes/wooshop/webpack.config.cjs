const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const CssMinimizerPlugin = require('css-minimizer-webpack-plugin');
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts');
const CopyWebpackPlugin = require('copy-webpack-plugin');

module.exports = (env, argv) => ({
    mode: argv.mode === 'production' ? 'production' : 'development',

    entry: {
        app: './assets/src/scss/app.scss',
        shop: './assets/src/scss/shop.scss',
        'single-product': './assets/src/scss/single-product.scss',
        cart: './assets/src/scss/cart.scss',
        checkout: './assets/src/scss/checkout.scss',
        'my-account': './assets/src/scss/my-account.scss',
    },

    output: {
        path: path.resolve(__dirname, 'assets/build'), filename: 'js/[name].min.js', clean: true,
    },

    module: {
        rules: [{
            test: /\.scss$/,
            use: [MiniCssExtractPlugin.loader, {
                loader: 'css-loader',
                options: {url: false}
            }, 'postcss-loader', 'sass-loader',],
        },],
    },

    plugins: [
        new MiniCssExtractPlugin({ filename: 'css/[name].min.css' }),
        new RemoveEmptyScriptsPlugin(),
        new CopyWebpackPlugin({
            patterns: [
                { from: 'assets/src/fonts', to: 'fonts' },
            ],
        }),
    ],

    optimization: {
        minimizer: [new CssMinimizerPlugin()],
    },
});