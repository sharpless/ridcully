<?php

/**
 * Handle user authentication
 * 
 * @package RidcullyCore
 * 
 */
require_once 'PasswordHash.php';

class CMUser extends CObject implements IHasSQL {
    
    private $phpass;


    public function __construct($r=null) {
        parent::__construct($r);
        $this->phpass = new PasswordHash(8, false);
        $profile = $this->session->GetAuthenticatedUser();
        $this->profile = is_null($profile) ? array() : $profile;
        $this->isAuthenticated = is_null($profile) ? false : true;
        if(!$this->isAuthenticated) {
            $this->id = 1;
            $this->acronym = 'anonymous';
            $this->hasRoleAnonymous = true;
        }
      }


      /**
       * Implementing interface IHasSQL. Encapsulate all SQL used by this class.
       *
       * @param string $key the string that is the key of the wanted SQL-entry in the array.
       */
      public static function SQL($key=null) {
        $queries = array(
          'drop table user'         => "DROP TABLE IF EXISTS r_user;",
          'drop table group'        => "DROP TABLE IF EXISTS r_groups;",
          'drop table user2group'   => "DROP TABLE IF EXISTS r_user_groups;",
          'create table user'       => "CREATE TABLE IF NOT EXISTS r_user (id INTEGER PRIMARY KEY, acronym TEXT UNIQUE, name TEXT, email TEXT UNIQUE, password TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
          'create table group'      => "CREATE TABLE IF NOT EXISTS r_groups (id INTEGER PRIMARY KEY, acronym TEXT KEY, name TEXT, created DATETIME default (datetime('now')), updated DATETIME default NULL);",
          'create table user2group' => "CREATE TABLE IF NOT EXISTS r_user_groups (idUser INTEGER, idGroups INTEGER, created DATETIME default (datetime('now')), PRIMARY KEY(idUser, idGroups));",
          'insert into user'        => 'INSERT INTO r_user (acronym,name,email,password) VALUES (?,?,?,?);',
          'insert into group'       => 'INSERT INTO r_groups (acronym,name) VALUES (?,?);',
          'insert into user2group'  => 'INSERT INTO r_user_groups (idUser,idGroups) VALUES (?,?);',
          'check user password'     => 'SELECT * FROM r_user WHERE (acronym=? OR email=?);',
          'update profile'          => 'UPDATE r_user SET name=?, email=?, updated=datetime("now") WHERE id=?',
          'update password'         => 'UPDATE r_user SET password=?, updated=datetime("now") WHERE id=?',
          'get group memberships'   => 'SELECT * FROM r_groups AS g INNER JOIN r_user_groups AS ug ON g.id=ug.idGroups WHERE ug.idUser=?;',
         );
        if(!isset($queries[$key])) {
          throw new Exception("No such SQL query, key '$key' was not found.");
        }
        return $queries[$key];
      }

      /**
       * Init the database and create appropriate tables.
       */
      public function Init() {
        try {
          $root = $this->phpass->HashPassword('root');
          $doe = $this->phpass->HashPassword('doe');
          $this->database->ExecuteQuery(self::SQL('drop table user2group'));
          $this->database->ExecuteQuery(self::SQL('drop table group'));
          $this->database->ExecuteQuery(self::SQL('drop table user'));
          $this->database->ExecuteQuery(self::SQL('create table user'));
          $this->database->ExecuteQuery(self::SQL('create table group'));
          $this->database->ExecuteQuery(self::SQL('create table user2group'));
          $this->database->ExecuteQuery(self::SQL('insert into user'), array('root', 'The Administrator', 'root@dbwebb.se', $root));
          $idRootUser = $this->database->LastInsertId();
          $this->database->ExecuteQuery(self::SQL('insert into user'), array('doe', 'John/Jane Doe', 'doe@dbwebb.se', $doe));
          $idDoeUser = $this->database->LastInsertId();
          $this->database->ExecuteQuery(self::SQL('insert into group'), array('admin', 'The Administrator Group'));
          $idAdminGroup = $this->database->LastInsertId();
          $this->database->ExecuteQuery(self::SQL('insert into group'), array('user', 'The User Group'));
          $idUserGroup = $this->database->LastInsertId();
          $this->database->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idAdminGroup));
          $this->database->ExecuteQuery(self::SQL('insert into user2group'), array($idRootUser, $idUserGroup));
          $this->database->ExecuteQuery(self::SQL('insert into user2group'), array($idDoeUser, $idUserGroup));
          $this->session->AddMessage('notice', 'Successfully created the database tables and created a default admin user as root:root and an ordinary user as doe:doe.');
        } catch(Exception$e) {
          die("$e<br/>Failed to open database: " . $this->config['database'][0]['dsn']);
        }
      }
     
      /**
       * Login by autenticate the user and password. Store user information in session if success.
       *
       * @param string $akronymOrEmail the emailadress or user akronym.
       * @param string $password the password that should match the akronym or emailadress.
       * @returns booelan true if match else false.
       */
      public function Login($akronymOrEmail, $password) {
        $user = $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('check user password'), array($akronymOrEmail, $akronymOrEmail));
        $user = (isset($user[0])) ? $user[0] : null;
          if (isset($user->password)) {
              $check = $this->phpass->CheckPassword($password, $user->password);
              if (!$check) {
                  $user = NULL;
              }
          }
          unset($user->password);
        if($user) {
          $user->groups = $this->database->ExecuteSelectQueryAndFetchAll(self::SQL('get group memberships'), array($user->id));
           foreach($user->groups as $val) {
            if($val->id == 1) {
              $user->hasRoleAdmin = true;
            }
            if($val->id == 2) {
              $user->hasRoleUser = true;
            }
          }
            $this->profile = $user;
            $this->session->SetAuthenticatedUser($this->profile);
            $this->session->AddMessage('success', "Welcome '{$user->name}'.");
            $this->acronym = $user->acronym;
            $this->mail = $user->mail;
        } else {
          $this->session->AddMessage('notice', "Could not login, user does not exists or password did not match.");
        }
        return ($user != null);
      }

      /**
       * Logout.
       */
      public function Logout() {
        $this->session->UnsetAuthenticatedUser();
        $this->session->AddMessage('success', "You have logged out.");
      }
     

      /**
       * Does the session contain an authenticated user?
       *
       * @returns boolen true or false.
       */
      public function IsAuthenticated() {
        return ($this->session->GetAuthenticatedUser() != false);
      }
     
     
      /**
       * Get profile information on user.
       *
       * @returns array with user profile or null if anonymous user.
       */
      public function GetProfile() {
        return $this->session->GetAuthenticatedUser();
      }
     
      /**
       * Get the user acronym.
       *
       * @returns string with user acronym or null
       */
      public function GetAcronym() {
        $profile = $this->GetProfile();
        return isset($profile->acronym) ? $profile->acronym : null;
      }
  /**
   * Does the user have the admin role?
   *
   * @returns boolen true or false.
   */
  public function IsAdministrator() {
    $profile = $this->GetProfile();
    return isset($profile->hasRoleAdmin) ? $profile->hasRoleAdmin : null;
  }
  
  
  /**
* Save user profile to database and update user profile in session.
*
* @returns boolean true if success else false.
*/
  public function Save() {
    $this->database->ExecuteQuery(self::SQL('update profile'), array($this->profile->name, $this->profile->email, $this->profile->id));
    $this->session->SetAuthenticatedUser($this->profile);
    return $this->database->RowCount() === 1;
  }
  
  
  /**
* Change user password.
*
* @param $password string the new password
* @returns boolean true if success else false.
*/
  public function ChangePassword($password) {
    $this->database->ExecuteQuery(self::SQL('update password'), array($password, $this->profile->id));
    return $this->database->RowCount() === 1;
  }
  
  
  /**
* Create new user.
*
* @param $acronym string the acronym.
* @param $password string the password plain text to use as base.
* @param $name string the user full name.
* @param $email string the user email.
* @returns boolean true if user was created or else false and sets failure message in session.
*/
  public function Create($acronym, $password, $name, $email) {
    $pwd = $this->phpass->HashPassword($password);
    $this->database->ExecuteQuery(self::SQL('insert into user'), array($acronym, $name, $email, $pwd));
    if($this->database->RowCount() == 0) {
      $this->session->AddMessage('error', "Failed to create user.");
      return false;
    }
    return true;
  }
}
?>
