<?php
/**
* Standard controller layout.
*
* @package RidcullyCore
*/
class CCDeveloper extends CObject implements IController {

  public function __construct() {
    parent::__construct();
  }

  /**
    * Implementing interface IController. All controllers must have an index action.
    */
   public function Index() {   
      $this->Menu();
   }

   /**
    * Create links in the three different ways supported
    */
   public function Links() {
     $this->Menu();

     $uri = "developer/links";
     $current = $this->request->CreateUri($uri);

     $this->request->cleanUri = false;
     $this->request->queryStringUri = false;
     $default = $this->request->CreateUri($uri);

     $this->request->cleanUri = true;
     $clean = $this->request->CreateUri($uri);

     $this->request->cleanUri = false;
     $this->request->queryStringUri = true;
     $querystring = $this->request->CreateUri($uri);

     $this->data['main'] .= <<<EOD
<h2>CRequest::CreateUrl()</h2>
<p>Here is a list of urls created using above method with various settings. All links should lead to
this same page.</p>
<ul>
<li><a href='$current'>This is the current setting</a>
<li><a href='$default'>This would be the default url</a>
<li><a href='$clean'>This should be a clean url</a>
<li><a href='$querystring'>This should be a querystring like url</a>
</ul>
<p>Enables various and flexible url-strategy.</p>
EOD;
   }
  /**
   * Create a method that shows the menu, same for all methods
   */
  private function Menu() {  
    $menu = array('developer', 'developer/index', 'developer/links');
    
    $html = null;
    foreach($menu as $uri) {
      $html .= "<li><a href='" . $this->request->CreateUri($uri) . "'>$uri</a>\n";  
    }
    
    $this->data['title'] = "The Developer Controller";
    $this->data['header'] = "";
    $this->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
}