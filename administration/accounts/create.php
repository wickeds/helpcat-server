<?php
require('../requireauth.inc.php');
require('../../classes/Database.class.php');
require('../classes/Accounts.class.php');
Accounts::$mysqli = Database::init();

Accounts::createAccount($_REQUEST['name'], $_REQUEST['privileges'], $_REQUEST['displayname'], $_REQUEST['email'], $_REQUEST['password']);

header('Location: ../accounts.php');