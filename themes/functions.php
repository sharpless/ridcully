<?php

function base_uri($uri)
{
  return CRidcully::Instance()->request->base_uri . trim($uri, '/');
}

function current_uri()
{
  return CRidcully::Instance()->request->current_uri;
}

function create_uri($uri)
{
  return CRidcully::Instance()->request->createUri($uri);
}

/**
* Render all views.
*/
function render_views() {
  return CRidcully::Instance()->views->Render();
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