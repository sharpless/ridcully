<?php
/**
* Standard controller layout.
*
* @package ridcullyCore
*/
class CCDeveloper implements IController {

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
     $r = CRidcully::Instance();

     $uri = "developer/links";
     $current = $r->Instance()->request->CreateUri($uri);

     $r->request->cleanUri = false;
     $r->request->queryStringUri = false;
     $default = $r->Instance()->request->CreateUri($uri);

     $r->request->cleanUri = true;
     $clean = $r->Instance()->request->CreateUri($uri);

     $r->request->cleanUri = false;
     $r->request->queryStringUri = true;
     $querystring = $r->Instance()->request->CreateUri($uri);

     $r->data['main'] .= <<<EOD
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
    $r = CRidcully::Instance();
    $menu = array('developer', 'developer/index', 'developer/links');
    
    $html = null;
    foreach($menu as $uri) {
      $html .= "<li><a href='" . $r->request->CreateUri($uri) . "'>$uri</a>\n";  
    }
    
    $r->data['title'] = "The Developer Controller";
    $r->data['header'] = "";
    $r->data['main'] = <<<EOD
<h1>The Developer Controller</h1>
<p>This is what you can do for now:</p>
<ul>
$html
</ul>
EOD;
  }
  
}