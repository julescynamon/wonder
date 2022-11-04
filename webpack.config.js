const Encore = require('@symfony/webpack-encore');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore.setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.ts')
    .addEntry('question_show', './assets/styles/question_show.scss')
    .copyFiles({
        from: './assets/images',
        pattern: /\.(png|jpg|jpeg|svg)$/,
        to: 'images/[path][name].[ext]',
    })
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabel((config) => {
        config.plugins.push('@babel/plugin-proposal-class-properties');
    })
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
    .enableSassLoader()
    .enableTypeScriptLoader()
    .enableVueLoader();

    Encore.configureDevServerOptions((options) => {
    options.allowedHosts = 'all';
    server = {
        type: 'https',
        pfx: path.join(
        process.env.HOME || process.env.USERPROFILE,
        '.symfony/certs/default.p12'
        ),
    };
});

module.exports = Encore.getWebpackConfig();
