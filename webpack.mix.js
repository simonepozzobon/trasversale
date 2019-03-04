const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig(webpack => {
        return {
            resolve: {
                alias: {
                    'styles': path.resolve(__dirname, 'resources/sass'),
                }
            }
        }
    })
    .browserSync({
        proxy: 'http://trasversale.test:89',
        browser: 'google chrome',
        port: 3016,
        files: [
            'public/**/*',
            'resources/**/*',
            'app/**/*',
            'config/**/*'
        ]
    })
