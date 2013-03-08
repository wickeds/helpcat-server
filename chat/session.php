<!DOCTYPE html>
<html>
  <head>
    <title>Support powered by WickedSup</title>
    <link rel="stylesheet" href="./css/normalize.css">
    <link rel="stylesheet" href="./css/default.css">
  </head>
  <body>
    <div id="container">
      <div id="header">
        <div id="info">
        </div>
        <div id="logo">
          <img src="./img/logo.png" style="max-width:250px;max-height:100px;" alt="Logo">
        </div>
      </div>
      <div id="messages">
      </div>
      <form id="input_form" onsubmit="return false;">
        <textarea id="input" placeholder="Please enter a message and hit enter ..." autofocus></textarea>
      </form>
      <div id="footer">
        powered by <a href="https://wickedsup.net">WickedSup</a>
      </div>
    </div>
    <script>
      var customer_id = "<?php echo intval($_REQUEST['id']); ?>";
      var customer_token = "<?php echo addcslashes($_REQUEST['token'], "\\\'\"&\n\r<>"); ?>";
    </script>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <script src="./js/page_session.js"></script>
  </body>
</html>