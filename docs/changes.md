# OneAuth Change Log

## Contents

- [OneAuth 0.1.3](#0.1.3)
- [OneAuth 0.1.2](#0.1.2)
- [OneAuth 0.1.1](#0.1.1)

<a name-"0.1.3"></a>
## OneAuth 0.1.3

- Fixed user profile on LinkedIn with setting set to private.
- Fixed error when unserializing an undefined value when session is null.
- Fixed incompleted Github profile.

<a name-"0.1.2"></a>
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