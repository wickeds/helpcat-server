<?php
require('../classes/CChat.class.php');
require('../classes/Wait.class.php');
require('../../classes/Database.class.php');

if (get_magic_quotes_gpc()) {
  function i_fucking_hate_magic_quotes_so_much(&$arr) {
      foreach ($arr as $key => &$val) {
        if ($key == "GLOBALS") { continue; }
        if (is_array($val)) {
            i_fucking_hate_magic_quotes_so_much($val);
        } else {
            $val = stripslashes($val);
        }
      }
  }
  i_fucking_hate_magic_quotes_so_much($GLOBALS);
}

try {
  CChat::$mysqli = Wait::$mysqli = Database::init();
  
  switch($_REQUEST['action']) {
    
    case 'chat.fetchMessages':
      $response = CChat::fetchMessages((int)$_REQUEST['id'], $_REQUEST['token'], (int)$_REQUEST['since']);;
    break;
    
    case 'chat.sendMessage':
      $response = CChat::sendMessage((int)$_REQUEST['id'], $_REQUEST['token'], $_REQUEST['text']);
    break;
    
    case 'wait.shouldContinue':
      $response = Wait::shouldContinue((int)$_REQUEST['id'], $_REQUEST['token']);
    break;
    
  }
  
  header('Content-Type: application/json');
  echo json_encode($response);
}
catch (Exception $e) {
  header('Content-Type: application/json');
  die(json_encode(array('success' => false, 'error' => $e->getMessage())));
}
?>