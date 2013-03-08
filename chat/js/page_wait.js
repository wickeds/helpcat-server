$(document).ready(function() {
  var interval = setInterval(function() {
    $.getJSON('./api/call.php', {action:'wait.shouldContinue',id:customer_id,token:customer_token}, function(data) {
      if (data['continue'] && data['continue'] == true) {
        clearInterval(interval);
        location.replace('./session.php?id=' + customer_id + '&token=' + customer_token);
      }
    });
  }, 2500);
});