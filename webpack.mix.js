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
    .js('resources/js/admin/admin.js', 'public/js')
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/admin.scss', 'public/css')
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
        proxy: {
            target: 'http://trasversale.test',
            ws: true,
        },
        browser: 'google chrome',
        port: 3016,
        files: [
            'public/**/*',
            'resources/**/*',
            'app/**/*',
            'config/**/*'
        ]
    })
