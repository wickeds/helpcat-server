<?php
class Settings
{
  public static $mysqli;
  
  public static function get() {
    $id = intval($_SESSION['ws_customer']);
    $result = self::$mysqli->query("SELECT email, display_name FROM ws_accounts WHERE id=$id");
    $row = $result->fetch_object();

    return array('success' => true, 'settings' => array('email' => $row->email, 'display_name' => $row->display_name));
  }
  
  public static function set($values) {
    $id = intval($_SESSION['ws_customer']);

    $email_esc = self::$mysqli->real_escape_string($values['email']);
    $display_name_esc = self::$mysqli->real_escape_string($values['display_name']);
    $result = self::$mysqli->query("UPDATE ws_accounts SET email='$email_esc', display_name='$display_name_esc' WHERE id=$id");
    $part1_success = $result != false;

    $part2_success = true; // predict as true because not executing is a success
    if (strlen($values['password']) > 0) {
      require('Essentials.class.php');
      $salt = randstr(8);
      $salt_esc = self::$mysqli->real_escape_string($salt);
      $password_esc = self::$mysqli->real_escape_string(hash('sha512', $salt.$values['password']));
      $result = self::$mysqli->query("UPDATE ws_accounts SET password_salt='$salt_esc', password_hash='$password_esc' WHERE id=$id");
      $part2_success = $result != false;
    }

    if ($part1_success && $part2_success)
      return array('success' => true);
    else
      throw new Exception('Internal MySQL Error.');
  }
}
?>