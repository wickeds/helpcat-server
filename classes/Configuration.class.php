<?php
class Configuration {  
  public static function get() {
    return json_decode(file_get_contents(__DIR__ . '/../config.json'), true);
  }
}
?>