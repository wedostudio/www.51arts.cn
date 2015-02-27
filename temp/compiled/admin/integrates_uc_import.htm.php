<!-- $Id: integrates_uc_import.htm 14960 2008-10-20 05:23:26Z testyang $ -->
<?php echo $this->fetch('pageheader.htm'); ?>
<!-- start integrate setup form -->
<div style="border: 1px solid #CC0000;background-color:#FFFFCE;color:#CE0000;padding:4px;" ><?php echo $this->_var['lang']['uc_import_notice']; ?></div>
<div class="main-div" style="padding:5px;">
  <form action="integrate.php" method="post" name="theForm">
  <h3><?php echo $this->_var['user_startid_intro']; ?></h3>
  <h3><?php echo $this->_var['lang']['uc_members_merge']; ?></h3>
  <ul>
    <li style="list-style-type:none;"><input type="radio" name="merge" value="1" checked="checked" /><?php echo $this->_var['lang']['uc_members_merge_way1']; ?></li>
    <li style="list-style-type:none;"><input type="radio" name="merge" value="2" /><?php echo $this->_var['lang']['uc_members_merge_way2']; ?></li>
  </ul>
  <p id="ECS_NOTICE"></p>
  <input type="button" value="<?php echo $this->_var['lang']['start_import']; ?>" class="button" onclick="import_start(this)">
  </form>
</div>
<!-- end integrate setup form -->
<?php echo $this->smarty_insert_scripts(array('files'=>'../js/utils.js,validator.js')); ?>

<script language="JavaScript">
<!--
onload = function()
{
    // 开始检查订单
    startCheckOrder();
}
function import_start(obj)
{
  var frm = document.forms['theForm'];
  var merge = -1;
  for (var i=0; i<frm.elements['merge'].length; i++)
  {
    if (frm.elements['merge'][i].checked)
    {
      merge = frm.elements['merge'][i].value;
    }
  }
  if (merge < 0)
  {
    alert(no_method);
    return;
  }

  var notice = document.getElementById('ECS_NOTICE');
  notice.innerHTML = user_importing;
  obj.disabled = true;
  Ajax.call('integrate.php?act=import_user', 'start=0&merge=' + merge, checkResponse, 'GET', 'JSON');
}

function checkResponse(result)
{
  if (result.error > 0)
  {
    alert(result.message);
  }
  if (result.error == 0)
  {
    var notice = document.getElementById('ECS_NOTICE');
    notice.innerHTML = result.message;
    window.setTimeout(function ()
    {
        location.href='integrate.php?act=complete';
    }, 1000);
  }
}
//-->
</script>

<?php echo $this->fetch('pagefooter.htm'); ?>