<?php
require('../inc/requireauth.inc.php');
require('../classes/Notifications.class.php');
require('../classes/Chat.class.php');
require('../classes/Settings.class.php');
require('../classes/Database.class.php');

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
  Notifications::$mysqli = Chat::$mysqli = Settings::$mysqli = Database::init();
  
  switch($_REQUEST['action']) {
    
    case 'notifications.fetch':
      $response = Notifications::pull();
    break;
    
    case 'chat.join':
      $response = Chat::join((int)$_REQUEST['id']);
    break;
    
    case 'chat.leave':
      $response = Chat::leave((int)$_REQUEST['id']);
    break;
    
    case 'chat.fetchMessages':
      $response = Chat::fetchMessages((int)$_REQUEST['id']);;
    break;
    
    case 'chat.sendMessage':
      $response = Chat::sendMessage((int)$_REQUEST['id'], $_REQUEST['text']);
    break;
    
    case 'settings.get':
      $response = Settings::get();
    break;
    
    case 'settings.set':
      $response = Settings::set($_REQUEST);
    break;
    
    case 'session.end':
      session_start();
      session_destroy();
      $mysqli->query("UPDATE ws_accounts SET last_heartbeat=null WHERE id=$agent_id");
      $response = array('success' => true);
    break;
    
    default:
      throw new Exception('Invalid call.php call.');
    break;
  }
  
  header('Content-Type: application/json');
  die(json_encode($response));
}
catch (Exception $e) {
  header('Content-Type: application/json');
  die(json_encode(array('success' => false, 'error' => array('message' => $e->getMessage()))));
}
?>