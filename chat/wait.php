<?php
if (isset($_POST['name']) && $_POST['name'] != '') {
  require('../classes/Database.class.php');
  require('../classes/Essentials.class.php');
  
  $mysqli = Database::init();
  
  $customer_token = Essentials::getRandomString(64);
  
  $name = substr(trim(preg_replace('/\s+/', ' ', $_POST['name'])), 0, 32);
  $name_ms = $mysqli->real_escape_string($name);
  $addr_ms = $mysqli->real_escape_string($_SERVER['REMOTE_ADDR']);
  $uagnt_ms = $mysqli->real_escape_string($_SERVER['HTTP_USER_AGENT']);
  
  // spawn chat
  $mysqli->query("INSERT INTO ws_chats (active) VALUES(true)");
  $chat_id = $mysqli->insert_id;
  // spawn customer
  $mysqli->query("INSERT INTO ws_customers (name, addr, user_agent, chat, token, last_heartbeat) VALUES('$name_ms', '$addr_ms', '$uagnt_ms', $chat_id, '$customer_token', NOW())");
  // NUR FÜR INT, KEIN BIGINT !!!
  $customer_id = $mysqli->insert_id;
  
  // spawn notificaion
  $text = $mysqli->real_escape_string('Name: ' . $name . "\r\n" . 'IP: ' . $_SERVER['REMOTE_ADDR']);
  $mysqli->query("INSERT INTO ws_notifications (global, type, target, text) VALUES (1, 'new_customer', $chat_id, '$text')");
}
else {
  header('Location: ./');
}
?>
<!DOCTYPE html>
<html>
  <head>
    <title>Support powered by WickedSup</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/default.css">
    <script>
      var customer_id = "<?php echo $customer_id; ?>";
      var customer_token = "<?php echo $customer_token; ?>";
    </script>
  </head>
  <body>
    <img src="./img/logo.png" style="max-width:250px;max-height:100px;" alt="Logo">
    <br>
    <br>
    <img src="./img/loading.gif" style="padding:2px;border-radius:12px;background:white;box-shadow:0px 0px 5px white;"><br>
    Please wait while we're assigning you to a support agent ...
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="./js/page_wait.js"></script>
  </body>
</html>