const mix = require('laravel-mix');
const path = require('path');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix
.webpackConfig({
    resolve: {
        alias: {
            svelte: path.resolve('node_modules', 'svelte')
        },
        extensions: ['.mjs', '.js', '.svelte'],
        mainFields: ['svelte', 'browser', 'module', 'main']
    },
    module: {
        rules: [
          {
            test: /\.(html|svelte)$/,
            use: 'svelte-loader'
          },
          {
            // required to prevent errors from Svelte on Webpack 5+, omit on Webpack 4
            test: /node_modules\/svelte\/.*\.mjs$/,
            resolve: {
              fullySpecified: false
            }
          }
        ]
      }
})
.js('resources/js/app.js', 'public/js').version()
// .sass('resources/sass/main.scss', 'public/css').options({
//     autoprefixer: {
//         options: {
//             browsers: [
//                 'last 12 versions',
//             ]
//         }
//     },
//     postCss: [
//         require('cssnano')({
//             preset: ['default', {
//                 discardComments: {
//                     removeAll: true,
//                 },
//             }]
//         })
//     ]
// })
.version();

