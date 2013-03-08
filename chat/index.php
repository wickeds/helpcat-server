<!DOCTYPE html>
<html>
  <head>
    <title>Support powered by WickedSup</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/default.css">
  </head>
  <body>
    <img src="./img/logo.png" style="max-width:250px;max-height:100px;" alt="Logo">
    <br>
    <br>
    <form method="post" action="wait.php" accept-charset="utf-8">
      Name:
      <br>
      <input type="text" name="name" autofocus>
      <br>
      <br>
      <?php
      require('../classes/Database.class.php');
      require('../classes/Essentials.class.php');
      
      $mysqli = Database::init();
      $result = $mysqli->query("SELECT EXISTS(SELECT 1 FROM ws_accounts WHERE last_heartbeat>=DATE_SUB(NOW(), INTERVAL 2 MINUTE)) AS available");
      $row = $result->fetch_object();
      if ($row && (int)$row->available) {
        echo '<input type="submit" value="Connect">';
      }
      else {
        echo '<input type="submit" value="Connect" disabled="disabled"><br><br>Sorry, currently no agent is available.';
      }
      ?>
    </form>
  </body>
</html>