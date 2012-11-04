<?php
/**
* Parse the request and identify controller, method and arguments.
*
* @package RidcullyCore
*/
class CRequest {

  public $cleanUri;
  public $queryStringUri;
  public $request_uri;
  public $script_name;
  public $base_uri;
  public $query;
  public $splits;
  public $controller;
  public $method;
  public $arguments;
  
  
  /**
   * Constructor
   * 
   * Set which type of URI to be used
   * Default      = 0 => index.php/controller/action/arg1/arg2
   * Clean        = 1 => controller/action/arg1/arg2
   * Querystring  = 2 => index.php?q=/controller/action/arg1/arg2
   * 
   * @param int $uriType Sets the type of URI to be used
   */
  public function __construct($uriType = 0)
  {
    $this->cleanUri       = ($uriType = 1) ? true : false;
    $this->queryStringUri = ($uriType = 2) ? true : false;
  }

  /**
   * Init the object by parsing the current uri request.
   * 
   * @param string $baseUri The optional base path for Ridcully, when autodetect
   * does not work
   */
  public function Init($baseUri = null) {
    // Take current uri and divide it in controller, method and arguments
    $query = substr($_SERVER['REQUEST_URI'], strlen(rtrim(dirname($_SERVER['SCRIPT_NAME']), '/')));
    $splits = explode('/', trim($query, '/'));

    // Try to handle index.php/controller or index.php?q=/controller
    if (!empty($splits[0]) && preg_match('/^index.php/', $splits[0])) {
      array_shift($splits);
    }
    
    // Try to get base URI
    if (empty($baseUri)) {
      $baseUri = preg_replace('/index.php$/', '', $_SERVER['SCRIPT_NAME']);
    }
    $baseUri = '/' . trim($baseUri, '/') . '/';
    
    // Set controller, method and arguments
    $controller =  !empty($splits[0]) ? $splits[0] : 'index';
    $method       =  !empty($splits[1]) ? $splits[1] : 'index';
    $arguments = $splits;
    unset($arguments[0], $arguments[1]); // remove controller & method part from argument list

    // Store it
    $this->request_uri  = $_SERVER['REQUEST_URI'];
    $this->script_name  = $_SERVER['SCRIPT_NAME'];
    $this->base_uri     = $baseUri;
    $this->query        = $query;
    $this->splits       = $splits;
    $this->controller   = $controller;
    $this->method       = $method;
    $this->arguments    = $arguments;
  }

  /**
   * Create a fullpath URI from controller/action-path
   * 
   * @param string $uri controller/action relative URI
   * 
   * @return string The full URI
   */
  public function CreateUri($uri = null)
  {
    $prepend = $this->base_uri;
    if ($this->cleanUri) {
      $prepend .= '';
    } elseif ($this->queryStringUri) {
      $prepend .= "index.php?q=/";
    } else {
      $prepend .= "index.php/";
    }
    return $prepend . trim($uri, '/');
  }
}