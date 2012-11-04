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

  protected function __construct() {
    $r = CRidcully::Instance();
    $this->config   = &$r->config;
    $this->data     = &$r->data;
    $this->request  = &$r->request;
    $this->database = &$r->database;
    $this->views    = &$r->views;
    $this->session  = &$r->session;
  }

}
?>
