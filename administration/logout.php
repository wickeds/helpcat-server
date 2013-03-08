<?php
require_once(__DIR__ . '/../inc/config.inc.php');
session_name(SESSION_NAME);
session_start();
session_destroy();
header("Location: ./login.php");
?>