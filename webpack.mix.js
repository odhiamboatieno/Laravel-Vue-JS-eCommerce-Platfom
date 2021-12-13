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

mix.js('resources/js/app.js', 'public/public/js')
	mix.js('resources/js/category.js', 'public/public/js')
	mix.js('resources/js/sub_category.js', 'public/public/js')
	mix.js('resources/js/sub_sub_category.js', 'public/public/js')
	mix.js('resources/js/brand.js', 'public/public/js')
	mix.js('resources/js/product.js', 'public/public/js')
	mix.js('resources/js/campaign.js', 'public/public/js')
	mix.js('resources/js/currency.js', 'public/public/js')
	mix.js('resources/js/payment.js', 'public/public/js')
	mix.js('resources/js/social_setting.js', 'public/public/js')
	mix.js('resources/js/shop_setting.js', 'public/public/js')
	mix.js('resources/js/pages.js', 'public/public/js')
	mix.js('resources/js/messenger.js', 'public/public/js')
	mix.js('resources/js/role.js', 'public/public/js')
	mix.js('resources/js/shipping.js', 'public/public/js')
	mix.js('resources/js/email.js', 'public/public/js')
	mix.js('resources/js/admin.js', 'public/public/js')
	mix.js('resources/js/order.js', 'public/public/js')
	mix.js('resources/js/dashboard.js', 'public/public/js')
	mix.js('resources/js/customer.js', 'public/public/js')
	mix.js('resources/js/report.js', 'public/public/js')
	mix.js('resources/js/front.js', 'public/public/js')
    .sass('resources/sass/app.scss', 'public/public/css');
