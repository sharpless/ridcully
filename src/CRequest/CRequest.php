<?php
/**
 * Parse the request and identify controller, method and arguments.
 *
 * @package RidcullyCore
 */
class CRequest {

	/**
	 * Member variables
	 */
	public $cleanUri;
  public $querystringUri;


	/**
	 * Constructor
	 *
	 * Default is to generate uri's of type index.php/controller/method/arg1/arg2/arg2
	 *
	 * @param boolean $clean generate clean uri's of type /controller/method/arg1/arg2/arg2
	 * @param boolean $querystring generate clean uri's of type index.php?q=controller/method/arg1/arg2/arg2
	 */
	public function __construct($uriType=0) {
    $this->cleanUri       = $uriType= 1 ? true : false;
    $this->querystringUri = $uriType= 2 ? true : false;
	}


	/**
	 * Create a uri in the way it should be created.
	 *
	 * @param $uri string the relative uri or the controller
	 * @param $method string the method to use, $uri is then the controller or empty for current
	 * @param $arguments string the extra arguments to send to the method
	 */
	public function CreateUri($uri=null, $method=null, $arguments=null) {
    // If fully qualified just leave it.
		if(!empty($uri) && (strpos($uri, '://') || $uri[0] == '/')) {
			return $uri;
		}
    
    // Get current controller if empty and method or arguments choosen
    if(empty($uri) && (!empty($method) || !empty($arguments))) {
      $uri = $this->controller;
    }
    
    // Get current method if empty and arguments choosen
    if(empty($method) && !empty($arguments)) {
      $method = $this->method;
    }
    
    // Create uri according to configured style
    $prepend = $this->base_uri;
    if($this->cleanUri) {
      ;
    } elseif ($this->querystringUri) {
      $prepend .= 'index.php?q=';
    } else {
      $prepend .= 'index.php/';
    }
    $uri = trim($uri, '/');
    $method = empty($method) ? null : '/' . trim($method, '/');
    $arguments = empty($arguments) ? null : '/' . trim($arguments, '/');    
    return $prepend . rtrim("$uri$method$arguments", '/');
  }


  /**
   * Parse the current url request and divide it in controller, method and arguments.
   *
   *  Calculates the base_url of the installation. Stores all useful details in $this
   * @param string $baseUri use this as a hardcoded baseurl
   * @param array $routing key/val to use for routing if url matches key
   */
  public function Init($baseUri = null, $routing=null) {
		// Take current uri and divide it in controller, method and arguments
		$requestUri = $_SERVER['REQUEST_URI'];
		$scriptPart = $scriptName = $_SERVER['SCRIPT_NAME'];    

		// Check if uri is in format controller/method/arg1/arg2/arg3
		if(substr_compare($requestUri, $scriptName, 0)) {
	    $scriptPart = dirname($scriptName);
		}

		// Set query to be everything after base_uri, except the optional querystring
		$query = trim(substr($requestUri, strlen(rtrim($scriptPart, '/'))), '/');
		$pos = strcspn($query, '?');
	    if($pos) {
	      $query = substr($query, 0, $pos);    
	    }
	    
		// Check if this looks like a querystring approach link
	    if(substr($query, 0, 1) === '?' && isset($_GET['q'])) {
	      $query = trim($_GET['q']);
	    }

	    // Check if url matches an entry in routing table
	    $routed_from = null;
	    if(is_array($routing) && isset($routing[$query]) && $routing[$query]['enabled']) {
	      $routed_from = $query;
	      $query = $routing[$query]['url'];
	    }

		$splits = explode('/', $query);
		
		// Set controller, method and arguments
		$controller =  !empty($splits[0]) ? $splits[0] : 'index';
		$method 		=  !empty($splits[1]) ? $splits[1] : 'index';
		$arguments = $splits;
		unset($arguments[0], $arguments[1]); // remove controller & method part from argument list
		
		// Prepare to create current_uri and base_uri
		$currentUri = $this->GetCurrentUri();
		$parts 	    = parse_url($currentUri);
		$baseUri 		= !empty($baseUri) ? $baseUri : "{$parts['scheme']}://{$parts['host']}" . (isset($parts['port']) ? ":{$parts['port']}" : '') . rtrim(dirname($scriptName), '/');
		
		// Store it
		$this->base_uri 	= rtrim($baseUri, '/') . '/';
		$this->current_uri  = $currentUri;
		$this->request_uri  = $requestUri;
		$this->script_name  = $scriptName;
		$this->routed_from	= $routed_from;
		$this->query	    = $query;
		$this->splits	    = $splits;
		$this->controller	= $controller;
		$this->method	    = $method;
		$this->arguments    = $arguments;
  }


	/**
	 * Get the uri to the current page. 
	 */
	public function GetCurrentUri() {
    $uri = "http";
    $uri .= (@$_SERVER["HTTPS"] == "on") ? 's' : '';
    $uri .= "://";
    $serverPort = ($_SERVER["SERVER_PORT"] == "80") ? '' :
    (($_SERVER["SERVER_PORT"] == 443 && @$_SERVER["HTTPS"] == "on") ? '' : ":{$_SERVER['SERVER_PORT']}");
    $uri .= $_SERVER["SERVER_NAME"] . $serverPort . htmlspecialchars($_SERVER["REQUEST_URI"]);
		return $uri;
	}


}