<?php
/**
* Site configuration, this file is changed by user per site.
*
*/

/*
* Set level of error reporting
*/
error_reporting(-1);
ini_set('display_errors', 1);

/*
 * Set short tags
 */
if(ini_get('short_open_tag')) {
  ini_set('short_open_tag', 1);
}

/*
* Define session name
*/
$r->config['session_name'] = preg_replace('/[:\.\/-_]/', '', $_SERVER["SERVER_NAME"]);

/*
* Define server timezone
*/
$r->config['timezone'] = 'Europe/Stockholm';

/*
* Define internal character encoding
*/
$r->config['character_encoding'] = 'UTF-8';

/*
* Define language
*/
$r->config['language'] = 'en';

/**
* Define the controllers, their classname and enable/disable them.
*
* The array-key is matched against the url, for example:
* the url 'developer/dump' would instantiate the controller with the key "developer", that is
* CCDeveloper and call the method "dump" in that class. This process is managed in:
* $r->FrontControllerRoute();
* which is called in the frontcontroller phase from index.php.
*/
$r->config['controllers'] = array(
  'index'     => array('enabled' => true, 'class' => 'CCIndex'),
  'developer' => array('enabled' => true, 'class' => 'CCDeveloper'),
  'guestbook' => array('enabled' => true, 'class' => "CCGuestBook"),
  'user'      => array('enabled' => true, 'class' => "CCUser"),
  'acp'       => array('enabled' => true,'class' => 'CCAdminControlPanel'),
);

/*
 * Set which theme to use
 */

$r->config['theme'] = array(
  'name' => 'default'
    );
/**
* Set a base_uri to use if the auto-detected does not work
*/
$r->config['base_uri'] = null;

/**
* Set the type of URI to be used
*
* default      = 0      => index.php/controller/method/arg1/arg2
* clean        = 1      => controller/method/arg1/arg2
* querystring  = 2      => index.php?q=/controller/method/arg1/arg2
*/
$r->config['url_type'] = 1;

/**
* Set what to show as debug or developer information in the get_debug() theme helper.
*/
$r->config['debug']['ridcully'] = true;
$r->config['debug']['session'] = true;
$r->config['debug']['timer'] = true;
$r->config['debug']['db-num-queries'] = true;
$r->config['debug']['db-queries'] = true;    
/**
 * Set database
 */
$r->config['database'][0]['dsn'] = 'sqlite:' . RIDCULLY_SITE_PATH . '/data/.ht.sqlite';

/**
 * Set session name
 */

$r->config['session_key'] = 'ridcully';

$r->config['timezone'] = 'Europe/Stockholm';

$r->config['create_new_users'] = true;