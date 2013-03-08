$(document).ready(function() {
  var since = -1;
  var die = false;
  function fetchMessages(callback) {
    $.getJSON('./api/call.php', {action:'chat.fetchMessages',id:customer_id,token:customer_token,since:since}, function(data) {
      if (data.success == true && data.open == true && data.active == false) {
        die = true;
        var message = $('<div/>');
        message.addClass('msgother');
        message.css({
          'color': 'red'
        });
        
        message.text('The conversation timed out.');
        $('#messages').append(message);
        
        $('#input').attr('disabled', 'disabled');
      }
      else if (data.success == true && data.open == false) {
        die = true;
        var message = $('<div/>');
        message.addClass('msgother');
        message.css({
          'color': 'red'
        });
        
        message.text('The conversation was ended.');
        $('#messages').append(message);
        
        $('#input').attr('disabled', 'disabled');
      }
      else if (data.success == true) {
        if (data.timestamp)
          since = data.timestamp;
        
        for (var i = 0; i < data.messages.length; i++) {
          var time = $('<span/>');
          var time_obj = new Date(data.messages[i].timestamp*1000);
          var hours = time_obj.getHours();
          if (hours < 10) {
            hours = '0' + hours;
          }
          var minutes = time_obj.getMinutes();
          if (minutes < 10) {
            minutes = '0' + minutes;
          }
          time.text(hours + ':' + minutes);
          
          var name = $('<span/>');
          if (data.messages[i].yourself) {
            name.text('you');
          }
          else {
            name.text(data.messages[i].name);
          }
          
          var text = $('<span/>');
          text.text(data.messages[i].text);

          var head = $('<div/>');
          head.addClass('head');
          /*head.css({
            'color': data.messages[i].rank==0?'blue':(data.messages[i].rank==1?'red':'gray')
          });*/
          
          head.append('(');
          head.append(time);
          head.append(') ');
          head.append(name);
          head.append(':');

          var container = $('<div />');
          container.addClass(data.messages[i].yourself?'msgyou':'msgother');
          container.append(head);
          container.append(text);

          $('#messages').append(container);
          
          $('#messages').scrollTop($('#messages').prop('scrollHeight') - $('#messages').height());
        }
      }
      // {"success":true,"open":false} => keine reaktion (?)
      if (callback) {
        callback();
      }
    });
  }

  function fetchMessagesLoop() {
    if (!die) {
      fetchMessages();
      setTimeout(fetchMessagesLoop, 1000);
    }
  }

  var welcome = $('<div/>');
  welcome.addClass('msgother');
  welcome.css({
    'color': 'red'
  });
  welcome.text('You are connected to an agent.');
  $('#messages').append(welcome);
  $('#messages').append('<br>');
  fetchMessagesLoop();
  
  $('#input').keypress(function(e) {
      if(e.which == 13 && !e.ctrlKey) {
        var text = $.trim($('#input').val());
        $('#input_form')[0].reset();
        
        if (text.length == 0) {
          return false;
        }

        $.getJSON('./api/call.php', {action:'chat.sendMessage',id:customer_id,token:customer_token,text:text}, function(data) {
          
        });
        
        return false;
      }
  });
  $(window).keypress(function() {
    if ($('input:focus, textarea:focus').length == 0) {
      $('#input').focus();
    }
  });


  window.onbeforeunload = function() {
    return 'Wait! Your chat session! :(';
  };

  window.onunload = function() {

  };
})