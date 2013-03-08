<?php
class CChat {
  public static $mysqli;

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
  
  public static function fetchMessages($customer_id, $token, $since) {
    $customer_id = (int)$customer_id;
    $token_ms = self::$mysqli->real_escape_string($token);
    $since = (int)$since;

    $result = self::$mysqli->query("SELECT ws_chats.id
    FROM ws_chats, ws_customers, ws_accounts, ws_sessions
    WHERE ws_chats.id=ws_customers.chat AND ws_customers.id=$customer_id AND ws_customers.token='$token_ms' AND ws_chats.active=true AND ws_sessions.agent=ws_accounts.id AND ws_sessions.chat=ws_chats.id");

    if ($obj = $result->fetch_object()) {
      $chat_id = (int)$obj->id;
      if (!self::isChatTimedOut((int)$obj->id)) {
        $result = self::$mysqli->query(
        "SELECT ws_messages.id, UNIX_TIMESTAMP(ws_messages.timestamp) AS `timestamp`, ws_messages.name, ws_messages.text, ws_messages.sender_type AS rank, ws_messages.sender
        FROM ws_messages, ws_chats
        WHERE ws_messages.chat=ws_chats.id AND ws_chats.id=$chat_id AND ws_messages.id>$since ORDER BY id ASC");

        $messages = array();
        $timestamp = 0;
        if ((int)$result->num_rows) {
          while ($row = $result->fetch_object()) {
            array_push($messages, array('name'=>$row->name,'text'=>$row->text,'timestamp'=>(int)$row->timestamp,'rank'=>(int)$row->rank,'yourself'=>((int)$row->rank==0)&&((int)$row->sender)==$customer_id));
            
            $timestamp = (int)$row->id;
          }
          
          // save heartbeat
          self::$mysqli->query("UPDATE ws_customers SET last_heartbeat=NOW() WHERE id=$customer_id");
        }

        return array('success' => true, 'open' => true, 'active' => true, 'timestamp' => $timestamp, 'messages' => $messages);
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
  
  public static function sendMessage($id, $token, $text) {
    $id = (int)$id;
    $token_ms = self::$mysqli->real_escape_string($token);
    $text_ms = self::$mysqli->real_escape_string($text);
    $result = self::$mysqli->query("SELECT ws_customers.name, ws_chats.id FROM ws_customers, ws_chats WHERE ws_customers.id=$id AND ws_customers.token='$token_ms' AND ws_customers.chat=ws_chats.id");
    
    if ($row = $result->fetch_object()) {
      $name_ms = self::$mysqli->real_escape_string($row->name);
      $chat_id = (int)$row->id;
      
      $result = self::$mysqli->query("INSERT INTO ws_messages (chat, name, text, sender_type, sender) VALUES ($chat_id, '$name_ms', '$text_ms', 0, $id)");
      
      if ($result) {
        return array('success' => true);
      }
      else {
        return array('success' => false);
      }
    }
    else {
      return array('success' => false);
    }
  }
}
?>