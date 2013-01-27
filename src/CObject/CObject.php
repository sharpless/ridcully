<?php

/**
 * Base class for enabling access to Ridcully through $this
 * 
 * @package RidcullyCore
 * @author Fredrik Larsson <fredrik@sharpless.se>
 * 
 */

class CObject {

  public $config;
  public $data;
  public $request;
  public $database;
  public $views;
  public $session;

  protected function __construct($r=null) {
    if (!$r) {
        $r = CRidcully::Instance();
    }
    $this->r        = &$r;
    $this->config   = &$r->config;
    $this->data     = &$r->data;
    $this->request  = &$r->request;
    $this->database = &$r->database;
    $this->views    = &$r->views;
    $this->session  = &$r->session;
    $this->user     = &$r->user;
  }
	/**
	 * A wrapper for CRidcully::RedirectTo
	 */
	protected function RedirectTo($urlOrController=null, $method=null) {
    $this->r->RedirectTo($urlOrController=null, $method=null);
  }


	/**
	 * A wrapper for CRidcully::RedirectToController
	 *
	 * @param string method name the method, default is index method.
	 */
	protected function RedirectToController($method=null) {
    $this->r->RedirectToController($method);
  }


	/**
	 * A wrapper for CRidcully::RedirectToControllerMethod
	 *
	 * @param string controller name the controller or null for current controller.
	 * @param string method name the method, default is current method.
	 */
	protected function RedirectToControllerMethod($controller=null, $method=null) {
	  $this->r->RedirectToControllerMethod($controller, $method);
  }
  
  /**
	 * A wrapper for CRidcully::AddMessage
	 *
   * @param $type string the type of message, for example: notice, info, success, warning, error.
   * @param $message string the message.
   * @param $alternative string the message if the $type is set to false, defaults to null.
   */
  protected function AddMessage($type, $message, $alternative=null) {
    $this->r->AddMessage($type, $message, $alternative=null);
  }
}
?>
