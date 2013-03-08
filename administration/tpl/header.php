<!DOCTYPE html>
<html>
  <head>
    <title>helpcat administration</title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8">
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/index.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
    <script src="./js/jquery-1.8.3.min.js"></script>
    <script src="./js/index.js"></script>
  </head>
  <body>
    <div id="user">
      <?php
      echo 'Logged in as <b>' . htmlentities($_SESSION['name']) . '</b><br>';
      ?>
      <a href="./logout.php">Logout</a>
    </div>
    <ul id="nav">
      <li data-href="index.php"<?php if(basename($_SERVER['SCRIPT_NAME'])=='index.php') echo ' class="nav_active"'; ?>>Overview</li>
      <li data-href="accounts.php"<?php if(basename($_SERVER['SCRIPT_NAME'])=='accounts.php') echo ' class="nav_active"'; ?>>Accounts</li>
    </ul>
    <div id="content">