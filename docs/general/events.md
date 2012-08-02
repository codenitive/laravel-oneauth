# Events

OneAuth use two events, one during login with OAuth/OAuth2 and another to synchronize user login/register with OAuth/OAuth2 database. 

This is to ensure authentication between 3rd party providers and your own application authentication works synchronously. 

## oneauth.logged

This event will be triggered once user logged in with OAuth or OAuth2, this would be good if you need to get extra information from providers including name, personal information or even profile picture.

	/**
	 * @param   OneAuth\Auth\Client   $client       (instanceof Eloquent)
	 * @param   array                 $user_data    users' data from provider
	 */
	Event::listen('oauth.logged', function ($client, $user_data)
	{
		// do something cool with those information
	});
	
## oneauth.sync

This event should be fired when the user actually create an account in your application. For example, you may have `User` Eloquent Model and authenticate using `Auth`.
	
	$login = array('username' => Input::get('username'), 'password' => Input::get('password'));
	
	if (Auth::attempt($login))
	{
		// get logged user id.
		$user_id = Auth::user()->id;
		
		// Synced it with oneauth, this will create a relationship between
		// `oneauth_clients` table with `users` table.
		Event::fire('oneauth.sync', array($user_id));
	} 