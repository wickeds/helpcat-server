<?php
require_once('../requireauth.inc.php');
require('../../classes/Database.class.php');
require('../classes/Accounts.class.php');
Accounts::$mysqli = Database::init();
$accounts = Accounts::listAccounts();
?>
<style type="text/css">
  #tab_accounts_wrapper div:first-child {
    float: left;
    width: 100%;
  }
  #tab_accounts_wrapper div:nth-child(2) {
    float: right;
  }
  .tab_accounts_editbutton {
    width: 24px;
    height: 24px;
    cursor: pointer;
  }
  #tab_accounts_edittemplate {
    display: none;
  }
</style>
<div id="tab_accounts_wrapper">
  <div>
    <table>
      <tr>
        <th>
          ID
        </th>
        <th>
          Name
        </th>
        <th>
          Privileges
        </th>
        <th>
          Last Heartbeat
        </th>
        <th>
          E-Mail
        </th>
        <th>
          Display Name
        </th>
        <th>
        </th>
      </tr>
      <?php
      foreach ($accounts as $account) {
        echo '<tr>';
        echo '<td data-content="id">' . $account['id'] . '</td>';
        echo '<td>' . $account['name'] . '</td>';
        echo '<td data-content="privileges">' . $account['privileges'] . '</td>';
        echo '<td>' . $account['last_heartbeat'] . '</td>';
        echo '<td data-content="email">' . $account['email'] . '</td>';
        echo '<td>' . $account['display_name'] . '</td>';
        echo '<td><img src="images/appbar.edit.png" class="tab_accounts_editbutton"></a></td>';
        echo '</tr>';
      }
      ?>
    </table>
    <form id="tab_accounts_edittemplate" action="accounts/edit.php" method="post" onsubmit="return:false;">
      Privileges:<br>
      <select name="privileges" size="1">
        <option value="0">Supporter</option>
        <option value="1">Administrator</option>
      </select><br>
      E-Mail:<br>
      <input type="email" name="email"><br>
      Password: (leave blank if no change intended)<br>
      <input type="password" name="password"><br><br>
      <input type="submit" value="Save">
    </form>
  </div>
  <div>
  </div>
</div>
<script type="text/javascript">
  $('.tab_accounts_editbutton').click(function() {
    var sender = $(event.target).parent().parent();

    var fnRemove = function() {
      var box = sender.next();
      box.hide('fast', function() {
        box.remove();
        sender.removeAttr('data-editing');
      });
    };

    if (sender.attr('data-editing') == 'true') {
      fnRemove();
    }
    else {
      var tr = $('<tr />');
      var td = $('<td />');
      td.attr('colspan', '100');
      console.log(sender[0]);


      tr.append(td);

      // init edit box

      var box = $('#tab_accounts_edittemplate').clone();
      box.find('input[name="email"]').val(sender.find('td[data-content="email"]').text());
      var privileges = sender.find('td[data-content="privileges"]').text();
      box.css({
        'display': 'table-row'
      });
      box.find('select[name="privileges"] > option[value="' + privileges + '"]').attr('selected', '')

      box.submit(function() {
        return false;
      });

      // end

      td.append(box);
      tr.hide();
      tr.insertAfter(sender);
      tr.show('fast');
      sender.attr('data-editing', 'true');
    }
  });
</script>