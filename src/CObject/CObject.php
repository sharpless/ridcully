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
    $this->config   = &$r->config;
    $this->data     = &$r->data;
    $this->request  = &$r->request;
    $this->database = &$r->database;
    $this->views    = &$r->views;
    $this->session  = &$r->session;
    $this->user     = &$r->user;
  }
	/**
	 * Redirect to another url and store the session
	 */
	protected function RedirectTo($urlOrController=null, $method=null) {
    $r = CRidcully::Instance();
    if(isset($r->config['debug']['db-num-queries']) && $r->config['debug']['db-num-queries'] && isset($r->db)) {
      $this->session->SetFlash('database_numQueries', $this->db->GetNumQueries());
    }    
    if(isset($r->config['debug']['db-queries']) && $r->config['debug']['db-queries'] && isset($r->db)) {
      $this->session->SetFlash('database_queries', $this->db->GetQueries());
    }    
    if(isset($r->config['debug']['timer']) && $r->config['debug']['timer']) {
	    $this->session->SetFlash('timer', $r->timer);
    }    
    $this->session->StoreInSession();
    header('Location: ' . $this->request->CreateUri($urlOrController, $method));
  }


	/**
	 * Redirect to a method within the current controller. Defaults to index-method. Uses RedirectTo().
	 *
	 * @param string method name the method, default is index method.
	 */
	protected function RedirectToController($method=null) {
    $this->RedirectTo($this->request->controller, $method);
  }


	/**
	 * Redirect to a controller and method. Uses RedirectTo().
	 *
	 * @param string controller name the controller or null for current controller.
	 * @param string method name the method, default is current method.
	 */
	protected function RedirectToControllerMethod($controller=null, $method=null) {
	  $controller = is_null($controller) ? $this->request->controller : null;
	  $method = is_null($method) ? $this->request->method : null;	  
    $this->RedirectTo($this->request->CreateUrl($controller, $method));
  }
  
  	/**
	 * Save a message in the session. Uses $this->AddMessage()
	 *
   * @param $type string the type of message, for example: notice, info, success, warning, error.
   * @param $message string the message.
   * @param $alternative string the message if the $type is set to false, defaults to null.
   */
  protected function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }
}
?>
