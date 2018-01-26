var Encore = require('@symfony/webpack-encore');

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .autoProvidejQuery()
    .autoProvideVariables({
        "window.jQuery": "jquery"
    })
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    // .createSharedEntry('js/common', ['jquery'])
    .addEntry('js/markerclusterer', './assets/js/markerclusterer.js')
    .addEntry('js/manifest', './assets/js/manifest.js')
    .addEntry('js/common', './assets/js/common.js')
    .addEntry('js/app', './assets/js/app.js')
    .addEntry('js/search', './assets/js/search.js')
    .addEntry('js/mapShowDetail', './assets/js/mapShowDetail.js')
    .addStyleEntry('css/app', './assets/css/app.scss')
    .addStyleEntry('css/admin', './assets/css/admin.scss')

    // uncomment if you use Sass/SCSS files
    .enableSassLoader()

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

module.exports = Encore.getWebpackConfig();
