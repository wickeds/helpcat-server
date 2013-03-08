<?php
class Essentials
{
  public static function randstr($length) {
    return self::getRandomUpperString($length);
  }

  public static function getRandomString($length) {
    $key = '';
    $chars = 'ABCDEFGHIJKLOMNOPQRSTUVXYZabcdefghijklmnopqrstuvwxyz123456789';
    for ($p = 0; $p < $length; $p++) {
        $key .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    return $key;
  }

  public static function getRandomUpperString($length) {
    $key = '';
    $chars = 'ABCDEFGHIJKLOMNOPQRSTUVXYZ123456789';
    for ($p = 0; $p < $length; $p++) {
        $key .= $chars[mt_rand(0, strlen($chars)-1)];
    }
    return $key;
  }
  
  public static function htmlentities($str) {
    return htmlentities($str, ENT_COMPAT | ENT_QUOTES, 'UTF-8');
  }
}
?>