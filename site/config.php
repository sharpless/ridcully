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
  'acp'       => array('enabled' => true, 'class' => 'CCAdminControlPanel'),
  'content'   => array('enabled' => true, 'class' => 'CCContent'),
  'blog'  	  => array('enabled' => true, 'class' => 'CCBlog'),
  'page'	    => array('enabled' => true, 'class' => 'CCPage'),
  'theme'     => array('enabled' => true, 'class' => 'CCTheme'),
  'modules'   => array('enabled' => true, 'class' => 'CCModules'),
  'my'        => array('enabled' => true,'class' => 'CCMycontroller'),
  );

/**
* Settings for the theme. The theme may have a parent theme.
*
* When a parent theme is used the parent's functions.php will be included before the current
* theme's functions.php. The parent stylesheet can be included in the current stylesheet
* by an @import clause. See site/themes/mytheme for an example of a child/parent theme.
* Template files can reside in the parent or current theme, the CLydia::ThemeEngineRender()
* looks for the template-file in the current theme first, then it looks in the parent theme.
*
* There are two useful theme helpers defined in themes/functions.php.
*  theme_url($url): Prepends the current theme url to $url to make an absolute url.
*  theme_parent_url($url): Prepends the parent theme url to $url to make an absolute url.
*
* path: Path to current theme, relativly LYDIA_INSTALL_PATH, for example themes/grid or site/themes/mytheme.
* parent: Path to parent theme, same structure as 'path'. Can be left out or set to null.
* stylesheet: The stylesheet to include, always part of the current theme, use @import to include the parent stylesheet.
* template_file: Set the default template file, defaults to default.tpl.php.
* regions: Array with all regions that the theme supports.
* data: Array with data that is made available to the template file as variables.
*
* The name of the stylesheet is also appended to the data-array, as 'stylesheet' and made
* available to the template files.
*/

$r->config['theme'] = array(
  'path'           => 'site/themes/modtheme',
  'name'           => 'modtheme',
  'parent'         => 'themes/new',
  'stylesheet'     => 'style.css',
  'template_file'  => 'index.tpl.php',
  'regions' => array('navbar','flash','featured-first','featured-middle','featured-last',
    'primary','sidebar','triptych-first','triptych-middle','triptych-last',
    'footer-column-one','footer-column-two','footer-column-three','footer-column-four',
    'footer',
    ),
  'menu_to_region' => array('my-navbar'=>'navbar'),
    'data' => array(
      'header' => '<h1>Welcome to Ridcully</h1>',
      'slogan' => 'A rudimentary PHP MVC framework',
      'footer' => '<p>Footer: &copy; Ridcully by Fredrik Larsson</p>',
      ),
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
$r->config['debug']['ridcully']       = true;
$r->config['debug']['session']        = true;
$r->config['debug']['timer']          = true;
$r->config['debug']['db-num-queries'] = true;
$r->config['debug']['db-queries']     = true;    
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

/**
* Define a routing table for urls.
*
* Route custom urls to a defined controller/method/arguments
*/
$r->config['routing'] = array(
  'home' => array('enabled' => true, 'url' => 'index/index'),
);

/**
* Define menus.
*
* Create hardcoded menus and map them to a theme region through $r->config['theme'].
*/
$r->config['menus'] = array(
  'navbar' => array(
    'home' => array('label'=>'Home', 'url'=>'home'),
    'modules' => array('label'=>'Modules', 'url'=>'modules'),
    'content' => array('label'=>'Content', 'url'=>'content'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'guestbook'),
    'blog' => array('label'=>'Blog', 'url'=>'blog'),
  ),
  'my-navbar' => array(
    'home' => array('label'=>'About Me', 'url'=>'my'),
    'blog' => array('label'=>'My Blog', 'url'=>'my/blog'),
    'guestbook' => array('label'=>'Guestbook', 'url'=>'my/guestbook'),
  ),
);
