<?php
require('../classes/Database.class.php');
require('./classes/Authentification.class.php');
Authentification::$mysqli = Database::init();
$id = 0;
$privileges = 0;
$licensetype = 0;
if (Authentification::Check($_REQUEST['username'], $_REQUEST['password'], $id, $privileges, $licensetype)) {
  Authentification::Authentificate($_REQUEST['username'], $id, $privileges, $licensetype);
  header('Location: ./index.php');
}
else {
  header('Location: ./login.php?error=wrong_username_or_password');
}
?>