<?php
require_once(__DIR__ . '/../inc/config.inc.php');

class Database {  
  public static function init() {
    $mysqli = new mysqli(CONFIG_MYSQL_HOST, CONFIG_MYSQL_USERNAME, CONFIG_MYSQL_PASSWORD, CONFIG_MYSQL_DATABASE);
    if (mysqli_connect_errno()) {
      throw new Exception('Internal MySQL error.');
    }
    $mysqli->set_charset('utf8');
    
    return $mysqli;
  }
}
?>