const mix = require('laravel-mix');
const WebpackShellPlugin = require('webpack-shell-plugin');
const _ = require('lodash');
 
const assets = {
    js: [
		'app.js',
		'bootstrap.js',
		'pages/Jackpot.js',
		'pages/Double.js',
		'pages/Pvp.js',
		'pages/Battle.js',
		'pages/Bonus.js',
		'pages/Referral.js',
		'pages/Help.js',
		'pages/Rules.js',
		'pages/Payment.js',
		'pages/Fair.js',
		'pages/GameHistory.js',
    ],
    sass: [
		'app.scss',
		'media.scss',
		'notifyme.scss',
		'tooltipster.scss',
    ],
    copy: [
        'images',
        'sounds',
    ]
};

const compile = function(assets, callback, data) {
    _.forEach(assets, function(file) {
        callback(`${data.src}/${file}`, `${data.dist}/${file}`);
    });
};

compile(assets.js, (from, to) => mix.js(from, to).version(), {
    'src': 'resources/assets/js',
    'dist': 'public/assets/js'
});

compile(assets.sass, (from, to) => mix.sass(from, to.replace('.scss', '.css')).version(), {
    'src': 'resources/assets/sass',
    'dist': 'public/assets/css'
});

compile(assets.copy, (from, to) => mix.copy(from, to).version(), {
    'src': 'resources/assets',
    'dist': 'public/assets'
});

mix.copy('resources/assets/manifest.json', 'public/manifest.json');

mix.webpackConfig({
    plugins: [new WebpackShellPlugin({
        //onBuildStart: ['php artisan cache:clear --quiet'],
        onBuildEnd: []
    })]
});