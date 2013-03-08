<?php
require('../requireauth.inc.php');
require('../../classes/Database.class.php');
require('../classes/Accounts.class.php');
Accounts::$mysqli = Database::init();

if (strlen(trim($_REQUEST['deleteconfirmation'])) > 0 && trim($_REQUEST['deletekey']) == trim($_REQUEST['deleteconfirmation'])) {
  Accounts::deleteAccount($_REQUEST['id']);
}
else {
  Accounts::updateAccount($_REQUEST['id'], $_REQUEST['email'], $_REQUEST['password']);
}

header('Location: ../accounts.php');