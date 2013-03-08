<?php
if(!isset($_SESSION)) { 
  session_start();
}
if (!isset($_SESSION["ws_authed"]) || $_SESSION["ws_authed"] == false) {
  header('Content-Type: application/json');
  echo json_encode(array('success' => 'false', 'error' => array('type' => 'not_authed', 'message' => 'You are not logged in.')));
  die();
}
?>