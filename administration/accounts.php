<?php require('requireauth.inc.php'); include('tpl/header.php'); ?>
<?php
require('../classes/Database.class.php');
require('../classes/Essentials.class.php');
require('classes/Accounts.class.php');
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
          Username
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
        echo '<tr data-id="' . Essentials::htmlentities($account['id']) . '" data-privileges="' . Essentials::htmlentities($account['privileges']) . '" data-email="' . Essentials::htmlentities($account['email']) . '">';
        echo '<td data-content="id">' . Essentials::htmlentities($account['id']) . '</td>';
        echo '<td>' . Essentials::htmlentities($account['name']) . '</td>';
        echo '<td data-content="privileges">' . Essentials::htmlentities($account['privileges']) . '</td>';
        echo '<td>' . Essentials::htmlentities($account['last_heartbeat']) . '</td>';
        echo '<td data-content="email">' . Essentials::htmlentities($account['email']) . '</td>';
        echo '<td>' . Essentials::htmlentities($account['display_name']) . '</td>';
        echo '<td><img src="images/appbar.edit.png" class="tab_accounts_editbutton"></a></td>';
        echo '</tr>';
      }
      ?>
    </table>
    <form id="tab_accounts_createaccount" action="accounts/create.php" method="post">
      <strong>Create new account</strong><hr>
      Username:<br>
      <input type="text" name="name"><br>
      Privileges:<br>
      <select name="privileges" size="1">
        <option value="0">Supporter</option>
        <option value="1">Administrator</option>
      </select><br>
      Display name:<br>
      <input type="text" name="displayname"><br>
      E-Mail:<br>
      <input type="email" name="email"><br>
      Password:<br>
      <input type="password" name="password"><br><br>
      <input type="submit" value="Create">
    </form>
    <form id="tab_accounts_edittemplate" action="accounts/edit.php" method="post">
      Privileges:<br>
      <select name="privileges" size="1">
        <option value="0">Supporter</option>
        <option value="1">Administrator</option>
      </select><br>
      E-Mail:<br>
      <input type="email" name="email"><br>
      Password: (leave blank if no change intended)<br>
      <input type="password" name="password"><br>
      <a href="javascript:;" class="deleteaccount">Delete account<br></a>
      <div class="deleteaccountbox">
        <input type="hidden" name="deletekey" value="<?php echo $deletekey = uniqid(); ?>">
        Put <?php echo $deletekey ?> to delete account:<br>
        <input type="text" name="deleteconfirmation">
      </div>
      <br>
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


      tr.append(td);

      // init edit box

      var box = $('#tab_accounts_edittemplate').clone();
      box.find('input[name="email"]').val(sender.attr('data-email'));
      var privileges = sender.attr('data-privileges');
      box.css({
        'display': 'table-row'
      });
      box.find('select[name="privileges"] > option[value="' + privileges + '"]').attr('selected', '')
      box.find('.deleteaccount').click(function() {
        $(event.target).hide();
        box.find('.deleteaccountbox').show();
      });
      var inputid = $('<input />');
      inputid.attr('type', 'hidden');
      inputid.attr('name', 'id');
      inputid.val(sender.attr('data-id'));
      box.append(inputid);


      box.submit(function() {
        $.ajax({
          async: true,
          url: 'accounts/edit.php',
          dataType: 'json',
          type: 'post',
          data: {
            email: box.find('input[name="email"]'),
            password: box.find('input[name="password"]')
          },
          success: function() {
            alert('Changes were successfully applied.');
            fnRemove();
          }
        });
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
<?php include('tpl/footer.php'); ?>