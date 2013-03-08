<!DOCTYPE html>
<html>
  <head>
    <title>helpcat administration</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/login.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
  </head>
  <body>
    <form id="box" method="post" action="auth.php">
      <h1>helpcat Administration</h1>
      <hr>
      <?php
      if (isset($_REQUEST['error'])) {
        if ($_REQUEST['error'] == 'wrong_username_or_password') {
          echo '<div style="color:red;">Wrong username or password.</div>';
        }
      }
      ?><br>
      Username:<br>
      <input type="text" name="username" autofocus><br>
      Password:<br>
      <input type="password" name="password"><br><br>
      <div class="rightbound">
        <input type="submit" value="Login">
      </div>
    </form>
    <script src="./js/jquery-1.8.3.min.js"></script>
  </body>
</html>