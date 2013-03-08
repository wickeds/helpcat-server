<?php
class Chat
{
  public static $mysqli;
  
  private static function isCustomerTimedOut($customer_id) {
    $customer_id = (int)$customer_id;
    
    $result = self::$mysqli->query("SELECT last_heartbeat >= DATE_SUB(NOW(), INTERVAL 120 SECOND) AS alive FROM ws_customers WHERE id=$customer_id");
    
    if ($result) {
      if ($obj = $result->fetch_object()) {
        return !(bool)$obj->alive;
      }
      else {
        return true;
      }
    }
    else {
      throw new Exception("Internal MySQL error while checking customer timestamps.");
    }
  }
  
  private static function isSessionTimedOut($agent_id, $customer_id) {
    $agent_id = (int)$agent_id;
    $customer_id = (int)$customer_id;
    
    $result = self::$mysqli->query("SELECT last_heartbeat >= DATE_SUB(NOW(), INTERVAL 120 SECOND) AS alive FROM ws_sessions WHERE agent=$agent_id AND customer=$customer_id");
    
    if ($result) {
      if ($obj = $result->fetch_object()) {
        return !(bool)$obj->alive;
      }
      else {
        return true;
      }
    }
    else {
      throw new Exception("Internal MySQL error while checking session timestamps.");
    }
  }
  
  private static function isChatTimedOut($chat_id) {
    $chat_id = (int)$chat_id;
    
    $result = self::$mysqli->query(
      "SELECT ws_customers.last_heartbeat >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) AND ws_sessions.last_heartbeat >= DATE_SUB(NOW(), INTERVAL 2 MINUTE) AS alive
      FROM ws_customers, ws_sessions, ws_chats
      WHERE ws_chats.id=$chat_id AND ws_customers.chat=ws_chats.id AND ws_sessions.chat=ws_chats.id");
    
    if ($result) {
      if ($obj = $result->fetch_object()) {
        return !(bool)$obj->alive;
      }
      else {
        return true;
      }
    }
    else {
      throw new Exception("Internal MySQL error while checking session and customer timestamps.");
    }
  }
  
  private static function isChatActive($chat_id) {
    $agent_id = (int)$agent_id;
    $customer_id = (int)$customer_id;
    
    $result = self::$mysqli->query("SELECT active FROM ws_chats WHERE id=$chat_id");
    
    if ($result) {
      if ($obj = $result->fetch_object()) {
        return (bool)$obj->alive;
      }
      else {
        return false;
      }
    }
    else {
      throw new Exception("Internal MySQL error while checking chat life.");
    }
  }
  
  public static function join($chat_id) {
    $chat_id = (int)$chat_id;
    $agent_id = $_SESSION['ws_customer'];
    
    if (!isset($_SESSION['ws_chats'])) $_SESSION['ws_chats'] = array();
    
    $result = self::$mysqli->query(
      "SELECT ws_customers.name, ws_customers.addr, ws_customers.user_agent
      FROM ws_customers, ws_chats
      WHERE ws_customers.chat=ws_chats.id AND ws_chats.id=$chat_id AND ws_chats.active=true");
    
    if ((int)$result->num_rows) {
      $row = $result->fetch_object();
      self::$mysqli->query("INSERT INTO ws_sessions (agent, chat, last_heartbeat) VALUES ($agent_id, $chat_id, NOW())");
      
      return array('success' => true, 'name' => $row->name, 'addr' => $row->addr, 'user_agent' => $row->user_agent);
    }
    else {
      return array('success' => false);
    }
  }
  
  public static function leave($chat_id) {
    $chat_id = (int)$chat_id;
    $agent_id = $_SESSION['ws_customer'];

    $result = self::$mysqli->query("DELETE FROM ws_sessions WHERE agent=$agent_id AND chat=$chat_id");

    if ($result) {
      return array('success' => true);
    }
    else {
      return array('success' => false);
    }
  }
  
  public static function fetchMessages($chat_id) {
    // probleme wenn mehrere agents in einem chat drin sind, wenn der chat outtimed wird nur bei einem agent der timeout angezeigt, der andere kriegt warsch. the conversation has ended
    $chat_id = (int)$chat_id;
    $agent_id = $_SESSION['ws_customer'];
    
    if (!isset($_SESSION['ws_moffsets'])) $_SESSION['ws_moffsets'] = array();
    if (!isset($_SESSION['ws_moffsets'][$chat_id])) $_SESSION['ws_moffsets'][$chat_id] = -1;
    $since = $_SESSION['ws_moffsets'][$chat_id];
    
    $result = self::$mysqli->query(
      "SELECT EXISTS(
        SELECT 1
        FROM ws_chats, ws_customers, ws_accounts, ws_sessions
        WHERE ws_chats.active=true AND ws_chats.id=$chat_id AND ws_accounts.id=$agent_id AND ws_sessions.agent=ws_accounts.id AND ws_sessions.chat=ws_chats.id AND ws_customers.chat=ws_chats.id
        )
      AS \"exists\"");
    $row = $result->fetch_object();
    
    if ((int)$row->exists) {
      if (!self::isChatTimedOut($chat_id)) {
        $result = self::$mysqli->query("SELECT ws_messages.id, UNIX_TIMESTAMP(ws_messages.timestamp) AS timestamp, ws_messages.name AS name, ws_messages.text AS text, ws_messages.sender_type AS rank FROM ws_messages, ws_chats WHERE ws_messages.chat=ws_chats.id AND ws_chats.id=$chat_id AND ws_messages.id>$since ORDER BY id ASC");
        
        $messages = array();
        if ((int)$result->num_rows) {
          while ($row = $result->fetch_object()) {
            array_push($messages, array('name'=>$row->name,'text'=>$row->text,'timestamp'=>(int)$row->timestamp,'rank'=>(int)$row->rank));
            
            $timestamp = (int)$row->id;
          }
          
          $_SESSION['ws_moffsets'][$chat_id] = $timestamp;
          
          // save heartbeat
          self::$mysqli->query("UPDATE ws_sessions SET last_heartbeat=NOW() WHERE agent=$agent_id AND chat=$chat_id");
        }
        return array('success' => true, 'open' => true, 'active' => true, 'messages' => $messages);
      }
      else {
        self::$mysqli->query("UPDATE ws_chats SET active=false WHERE id=$chat_id");
        return array('success' => true, 'open' => true, 'active' => false);
      }
    }
    else {
      return array('success' => true, 'open' => false);
    }
  }
  
  public static function sendMessage($chat_id, $text) {
    $chat_id = intval($chat_id);
    $text_ms = self::$mysqli->real_escape_string($text);
    $name_ms = self::$mysqli->real_escape_string($_SESSION['ws_name']);
      
    $result = self::$mysqli->query("INSERT INTO ws_messages (chat, name, text, sender_type) VALUES ($chat_id, '$name_ms', '$text_ms', 1)");
    
    if ($result) {
      return array('success' => true);
    }
    else {
      return array('success' => false);
    }
  }
}
?>