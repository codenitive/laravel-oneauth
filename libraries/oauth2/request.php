<?php namespace OneAuth\OAuth2;

class Request
{
	/**
	 * Create a new request object.
	 *
	 *     $request = Request::make('token', 'GET', 'http://example.com/oauth/request_token');
	 *
	 * @param   string  request type
	 * @param   string  request URL
	 * @param   string  request method
	 * @param   array   request parameters
	 * @return  Request
	 */
	public static function make($type, $method, $url = null, array $params = null)
	{
		switch ($type)
		{
			case 'access' :
				return new Request\Access($method, $url, $params);
				break;
			case 'resource' :
				return new Request\Resource($method, $url, $params);
				break;
		}
	}

	/**
	 * @var  integer  connection timeout
	 */
	public $timeout = 10;

	/**
	 * @var  boolean  send Authorization header?
	 */
	public $send_header = true;

	/**
	 * @var  string  request type name: token, authorize, access, resource
	 */
	protected $name;

	/**
	 * @var  string  request method: GET, POST, etc
	 */
	protected $method = 'GET';

	/**
	 * @var  string  request URL
	 */
	protected $url;

	/**
	 * @var   array   request parameters
	 */
	protected $params = array();

	/**
	 * Set the request URL, method, and parameters.
	 *
	 * @param  string  request method
	 * @param  string  request URL
	 * @param  array   request parameters
	 * @uses   Core::parse_url
	 */
	public function __construct($method, $url, array $params = null)
	{
		// Set the request method
		if ($method) $this->method = strtoupper($method);

		// Separate the URL and query string, which will be used as
		// additional default parameters
		list ($url, $default) = Core::parse_url($url);

		// Set the request URL
		$this->url = $url;

		// Set the default parameters
		if ($default) $this->params($default);

		// Set the request parameters
		if ($params) $this->params($params);
	}

	/**
	 * Return the value of any protected class variable.
	 *
	 *     // Get the request parameters
	 *     $params = $request->params;
	 *
	 *     // Get the request URL
	 *     $url = $request->url;
	 *
	 * @param   string  variable name
	 * @return  mixed
	 */
	public function __get($key)
	{
		return $this->$key;
	}

	/**
	 * Parameter getter and setter. Setting the value to `null` will remove it.
	 *
	 * @param   string   parameter name
	 * @param   mixed    parameter value
	 * @param   boolean  allow duplicates?
	 * @return  mixed    when getting
	 * @return  $this    when setting
	 * @uses    array_get
	 */
	public function param($name, $value = null, $duplicate = false)
	{
		if ($value === null)
		{
			// Get the parameter
			return array_get($this->params, $name);
		}

		if (isset($this->params[$name]) AND $duplicate)
		{
			if ( ! is_array($this->params[$name]))
			{
				// Convert the parameter into an array
				$this->params[$name] = array($this->params[$name]);
			}

			// Add the duplicate value
			$this->params[$name][] = $value;
		}
		else
		{
			// Set the parameter value
			$this->params[$name] = $value;
		}

		return $this;
	}

	/**
	 * Set multiple parameters.
	 *
	 *     $request->params($params);
	 *
	 * @param   array    parameters
	 * @param   boolean  allow duplicates?
	 * @return  $this
	 * @uses    Request::param
	 */
	public function params(array $params, $duplicate = false)
	{
		foreach ($params as $name => $value)
		{
			$this->param($name, $value, $duplicate);
		}

		return $this;
	}

	/**
	 * Convert the request parameters into a query string, suitable for GET
	 * and POST requests.
	 *
	 *     $query = $request->as_query();
	 *
	 * @param   boolean   return a normalized string?
	 * @return  string
	 */
	public function as_query($as_string = true)
	{
		$params = $this->params;

		return $as_string ? Core::normalize_params($params) : $params;
	}

	/**
	 * Return the entire request URL with the parameters as a GET string.
	 *
	 *     $url = $request->as_url();
	 *
	 * @return  string
	 * @uses    Request::as_query
	 */
	public function as_url()
	{
		return $this->url.'?'.$this->as_query(true);
	}

	/**
	 * Execute the request and return a response.
	 *
	 * @param   array    additional cURL options
	 * @return  string   request response body
	 * @uses    array_get
	 * @uses    Core::remote
	 */
	public function execute(array $options = null)
	{
		// Get the URL of the request
		$url = $this->url;
		
		if ( ! isset($options[CURLOPT_USERAGENT]))
		{
			// Set the default user agent. GitHub requires one.
			$options[CURLOPT_USERAGENT] = "OneAuth - https://github.com/codenitive/laravel-oneauth";
		}

		if ( ! isset($options[CURLOPT_CONNECTTIMEOUT]))
		{
			// Use the request default timeout
			$options[CURLOPT_CONNECTTIMEOUT] = $this->timeout;
		}

		if (\Request::env() === 'local')
		{
			$options[CURLOPT_SSL_VERIFYPEER] = false;
		}

		if (substr($this->method, 0, 4) === 'POST')
		{
			// Send the request as a POST
			$options[CURLOPT_POST] = true;
			$as_string = false;

			if ($this->method === 'POST_QUERY')
			{
				$as_string = true;
			}

			if ($post = $this->as_query($as_string))
			{
				// Attach the post fields to the request
				$options[CURLOPT_POSTFIELDS] = $post;
			}
		}
		elseif (($query = $this->as_query()))
		{
			// Append the parameters to the query string
			$url = "{$url}?{$query}";
		}

		$response = Core::remote($url, $options);

		// check if it's a json string
		if ($this->name === 'access' and strpos(trim($response), '{') === 0)
		{
			$response = http_build_query(json_decode($response, true));
		}

		return $response;
	}
}
