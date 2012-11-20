<?php

/**
 * A guestbook using Ridcully
 * 
 * @package RidcullyCore
 */

class CMGuestBook extends CObject implements IHasSQL {

  public function __construct() {
    parent::__construct();
  }
  
  /**
  * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
  *
  * @param string $key the string that is the key of the wanted SQL-entry in the array.
  */
  public static function SQL($key=null) {
   $queries = array(
      'create table guestbook'  => "CREATE TABLE IF NOT EXISTS r_gb (post_id INTEGER PRIMARY KEY, post_message TEXT, post_author TEXT, post_time DATETIME default (datetime('now', 'localtime')));",
      'insert into guestbook'   => "INSERT INTO r_gb (post_message, post_author) VALUES (?, ?);",
      'select from guestbook' => 'SELECT * FROM r_gb ORDER BY post_id DESC;',
      'drop guestbook'          => 'DROP TABLE r_gb;',
   );
   if(!isset($queries[$key])) {
      throw new Exception("No such SQL query, key '$key' was not found.");
    }
    return $queries[$key];
  }

  public function Add($message, $author) {
    $this->database->ExecuteQuery(self::SQL('insert into guestbook'), array($message, $author));
    if ($this->database->RowCount() != 1) {
      die('Failed to insert new guestbook item into database.');
    }
    $this->AddMessage('info', 'Successfully saved message.');
  }
  
  public function DeleteAll() {
    $this->database->ExecuteQuery(self::SQL('drop guestbook'));
    $this->Init();
    $this->AddMessage('info', 'Removed all messages from the database table.');
  }
  
  public function Init() {
    
    try {
      $this->database->ExecuteQuery(self::SQL('create table guestbook'));
    }
    catch (Exception $e) {
      die("Failed to open database: " . $this->config['database'][0]['dsn'] . "</br>" . $e);
    }
  }
  
  public function ReadAll() {
    try {
      return $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('select from guestbook'));
    } catch (Exception $e) {
      return array();
    }
    
  }
}
?>
