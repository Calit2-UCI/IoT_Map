<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 03:56:11 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_notifications_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a id="refresh" class="pool_link"><?php echo l('refresh_pool', 'notifications', '', 'text', array()); ?></a></div></li>
	<?php if ($this->_vars['allow_pool_send']): ?><li><div class="l"><a id="send" class="pool_link"><?php echo l('send_pools', 'notifications', '', 'text', array()); ?></a></div></li><?php endif;  if ($this->_vars['allow_pool_delete']): ?><li><div class="l"><a id="delete" class="pool_link"><?php echo l('delete_pools', 'notifications', '', 'text', array()); ?></a></div></li><?php endif; ?>
</ul>
&nbsp;
</div>
<style>
    <?php echo '
		.pool_link {
			cursor: pointer;
		}
    '; ?>

</style>
<form id="pool_form" name="pool_form">
	<div id="pool_data">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			<tr>
			<?php if ($this->_vars['allow_pool_send'] || $this->_vars['allow_pool_delete']): ?><th class="first w20 center"><input type="checkbox" id="grouping_all" onclick="javascript: checkAll(this.checked);"></th><?php endif; ?>
			<th class="w150 <?php if (! $this->_vars['allow_pool_send'] && ! $this->_vars['allow_pool_delete']): ?>first<?php endif; ?>"><a href="<?php echo $this->_vars['sort_links']['email']; ?>
"<?php if ($this->_vars['order'] == 'email'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_mail_to_email', 'notifications', '', 'text', array()); ?></a></th>
			<th class="w150"><a href="<?php echo $this->_vars['sort_links']['subject']; ?>
"<?php if ($this->_vars['order'] == 'subject'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_subject', 'notifications', '', 'text', array()); ?></a></th>
			<th class="w50"><a href="<?php echo $this->_vars['sort_links']['send_counter']; ?>
"<?php if ($this->_vars['order'] == 'send_counter'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('send_attempts', 'notifications', '', 'text', array()); ?></a></th>
		<?php if ($this->_vars['allow_pool_send'] || $this->_vars['allow_pool_delete']): ?><th class="w50"><?php echo l('actions', 'notifications', '', 'text', array()); ?></th><?php endif; ?>
	</tr>
	<?php if (is_array($this->_vars['senders']) and count((array)$this->_vars['senders'])): foreach ((array)$this->_vars['senders'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
		<?php if ($this->_vars['allow_pool_send'] || $this->_vars['allow_pool_delete']): ?><td class="first w20 center"><input type="checkbox" class="grouping" value="<?php echo $this->_vars['item']['id']; ?>
"></td><?php endif; ?>
		<td class="center"><?php echo $this->_vars['item']['email']; ?>
</td>
		<td class="center"><?php echo $this->_vars['item']['subject']; ?>
</td>
		<td class="center"><?php echo $this->_vars['item']['send_counter']; ?>
</td>
		<?php if ($this->_vars['allow_pool_send'] || $this->_vars['allow_pool_delete']): ?><td class="icons">
			<?php if ($this->_vars['allow_pool_send']): ?><a href="<?php echo $this->_vars['site_url']; ?>
admin/notifications/pool_send/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-play.png" width="16" height="16" border="0" alt="<?php echo l('link_send_pool', 'notifications', '', 'text', array()); ?>" title="<?php echo l('link_send_pool', 'notifications', '', 'text', array()); ?>"></a><?php endif; ?>
		<?php if ($this->_vars['allow_pool_delete']): ?><a href="<?php echo $this->_vars['site_url']; ?>
admin/notifications/pool_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_pool', 'notifications', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_pool', 'notifications', '', 'text', array()); ?>" title="<?php echo l('link_delete_pool', 'notifications', '', 'text', array()); ?>"></a><?php endif; ?>
	</td><?php endif; ?>
</tr>
<?php endforeach; else: ?>
	<tr><td colspan="5" class="center"><?php echo l('no_pool', 'notifications', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
    </table>
    <?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
</div>
</form>

<script>
	<?php echo '
	';  if ($this->_vars['allow_pool_send'] || $this->_vars['allow_pool_delete']):  echo '
	function checkAll(checked){
		if(checked)
			$(\'.grouping:enabled\').prop(\'checked\', true);
		else
			$(\'.grouping:enabled\').prop(\'checked\', false);
	}
	function checkBoxes(){
		if($(\'.grouping:checked\').length > 0){
			return true;
		}else{
			return false;
		}
	}
	function getCheckBoxes(){
		var ProductID = [];
		$(\'[type=checkbox]\').each(function() {
			if (this.checked) {
				ProductID[ProductID.length] = $(this).val();
			}
		});
		return ProductID;
	}
	';  endif;  echo '
	function refresh_pool() {
		$.ajax({
			url: \'';  echo $this->_vars['ajax_pool_url'];  echo '\',
			cache: false,
			success: function(data){
				$(\'#pool_data\').html(data);
			}
		});
	}
	$(\'document\').ready(function(){
		$(\'#refresh\').click(function(){
			refresh_pool();
			return false;
		});
	';  if ($this->_vars['allow_pool_send']):  echo '
		$(\'#send\').click(function(){
			document.location.href = \'';  echo $this->_vars['site_url'];  echo 'admin/notifications/pool_send/\' + getCheckBoxes();
			return false;
		});
	';  endif; ?>
	<?php if ($this->_vars['allow_pool_delete']):  echo '
		$(\'#delete\').click(function(){
			document.location.href = \'';  echo $this->_vars['site_url'];  echo 'admin/notifications/pool_delete/\' + getCheckBoxes();
			return false;
		});
	';  endif;  echo '
	});
	'; ?>

</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
