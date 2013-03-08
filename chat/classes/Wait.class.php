<?php
class Wait {
  public static $mysqli;
  
  public static function shouldContinue($id, $token) {
    $id = (int)$id;;
    $token_ms = self::$mysqli->real_escape_string($_REQUEST['token']);
    $result = self::$mysqli->query(
      "SELECT EXISTS(
        SELECT 1
        FROM ws_chats, ws_customers, ws_accounts, ws_sessions
        WHERE ws_chats.id=ws_sessions.chat AND ws_chats.id=ws_customers.chat AND ws_customers.id=$id AND ws_customers.token='$token_ms'
        )
      AS agentfound");
    $row = $result->fetch_object();
    
    if ($row && (int)$row->agentfound) {
      self::$mysqli->query("UPDATE ws_customers SET last_heartbeat=NOW() WHERE id=$id");
      
      return array('success' => true, 'continue' => true);
    }
    else {
      return array('success' => false, 'continue' => false, 'error' => false);
    }
  }
}
?>