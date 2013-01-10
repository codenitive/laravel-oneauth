# OneAuth Change Log

## Contents

- [OneAuth 0.1.6](#0.1.6)
- [OneAuth 0.1.5](#0.1.5)
- [OneAuth 0.1.4](#0.1.4)
- [OneAuth 0.1.3](#0.1.3)
- [OneAuth 0.1.2](#0.1.2)
- [OneAuth 0.1.1](#0.1.1)

<a name="0.1.6"></a>
## OneAuth 0.1.6

- Fixed Google OAuth 2 Provider not returning proper name.
- Fixed Google OAuth 2 Provider not always provide profile image.
- Fixed WindowsLive OAuth 2 Provider implementation.
- Add event on user login, so you don't need to fire and event when user is logged-in
- Add event on user logout, to clear oneauth session data.
- Update implementation for Orchestra Platform to allow smooth registration process.

<a name="0.1.5"></a>
## OneAuth 0.1.5

- Bugfixes on LinkedIn request.
- Add custom driver for OAuth and OAuth2.
- Update Flickr authentication process.
- Add Unit Testing for OneAuth.


<a name="0.1.4"></a>
## OneAuth 0.1.4

- Event 'oneauth.logged' should use unserialized array for `$user_data['token']`.

<a name="0.1.3"></a>
## OneAuth 0.1.3

- Fixed user profile on LinkedIn with setting set to private.
- Fixed error when unserializing an undefined value when session is null.
- Fixed incompleted Github profile.

<a name="0.1.2"></a>
## OneAuth 0.1.2

- Fixed API changes on Dropbox Provider.
- Fixed [#15](https://github.com/codenitive/laravel-oneauth/pull/15) where OAuth*\Token\Access return incomplete PHP class from database session, by serialize it first we should avoid such issue and at the same time enable this instance to be reuse to retrieve or send to the provider.
- Fixed [#16](https://github.com/codenitive/laravel-oneauth/pull/16) Access and Refresh token string length over 255. Increase access token, refresh token and secret length to 500.
- Add Basecamp OAuth2 Provider.

<a name="0.1.1"></a>
## OneAuth 0.1.1

- Improved OneAuth\Auth\Controller::action_error() support on displaying error message from OAuth and OAuth2.
- Integration with [Orchestra bundle](http://bundles.laravel.com/bundle/orchestra).
- Offline documentation using [Bundocs bundle](http://bundles.laravel.com/bundle/bundocs).
- Fixed odd structure on Paypal OAuth2 Provider, CURLOPT_POSTFIELD require string instead of normal array.
- Add Laravel\Auth call to use IoC.
- Comply with Laravel standard on using unsigned for foreign key.