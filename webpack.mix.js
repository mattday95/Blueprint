let mix = require('laravel-mix');
require('laravel-mix-clean');
require('laravel-mix-imagemin');

// Set dist path
mix.setPublicPath('dist');

// Clean dist folder
mix.clean();

// Compile SCSS and disable CSS URL rewriting
mix.sass('src/scss/app.scss', 'css', {
    processUrls: false
});

mix.js('src/js/app.js', 'js', {
    processUrls: false
});

// Compile JS and extract vendor libs
// mix.js('src/js/app.js', 'js')
    // .extract();

// Add sourcemaps and cache-busting versioning to compiled assets
mix.sourceMaps(false, 'source-map');
    // .version();

// Minify and transfer images into dist folder
    // Only use minifed image if it's smaller than the original image
mix.imagemin(
    'img',
    {
        patterns: [{ from: 'src/img', to: 'img' }],
    },
    {
        onlyUseIfSmaller: true
    }
);

// Copy fonts into dist folder
// mix.copyDirectory('src/fonts', 'web/dist/fonts');

// Disable OS notifications for successful builds
mix.disableSuccessNotifications();

// BrowserSync config
mix.browserSync({
    files: [
        'dist/css/**/*.css',
        // 'web/dist/js/**/*.js',
        '**/*.{html,phtml,php,twig}'
    ],
    open: false,
    notify: false,
    proxy: 'http://blueprint.test'
});