<?php

/**
 * A model for content stored in database
 * 
 * @package RidcullyCore
 */

class CMContent extends CObject implements IHasSQL, ArrayAccess, IModule {
   
  /**
   * Properties
   */
  public $data;
  
  public function __construct($id = null) {
    parent::__construct();
    if ($id) {
      $this->LoadById($id);
    } else {
      $this->data = array();
    }
  }
  
  /**
   * Implementing ArrayAccess for $this->data
   */
  public function offsetSet($offset, $value) { if (is_null($offset)) { $this->data[] = $value; } else { $this->data[$offset] = $value; }}
  public function offsetExists($offset) { return isset($this->data[$offset]); }
  public function offsetUnset($offset) { unset($this->data[$offset]); }
  public function offsetGet($offset) { return isset($this->data[$offset]) ? $this->data[$offset] : null; }
  
  /**
   * Implementing IHasSQL
   * 
   * @param string $key the string that is the key of the wanted SQL-entry in the array.
   */
  
  public static function SQL($key=null) {
    $order_order  = isset($args['order-order']) ? $args['order-order'] : 'ASC';
    $order_by     = isset($args['order-by'])    ? $args['order-by'] : 'id'; 
    $queries = array(
      'drop table content'        => "DROP TABLE IF EXISTS r_content;",
      'create table content'      => "CREATE TABLE IF NOT EXISTS r_content (id INTEGER PRIMARY KEY, key TEXT KEY, type TEXT, title TEXT, data TEXT, filter TEXT, idUser INT, created DATETIME default (datetime('now')), updated DATETIME default NULL, deleted DATETIME default NULL, FOREIGN KEY(idUser) REFERENCES r_user(id));",
      'insert content'            => 'INSERT INTO r_content (key,type,title,data,filter,idUser) VALUES (?,?,?,?,?,?);',
      'select * by id'            => 'SELECT c.*, u.acronym as owner FROM r_content AS c INNER JOIN r_user as u ON c.idUser=u.id WHERE c.id=? AND deleted IS NULL;',
      'select * by key'           => 'SELECT c.*, u.acronym as owner FROM r_content AS c INNER JOIN r_user as u ON c.idUser=u.id WHERE c.key=? AND deleted IS NULL;',
      'select * by type'          => "SELECT c.*, u.acronym as owner FROM r_content AS c INNER JOIN r_user as u ON c.idUser=u.id WHERE type=?  AND deleted IS NULL ORDER BY {$order_by} {$order_order};",
      'select *'                  => 'SELECT c.*, u.acronym as owner FROM r_content AS c INNER JOIN r_user as u ON c.idUser=u.id AND deleted IS NULL;',
      'update content'            => "UPDATE r_content SET key=?, type=?, title=?, data=?, filter=?, updated=datetime('now') WHERE id=? AND deleted IS NULL;",
      'update content as deleted' => "UPDATE r_content SET deleted = datetime('now') WHERE id=?",
    );
    if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }
  


  /**
   * Install the database and create appropriate tables, implements IModule
   * @param string $action What to do
   */
  public function Manage($action=null) {
    switch ($action) {
      case 'install':
        try {
          $this->database->ExecuteQuery(self::SQL('drop table content'));
          $this->database->ExecuteQuery(self::SQL('create table content'));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('hello-world', 'post', 'Hello World', "This is a demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('hello-world-again', 'post', 'Hello World Again', "This is another demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('hello-world-once-more', 'post', 'Hello World Once More', "This is one more demo post.\n\nThis is another row in this demo post.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('home', 'page', 'Home page', "This is a demo page, this could be your personal home-page.\n\nRidcully is a PHP-based MVC-inspired Content management Framework, used for educational purposes, based on Lydia: http://dbwebb.se/lydia/tutorial.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('about', 'page', 'About page', "This is a demo page, this could be your personal about-page.\n\nRidcully is used as a tool to educate in MVC frameworks.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('download', 'page', 'Download page', "This is a demo page, this could be your personal download-page.\n\nYou can download your own copy of Ridcully from https://github.com/sharpless/ridcully\n\nYou can download your own copy of lydia from https://github.com/mosbth/lydia.", 'plain', $this->user['id']));
          $this->database->ExecuteQuery(self::SQL('insert content'), array('bbcode', 'page', 'Page with BBCode', "This is a demo page with some BBCode-formatting.\n\n[b]Text in bold[/b] and [i]text in italic[/i] and [url=http://dbwebb.se]a link to dbwebb.se[/url]. You can also include images using bbcode, such as the lydia logo: [img]http://dbwebb.se/lydia/current/themes/core/logo_80x80.png[/img]", 'bbcode', $this->user['id']));
          return array('success', 'Successfully created the database tables and created a default "Hello World" blog post, owned by you.');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
        break;
      
      default:
        throw new Exception("Unsupported action");
        break;
    }

  }
  /**
   * Save content. If it has a id, use it to update current entry or else insert new entry.
   *
   * @returns boolean true if success else false.
   */
  public function Save() {
    $msg = null;
    if($this['id']) {
      $this->database->ExecuteQuery(self::SQL('update content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this['id']));
      $msg = 'updated';
    } else {
      $this->database->ExecuteQuery(self::SQL('insert content'), array($this['key'], $this['type'], $this['title'], $this['data'], $this['filter'], $this->user["id"]));
      $this['id'] = $this->database->LastInsertId();
      $msg = 'created';
    }
    $rowcount = $this->database->RowCount();
    if($rowcount) {
    $this->AddMessage('success', "Successfully {$msg} content '".htmlent($this['key'])."'.");
    } else {
    $this->AddMessage('error', "Failed to {$msg} content '".htmlent($this['key'])."'.");
    }
    return $rowcount === 1;
  }
  
  /**
   * Load content by id.
   *
   * @param id integer the id of the content.
   * @returns boolean true if success else false.
   */
  public function LoadById($id) {
    $res = $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('select * by id'), array($id));
    if(empty($res)) {
      $this->AddMessage('error', "Failed to load content with id '$id'.");
      return false;
    } else {
      $this->data = $res[0];
    }
    return true;
  }
 
 
  /**
   * List all content.
   * @param array $args Array with arguments
   */
  public function ListAll($args=null) {
    try {
      if(isset($args) && isset($args['type'])) {
        return $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('select * by type', $args), array($args['type']));
      } else {
        return $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('select *', $args));
      }
    } catch(Exception $e) {
      echo $e;
      return null;
    }
  }
  /**
   * Filter content according to a filter.
   *
   * @param $data string of text to filter and format according its filter settings.
   * @returns string with the filtered data.
   */
  public static function Filter($data, $filter) {
    switch($filter) {
      case 'html': $data = nl2br(CHTMLPurifier::Purify($data)); break;
      case 'bbcode': $data = nl2br(bbcode2html(htmlent($data))); break;
      case 'plain':
      default: $data = nl2br(htmlent($data)); break;
    }
    return $data;
  }    
  /**
   * Get filtered data through Filter
   * 
   * @return string Filtered data
   */
  
  public function GetFilteredData() {
    return $this->Filter($this['data'], $this['filter']);
  }

  /**
   * Delete content, or rather mark it as deleted with a deleted date
   *
   * @return boolean true if successful, else false
   */
  public function Delete()
  {
    if($this['id']) {
      $this->database->ExecuteQuery(self::SQL('update content as deleted'), array($this['id']));
    }
    $rowcount = $this->database->RowCount();
    if ($rowcount) {
      $this->AddMessage('success', "Successfully set content '" . htmlent($this['key']) . "' as deleted.");
    } else {
      $this->AddMessage('error', "Failed to set content '" . htmlent($this['key']) . "' as deleted.");
    }
    return $rowcount === 1;
  }

}