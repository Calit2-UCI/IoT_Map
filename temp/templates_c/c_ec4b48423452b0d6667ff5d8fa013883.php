<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:15:04 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_spam_menu'), $this);?>
<div class="actions">
	&nbsp;
</div>
<form id="types_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w150"><?php echo l('field_type_gid', 'spam', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_type_form_type', 'spam', '', 'text', array()); ?></th>
		<th class="w70"><?php echo l('field_type_send_mail', 'spam', '', 'text', array()); ?></th>
		<th class="w70"><?php echo l('field_type_status', 'spam', '', 'text', array()); ?></th>
		<th class="w100">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['types']) and count((array)$this->_vars['types'])): foreach ((array)$this->_vars['types'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<?php $this->assign('item_name', "stat_header_spam_" . $this->_vars['item']['gid'] . ""); ?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>				
			<td class="center"><?php echo l($this->_vars['item_name'], 'spam', '', 'text', array()); ?></td>
			<td class="center"><?php echo $this->_vars['item']['form']; ?>
</td>
			</td>
			<td class="center">
				<?php if ($this->_vars['item']['send_mail']): ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/type_send_mail/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_send_mail_off', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_send_mail_off', 'spam', '', 'button', array()); ?>"></a>
				<?php else: ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/type_send_mail/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_send_mail_on', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_send_mail_on', 'spam', '', 'button', array()); ?>"></a>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php if ($this->_vars['item']['status']): ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/type_activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_type_deactivate', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_type_deactivate', 'spam', '', 'button', array()); ?>"></a>
				<?php else: ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/type_activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_type_activate', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_type_activate', 'spam', '', 'button', array()); ?>"></a>
				<?php endif; ?>
			</td>
			<td class="icons">				
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/types_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_types_edit', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_types_edit', 'spam', '', 'button', array()); ?>"></a>				
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="6" class="center"><?php echo l('no_types', 'spam', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
</form>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
