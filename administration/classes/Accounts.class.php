<?php
require_once(__DIR__ . '/../../inc/config.inc.php');

class Accounts
{
  public static $mysqli;

  public static function listAccounts() {
    $result = self::$mysqli->query("SELECT id, name, privileges, last_heartbeat, email, display_name FROM ws_accounts");
    $return = array();
    echo self::$mysqli->error;
    while ($object = $result->fetch_object()) {
      array_push($return, array(
        'id' => (int)$object->id,
        'name' => $object->name,
        'privileges' => (int)$object->privileges,
        'last_heartbeat' => $object->last_heartbeat,
        'email' => $object->email,
        'display_name' => $object->display_name
        ));
    }
    return $return;
  }

  public static function updateAccount($id, $email, $password)
  {
    $id = (int)$id;
    $email_ms = self::$mysqli->real_escape_string($email);
    if (!self::$mysqli->query("UPDATE ws_accounts SET email='$email_ms' WHERE id=$id"))
    {
      throw new Exception();
    }
    if (strlen($password) > 0)
    {
      $salt = uniqid('helpcat', true);
      $hash = hash('sha512', $salt.$password);
      $salt_ms = self::$mysqli->real_escape_string($salt);
      $hash_ms = self::$mysqli->real_escape_string($hash);

      if (!self::$mysqli->query("UPDATE ws_accounts SET password_salt='$salt_ms', password_hash='$hash_ms' WHERE id=$id")) {
        throw new Exception();
      }
    }
    return true;
  }

  public static function deleteAccount($id)
  {
    $id = (int)$id;
    if (!self::$mysqli->query("DELETE FROM ws_accounts WHERE id=$id"))
    {
      throw new Exception();
    }
    return true;
  }

  public static function createAccount($name, $privileges, $displayname, $email, $password)
  {
    $name_ms = self::$mysqli->real_escape_string($name);
    $privileges = (int)$privileges;
    $displayname_ms = self::$mysqli->real_escape_string($displayname);
    $email_ms = self::$mysqli->real_escape_string($email);
    $salt = uniqid('helpcat', true);
    $hash = hash('sha512', $salt.$password);
    $salt_ms = self::$mysqli->real_escape_string($salt);
    $hash_ms = self::$mysqli->real_escape_string($hash);
    if (!self::$mysqli->query("
      INSERT INTO ws_accounts (name, privileges, display_name, email, password_salt, password_hash)
      VALUES ('$name_ms', $privileges, '$displayname_ms', '$email_ms', '$salt_ms', '$hash_ms')"))
    {
      throw new Exception();
    }
    return true;
  }
}
?>