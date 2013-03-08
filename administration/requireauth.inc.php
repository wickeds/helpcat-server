<?php
require_once(__DIR__ . '/../inc/config.inc.php');
session_name(SESSION_NAME);
if(!isset($_SESSION)) {
  session_start();
}
if (!isset($_SESSION["authed"]) || $_SESSION["authed"] == false) {
  header('Location: ./login.php');
}
?>