module.exports = {
    plugins: [
        require('postcss-preset-env')({stage: 1}),
        require('autoprefixer'),
        process.env.NODE_ENV === 'production' ? require('cssnano')({preset: 'default'}) : null,
    ].filter(Boolean),
};