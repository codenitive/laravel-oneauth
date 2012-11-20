OneAuth Bundle
==============

OAuth and OAuth2 Auth bundle for Laravel

[![Build Status](https://secure.travis-ci.org/codenitive/laravel-oneauth.png?branch=master)](http://travis-ci.org/codenitive/laravel-oneauth)

*OpenID support is currently under plan.*

## Installation

### Installation with Laravel Artisan

	php artisan bundle:install oneauth

### Bundle Registration

	'oneauth' => array('auto' => true),

### Run the migration

	php artisan migrate

## OneAuth Documentation

OneAuth Bundle come with an offline documentation, to view this please download and enable `bundocs` bundle,
see [Bundocs Bundle](http://bundles.laravel.com/bundle/bundocs) for more detail.

## Contributors

This bundle is a port from `Kohana\OAuth`, `OAuth2 Package for FuelPHP` and `NinjAuth Package for FuelPHP`. Original license is reserved to the contributors.

## License

	The MIT License

	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:

	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.

	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
