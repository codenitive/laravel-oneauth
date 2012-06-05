<?php namespace OneAuth\OAuth2;

use \Bundle, OneAuth\OAuth\Core as OAuth_Core;

class Core extends OAuth_Core
{
	/**
	 * Returns the output of a remote URL. Any [curl option](http://php.net/curl_setopt)
	 * may be used.
	 *
	 *     // Do a simple GET request
	 *     $data = Core::remote($url);
	 *
	 *     // Do a POST request
	 *     $data = Core::remote($url, array(
	 *         CURLOPT_POST       => TRUE,
	 *         CURLOPT_POSTFIELDS => http_build_query($array),
	 *     ));
	 *
	 * @param   string   remote URL
	 * @param   array    curl options
	 * @return  string
	 * @throws  Exception
	 */
	public static function remote($url, array $options = NULL)
	{
		// The transfer must always be returned
		$options[CURLOPT_RETURNTRANSFER] = TRUE;

		// Open a new remote connection
		$remote = curl_init($url);

		// Set connection options
		if ( ! curl_setopt_array($remote, $options))
		{
			throw new Exception('Failed to set CURL options, check CURL documentation: http://php.net/curl_setopt_array');
		}

		// Get the response
		$response = curl_exec($remote);

		if (curl_errno($remote) == 60) 
		{ 
			curl_setopt($remote, CURLOPT_CAINFO, Bundle::path('oneauth').'vendor'.DS.'ca_chain_bundle.crt');
			$response = curl_exec($remote);
		}

		// Get the response information
		$code = curl_getinfo($remote, CURLINFO_HTTP_CODE);

		if ($code AND ($code < 200 OR $code > 299))
		{
			$error = $response;
		}
		elseif ($response === FALSE)
		{
			$error = curl_error($remote);
		}

		// Close the connection
		curl_close($remote);

		if (isset($error))
		{
			throw new Exception(array(
				'error'   => $error,
				'code'    => $code,
				'message' => sprintf('Error fetching remote %s [ status %s ] %s', $url, $code, $error),
			));
		}

		return $response;
	}
}