const mix = require('laravel-mix');
const glob = require('glob-all');

require('laravel-mix-tailwind');
require('laravel-mix-purgecss');

mix.js('resources/js/app.js', 'public/js')
	.postCss('resources/css/app.css', 'public/css')
	.tailwind('./tailwind.config.js')
	.purgeCss({
		enabled: true,
		paths: () => glob.sync([
			path.join(__dirname, 'resources/views/*.brio'),
			path.join(__dirname, 'resources/views/account/*.brio'),
			path.join(__dirname, 'resources/views/auth/*.brio'),
			path.join(__dirname, 'resources/views/auth/*.brio'),
			path.join(__dirname, 'resources/views/content/*.brio'),
			path.join(__dirname, 'resources/views/email/*.brio'),
			path.join(__dirname, 'resources/views/layouts/*.brio'),
			path.join(__dirname, 'resources/views/partials/*.brio'),
			path.join(__dirname, 'resources/views/stubs/*.brio')
		]),
	});
	
mix.autoload({
    jquery: ['$', 'jQuery']
});
