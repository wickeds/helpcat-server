<?php
class Notifications
{
  public static $mysqli;
  
  public static function pull() {
    if (!isset($_SESSION['ws_noffset'])) {
      $result = self::$mysqli->query("SELECT MAX(id) AS now FROM ws_notifications");
      $row = $result->fetch_object();
      
      $_SESSION['ws_noffset'] = intval($row['now']);
    }
    
    $since = $_SESSION['ws_noffset'];
    $customer = $_SESSION['ws_customer'];
    
    $result = self::$mysqli->query("SELECT id, type, text, target FROM ws_notifications WHERE (global=1 OR recipient=$customer) AND id > $since ORDER BY id ASC");
    $notifications = array();

    $last = null;
    while ($result && $row = $result->fetch_object()) {
      array_push($notifications, array('type' => $row->type, 'text' => $row->text, 'target' => (int)$row->target));
      
      $last = (int)$row->id;
    }

    if ($last != null) $_SESSION['ws_noffset'] = (int)$last;
    
    self::$mysqli->query("UPDATE ws_accounts SET last_heartbeat=NOW() WHERE id=$customer");
    
    return array('success' => true, 'notifications' => $notifications);
  }
}
?>