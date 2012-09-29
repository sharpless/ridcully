<?php
/**
 * Gatekeeper: Handle all requests
 * 
 * @package ridcully
 * 
 */
//
// Bootstrap
//

define('RIDCULLY_INSTALL_PATH', dirname(__FILE__));
define('RIDCULLY_SITE_PATH', RIDCULLY_INSTALL_PATH . '/site');

require(RIDCULLY_INSTALL_PATH . '/src/CRidcully/bootstrap.php');

$r = CRidcully::Instance();

//
// Frontcontroller Route
//

$r->FrontControllerRoute();

//
// Theme Engine Render
//

$r->ThemeEngineRender();

