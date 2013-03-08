<?php
require('../classes/Database.class.php');

$mysqli = Database::init();

$name = $mysqli->real_escape_string($_REQUEST['name']);
$password = $_REQUEST['password'];
$result = $mysqli->query("SELECT id, password_hash, password_salt, privileges FROM ws_accounts WHERE name='$name'");

if ($result->num_rows > 0) {
  $row = $result->fetch_object();
  
  if(hash('sha512', $row->password_salt . $password) == $row->password_hash) {
    session_start();
    
    $_SESSION['ws_authed'] = true;
    $_SESSION['ws_name'] = $_REQUEST['name'];
    $_SESSION['ws_customer'] = (int)$row->id;
    $_SESSION['ws_privileges'] = (int)$row->privileges;
    
    // set notification offset
    $result = $mysqli->query("SELECT COALESCE(MAX(id), 0) AS now FROM ws_notifications");
    $row = $result->fetch_object();
    $_SESSION['ws_noffset'] = intval($row->now);
    
    $agent_id = $_SESSION['ws_customer'];
    $mysqli->query("UPDATE ws_accounts SET last_heartbeat=NOW() WHERE id=$agent_id");
    
    header('Content-Type: application/json');
    echo json_encode(array('success' => true, 'session_id' => session_id()));
  }
  else {
    header('Content-Type: application/json');
    echo json_encode(array('success' => false, 'error' => array('type' => 'wrong_password', 'message' => 'Wrong password.')));
  }
}
else {
  header('Content-Type: application/json');
  echo json_encode(array('success' => false, 'error' => array('type' => 'wrong_username', 'message' => 'Wrong username.')));
}
?>