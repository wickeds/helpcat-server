<?php
function htmlentitiesutf8($string) {
  return htmlentities($string, ENT_COMPAT | ENT_QUOTES, 'UTF-8');
}
function randstr($length) {
  $key = '';
  $chars = 'ABCDEFGHIJKLOMNOPQRSTUVXYZ123456789';
  for ($p = 0; $p < $length; $p++) {
      $key .= $chars[mt_rand(0, strlen($chars)-1)];
  }
  return $key;
}
?>