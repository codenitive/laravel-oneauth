<?php

Autoloader::namespaces(array(
	'OneAuth\\Auth'   => Bundle::path('oneauth').'libraries'.DS.'auth',
	'OneAuth\\OAuth'  => Bundle::path('oneauth').'libraries'.DS.'oauth',
	'OneAuth\\OAuth2' => Bundle::path('oneauth').'libraries'.DS.'oauth2',
));