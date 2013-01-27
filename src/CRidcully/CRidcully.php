<?php
/**
* The main class for Ridcully
*
* @package RidcullyCore
*/
class CRidcully implements ISingleton {

  private static $instance = null;
  public $request;
  public $data = array('main' => '',
      'title' => '',
      'header' => '',
      'footer' => '');
  

  /**
   * Singleton pattern. Get the instance of the latest created object or create a new one.
   * @return CRidcully The instance of this class.
   */
  public static function Instance() {
    if(self::$instance == null) {
      self::$instance = new CRidcully();
    }
    return self::$instance;
  }

  /**
   * Constructor
   */
  protected function __construct() {
    // time page generation
    $this->timer['first'] = microtime(true);
    // include the site specific config.php and create a ref to $r to be used by config.php
    $r = &$this;
    require(RIDCULLY_SITE_PATH.'/config.php');
    date_default_timezone_set($this->config['timezone']);
    $this->database = new CDatabase($this->config['database'][0]['dsn']);
    $this->views = new CViewContainer();
    // Start a named session
    session_name($this->config['session_name']);
    session_start();
    $this->session = new CSession($this->config['session_key']);
    $this->session->PopulateFromSession();
    // Create a object for the user
    $this->user = new CMUser($this);
  }

  /**
   * Frontcontroller, check url and route to controllers.
   */
  public function FrontControllerRoute() {
    // Take current url and divide it in controller, method and parameters
    $this->request = new CRequest();
    $this->request->Init($this->config['base_uri'], $this->config['routing']);
    $controller = $this->request->controller;
    $method     = $this->request->method;
    $arguments  = $this->request->arguments;
    // Is the controller enabled in config.php?
    $controllerExists    = isset($this->config['controllers'][$controller]);
    $controllerEnabled   = false;
    $className           = false;
    $classExists         = false;

    if($controllerExists) {
      $controllerEnabled    = ($this->config['controllers'][$controller]['enabled'] === true);
      $className            = $this->config['controllers'][$controller]['class'];
      $classExists          = class_exists($className);
    }
    // Check if controller has a callable method in the controller class, if then call it
    if($controllerExists && $controllerEnabled && $classExists) {
      $rc = new ReflectionClass($className);
      if($rc->implementsInterface('IController')) {
        if($rc->hasMethod($method)) {
          $controllerObj = $rc->newInstance();
          $methodObj = $rc->getMethod($method);
          $methodObj->invokeArgs($controllerObj, $arguments);
        } else {
          die("404. " . get_class() . ' error: Controller does not contain method.');
        }
      } else {
        die('404. ' . get_class() . ' error: Controller does not implement interface IController.');
      }
    }
    else {
      die('404. Page is not found.');
    }
  }

   /**
    * ThemeEngineRender, renders the reply of the request.
    */
  public function ThemeEngineRender() {
    $this->session->StoreInSession();

    // Is theme enabled?
    if(!isset($this->config['theme'])) { return; }
    
    // Get the paths and settings for the theme
    $themeName    = $this->config['theme']['name'];
    $themePath    = RIDCULLY_INSTALL_PATH . '/' . $this->config['theme']['path'];
    $themeUrl     = $this->request->base_uri . $this->config['theme']['path'];
   
    // Is there a parent theme?
    $parentPath = null;
    $parentUrl = null;
    if(isset($this->config['theme']['parent'])) {
      $parentPath = RIDCULLY_INSTALL_PATH . '/' . $this->config['theme']['parent'];
      $parentUrl  = $this->request->base_uri . $this->config['theme']['parent'];
    }
           
    // Add stylesheet path to the $r->data array
    $this->data['stylesheet'] = $this->config['theme']['stylesheet'];

    // Make the theme urls available as part of $ly
    $this->themeUrl = $themeUrl;
    $this->themeParentUrl = $parentUrl;

    // Include the global functions.php and the functions.php that are part of the theme
    $r = &$this;
    include_once RIDCULLY_INSTALL_PATH . '/themes/functions.php';
    if($parentPath) {
      if(is_file("{$parentPath}/functions.php")) {
        include "{$parentPath}/functions.php";
      }
    }
    if(is_file("{$themePath}/functions.php")) {
      include "{$themePath}/functions.php";
    }    
    // Map menu to region if defined
    if(is_array($this->config['theme']['menu_to_region'])) {
      foreach($this->config['theme']['menu_to_region'] as $key => $val) {
        $this->views->AddString($this->DrawMenu($key), array(), $val);
      }
    }
    // Extract $r->data and $r->view->data to own variables and handover to the template file
    extract($this->data);     
    extract($this->views->GetData());     
    if (isset($this->config['theme']['data'])) {
      extract($this->config['theme']['data']);
    }
    $templateFile = (isset($this->config['theme']['template_file'])) ? $this->config['theme']['template_file'] : 'default.tpl.php';
    if(is_file("{$themePath}/{$templateFile}")) {
      include("{$themePath}/{$templateFile}");
    } else if(is_file("{$parentPath}/{$templateFile}")) {
      include("{$parentPath}/{$templateFile}");
    } else {
      throw new Exception('No such template file.');
    }
  }

  /**
   * Redirect to another url and store the session
   * @param string $urlOrController the relative url or the controller
   * @param string $method          string the method to use, $url is then the controller or empty for current controller
   */
  public function RedirectTo($urlOrController=null, $method=null) {
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
  public function RedirectToController($method=null) {
    $this->RedirectTo($this->request->controller, $method);
  }


  /**
   * Redirect to a controller and method. Uses RedirectTo().
   *
   * @param string controller name the controller or null for current controller.
   * @param string method name the method, default is current method.
   */
  public function RedirectToControllerMethod($controller=null, $method=null) {
    $controller = is_null($controller) ? $this->request->controller : null;
    $method = is_null($method) ? $this->request->method : null;   
    $this->RedirectTo($this->request->CreateUrl($controller, $method));
  }
  
    /**
   * Save a message in the session. Uses $this->AddMessage()
   *
   * @param string $type the type of message, for example: notice, info, success, warning, error.
   * @param string $message the message.
   * @param string $alternative the message if the $type is set to false, defaults to null.
   */
  public function AddMessage($type, $message, $alternative=null) {
    if($type === false) {
      $type = 'error';
      $message = $alternative;
    } else if($type === true) {
      $type = 'success';
    }
    $this->session->AddMessage($type, $message);
  }
  /**
   * Create an url. Wrapper and shorter method for $this->request->CreateUrl()
   *
   * @param $urlOrController string the relative url or the controller
   * @param $method string the method to use, $url is then the controller or empty for current
   * @param $arguments string the extra arguments to send to the method
   */
  public function CreateUri($urlOrController=null, $method=null, $arguments=null) {
    return $this->request->CreateUri($urlOrController, $method, $arguments);
  }
  /**
   * Draw HTML for a menu defined in $ly->config['menus'].
   *
   * @param $menu string then key to the menu in the config-array.
   * @returns string with the HTML representing the menu.
   */
  public function DrawMenu($menu) {
    $items = null;
    if(isset($this->config['menus'][$menu])) {
      foreach($this->config['menus'][$menu] as $val) {
        $selected = null;
        if($val['url'] == $this->request->query || $val['url'] == $this->request->routed_from) {
          $selected = " class='selected'";
        }
        $items .= "<li><a {$selected} href='" . $this->CreateUri($val['url']) . "'>{$val['label']}</a></li>\n";
      }
    } else {
      throw new Exception('No such menu.');
    }     
    return "<ul class='menu {$menu}'>\n{$items}</ul>\n";
  }
}
