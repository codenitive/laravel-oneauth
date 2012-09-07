<?php

Event::listen('orchestra.form: extension.oneauth', function ($config, $form)
{
	$form->extend(function ($form)
	{
		$form->fieldset('Basecamp OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Basecamp ID', 'dropbox_id');
			$fieldset->control('input:text', 'Basecamp Secret', 'dropbox_secret');
		});

		$form->fieldset('Dropbox OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Dropbox Key', 'dropbox_key');
			$fieldset->control('input:text', 'Dropbox Secret', 'dropbox_secret');
		});

		$form->fieldset('Facebook OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Facebook ID', 'facebook_id');
			$fieldset->control('input:text', 'Facebook Secret', 'facebook_secret');
			$fieldset->control('input:text', 'facebook_scope', function ($control)
			{
				$control->label = 'Facebook Scope';
				$control->value = function ($row)
				{
					$value = ! empty($row->facebook_scope) ? $row->facebook_scope : Config::get('oneauth::api.providers.facebook.scope');

					return $value;
				};
			});
		});

		$form->fieldset('Flickr OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Flickr Key', 'flickr_key');
			$fieldset->control('input:text', 'Flickr Secret', 'flickr_secret');
		});

		$form->fieldset('FourSquare OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'FourSquare ID', 'foursquare_id');
			$fieldset->control('input:text', 'FourSquare Secret', 'foursquare_secret');
		});

		$form->fieldset('Github OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Github ID', 'github_id');
			$fieldset->control('input:text', 'Github Secret', 'github_secret');
		});

		$form->fieldset('Google OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Google ID', 'google_id');
			$fieldset->control('input:text', 'Google Secret', 'google_secret');
		});

		$form->fieldset('Instagram OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Instagram ID', 'instagram_id');
			$fieldset->control('input:text', 'Instagram Secret', 'instagram_secret');
		});

		$form->fieldset('LinkedIn OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'LinkedIn Key', 'linkedin_key');
			$fieldset->control('input:text', 'LinkedIn Secret', 'linkedin_secret');
		});

		$form->fieldset('Paypal OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Paypal ID', 'paypal_id');
			$fieldset->control('input:text', 'Paypal Secret', 'paypal_secret');
		});

		$form->fieldset('Soundcloud OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'Soundcloud ID', 'soundcloud_id');
			$fieldset->control('input:text', 'Soundcloud Secret', 'soundcloud_secret');
		});

		$form->fieldset('Tumblr OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Tumblr Key', 'tumblr_key');
			$fieldset->control('input:text', 'Tumblr Secret', 'tumblr_secret');
		});

		$form->fieldset('Twitter OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Twitter Key', 'twitter_key');
			$fieldset->control('input:text', 'Twitter Secret', 'twitter_secret');
		});

		$form->fieldset('Vimeo OAuth', function ($fieldset)
		{
			$fieldset->control('input:text', 'Vimeo Key', 'vimeo_key');
			$fieldset->control('input:text', 'Vimeo Secret', 'vimeo_secret');
		});

		$form->fieldset('WindowLive OAuth2', function ($fieldset)
		{
			$fieldset->control('input:text', 'WindowLive ID', 'windowlive_id');
			$fieldset->control('input:text', 'WindowLive Secret', 'windowlive_secret');
		});
	});
});
