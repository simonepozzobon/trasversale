const mix = require('laravel-mix');
const launchMiddleware = require('launch-editor-middleware');
//
// mix.launchMiddleware = (editor = null) => {
//     if (mix.config.hmr) {
//         const http = process.argv.includes('--https') ? 'https' : 'http';
//         const {
//             host,
//             port
//         } = mix.config.hmrOptions;
//         window.VUE_DEVTOOLS_CONFIG = http + '://' + host + ':' + port + '/';
//
//         mix.webpackConfig({
//             devServer: {
//                 before(app) {
//                     app.use('/__open-in-editor', launchMiddleware('atom'));
//                 },
//             },
//         });
//     }
//     return mix;
// };

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

mix.options({
    hmrOptions: {
        host: 'trasversale.test', // site's host name
        port: 4000,
    }
});

mix
    .js('resources/js/admin/admin.js', 'public/js')
    .js('resources/js/app.js', 'public/js')
    .sass('resources/sass/admin.scss', 'public/css')
    .sass('resources/sass/app.scss', 'public/css')
    .webpackConfig({
        resolve: {
            alias: {
                'styles': path.resolve(__dirname, 'resources/sass'),
            }
        },
        devtool: "source-map",
        devServer: {
            before(app) {
                app.use('/__open-in-editor', launchMiddleware('atom'));
            },
            // index: '',
            open: true,
            // host: 'localhost',
            proxy: {
                '**': {
                    target: 'http://trasversale.test', // host machine ip
                }
            },
            watchOptions: {
                aggregateTimeout: 200,
                poll: 5000
            },

        },
    })
// .browserSync({
//     proxy: {
//         target: 'http://trasversale.test',
//         ws: true,
//     },
//     browser: 'google chrome',
//     port: 3016,
//     files: [
//         'public/**/*',
//         'resources/**/*',
//         'app/**/*',
//         'config/**/*'
//     ]
// })
