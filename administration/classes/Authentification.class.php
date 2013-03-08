<?php
require_once(__DIR__ . '/../../inc/config.inc.php');

class Authentification
{
  public static $mysqli;

  public static function Check($username, $password, &$id, &$privileges, &$licensetype) {
    $username = self::$mysqli->real_escape_string($username);
    $result = self::$mysqli->query("SELECT id, password_hash, password_salt, privileges FROM ws_accounts WHERE name='$username' LIMIT 0, 1");

    if ($result->num_rows > 0) {
      $row = $result->fetch_object();
      
      if(hash('sha512', $row->password_salt . $password) == $row->password_hash) {
        $privileges = (int)$row->privileges;
        $licensetype = (int)$row->licensetype;
        $id = (int)$row->id;
        return true;
      }
      else {
        return false;
      }
    }
    else {
      return false;
    }
  }

  public static function Authentificate($username, $id, $privileges, $licensetype) {
      session_name(SESSION_NAME);
      session_start();
      
      $_SESSION['authed'] = true;
      $_SESSION['name'] = $username;
      $_SESSION['id'] = $id;
      $_SESSION['privileges'] = $privileges;
      $_SESSION['licensetype'] = $licensetype;
  }
}
?>