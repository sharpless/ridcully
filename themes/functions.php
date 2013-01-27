<?php

function base_uri($uri)
{
  return CRidcully::Instance()->request->base_uri . trim($uri, '/');
}

function current_uri()
{
  return CRidcully::Instance()->request->current_uri;
}

function create_uri($uriOrController=null, $method=null, $arguments=null)
{
  return CRidcully::Instance()->request->createUri($uriOrController, $method, $arguments);
}

/**
 * Render view
 * @param  string $region the region to be drawn
 * @return mixed         the resulting view
 */
function render_views($region='default') {
  return CRidcully::Instance()->views->Render($region);
}

/**
 * Check if region has views
 * @param  string $region the region(s) to be checked
 * @return boolean
 */
function region_has_content($region='default' /*...*/) {
  return CRidcully::Instance()->views->RegionHasView(func_get_args());
}

/**
* Print debuginformation from the framework.
*/
function get_debug() {
  // Only if debug is wanted.
  $r = CRidcully::Instance();
  if(empty($r->config['debug'])) {
    return;
  }
  
  // Get the debug output
  $html = null;
  if(isset($r->config['debug']['db-num-queries']) && $r->config['debug']['db-num-queries'] && isset($r->db)) {
    $flash = $r->session->GetFlash('database_numQueries');
    $flash = $flash ? "$flash + " : null;
    $html .= "<p>Database made $flash" . $r->db->GetNumQueries() . " queries.</p>";
  }
  if(isset($r->config['debug']['db-queries']) && $r->config['debug']['db-queries'] && isset($r->db)) {
    $flash = $r->session->GetFlash('database_queries');
    $queries = $r->db->GetQueries();
    if($flash) {
      $queries = array_merge($flash, $queries);
    }
    $html .= "<p>Database made the following queries.</p><pre>" . implode('<br/><br/>', $queries) . "</pre>";
  }
  if(isset($r->config['debug']['timer']) && $r->config['debug']['timer']) {
    $html .= "<p>Page was loaded in " . round(microtime(true) - $r->timer['first'], 5)*1000 . " msecs.</p>";
  }
  if(isset($r->config['debug']['Ridcully']) && $r->config['debug']['Ridcully']) {
    $html .= "<hr><h3>Debuginformation</h3><p>The content of CRidcully:</p><pre>" . htmlent(print_r($r, true)) . "</pre>";
  }
  if(isset($r->config['debug']['session']) && $r->config['debug']['session']) {
    $html .= "<hr><h3>SESSION</h3><p>The content of CRidcully->session:</p><pre>" . htmlent(print_r($r->session, true)) . "</pre>";
    $html .= "<p>The content of \$_SESSION:</p><pre>" . htmlent(print_r($_SESSION, true)) . "</pre>";
  }
  return $html;
}

    /**
    * Get messages stored in flash-session.
    */
    function get_messages_from_session() {
      $messages = CRidcully::Instance()->session->GetMessages();
      $html = null;
      if(!empty($messages)) {
        foreach($messages as $val) {
          $valid = array('info', 'notice', 'success', 'warning', 'error', 'alert');
          $class = (in_array($val['type'], $valid)) ? $val['type'] : 'info';
          $html .= "<div class='$class'>{$val['message']}</div>\n";
        }
      }
      return $html;
    }
    
    /**
    * Login menu. Creates a menu which reflects if user is logged in or not.
    */
    function login_menu() {
      $r = CRidcully::Instance();
      if($r->user->IsAuthenticated()) {
        
        $items = "<a href='" . create_uri('user/profile') . "'><img class='gravatar' src='" . get_gravatar(20) . "' alt=''> " . $r->user->GetAcronym() . "</a> ";
        if($r->user["hasRoleAdmin"]) {
          $items .= "<a href='" . create_uri('acp') . "'>acp</a> ";
        }
        $items .= "<a href='" . create_uri('user/logout') . "'>logout</a> ";
      } else {
        $items = "<a href='" . create_uri('user/login') . "'>login</a> ";
      }
      return "<nav id='login-menu'>$items</nav>";
    }
    
    /**
* Get a gravatar based on the user's email.
*/
function get_gravatar($size=null) {
  return 'http://www.gravatar.com/avatar/' . md5(strtolower(trim(CRidcully::Instance()->user->profile["email"]))) . '.jpg?' . ($size ? "s=$size" : null);
}

function filter_data($data, $filter) {
    return CMContent::Filter($data, $filter);
}

/**
* Prepend the theme_url, which is the url to the current theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_url($url) {
  return create_uri(CRidcully::Instance()->themeUrl . "/{$url}");
}


/**
* Prepend the theme_parent_url, which is the url to the parent theme directory.
*
* @param $url string the url-part to prepend.
* @returns string the absolute url.
*/
function theme_parent_url($url) {
  return create_uri(CRidcully::Instance()->themeParentUrl . "/{$url}");
}

/**
* Escape data to make it safe to write in the browser.
*
* @param $str string to escape.
* @returns string the escaped string.
*/
function esc($str) {
  return htmlEnt($str);
}
