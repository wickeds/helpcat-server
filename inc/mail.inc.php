<?php
function build_headers($email) {
  $headers = '';
  $headers .= "From: $sender_address\r\n";
  $headers .= "Errors-To: <$support_address>\r\n";
  $headers .= "Return-Path: <$email>\r\n";
  $headers .= "Reply-To: <$email>";
}
?>