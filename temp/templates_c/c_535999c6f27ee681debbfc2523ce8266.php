<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:04:07 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/edit"><?php echo l('link_add_cronjob', 'cronjob', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/index/all"><?php echo l('filter_all_cronjob', 'cronjob', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'not_active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['not_active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/index/not_active"><?php echo l('filter_not_active_cronjob', 'cronjob', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['not_active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/index/active"><?php echo l('filter_active_cronjob', 'cronjob', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['active']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><?php echo l('field_cron_title', 'cronjob', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_cron_tab', 'cronjob', '', 'text', array()); ?></th>
	<th class="w150"><?php echo l('field_date_add', 'cronjob', '', 'text', array()); ?> / <?php echo l('field_date_execute', 'cronjob', '', 'text', array()); ?></th>
	<th><?php echo l('field_expiried', 'cronjob', '', 'text', array()); ?></th>
	<th><?php echo l('field_in_process', 'cronjob', '', 'text', array()); ?></th>
	<th class="w150">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['crontab']) and count((array)$this->_vars['crontab'])): foreach ((array)$this->_vars['crontab'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td><?php echo $this->_vars['item']['cron_tab']; ?>
</td>
	<td class="center"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
 / <?php if ($this->_vars['item']['date_execute'] != '0000-00-00 00:00:00'):  echo $this->_run_modifier($this->_vars['item']['date_execute'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']);  endif; ?></td>
	<td class="center"><?php if ($this->_vars['item']['expiried']):  echo l('crontab_expiried', 'cronjob', '', 'text', array());  endif; ?></td>
	<td class="center"><?php if ($this->_vars['item']['in_process']):  echo l('crontab_in_process', 'cronjob', '', 'text', array());  else: ?>&nbsp;<?php endif; ?></td>
	<td class="icons">
		<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_cron', 'cronjob', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_activate_cron', 'cronjob', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_edit_cron', 'cronjob', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_cron', 'cronjob', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_delete_cron', 'cronjob', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/run/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-play.png" width="16" height="16" border="0" alt="<?php echo l('link_run_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_run_cron', 'cronjob', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/cronjob/log/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-copy.png" width="16" height="16" border="0" alt="<?php echo l('link_log_cron', 'cronjob', '', 'text', array()); ?>" title="<?php echo l('link_log_cron', 'cronjob', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center"><?php echo l('no_crontabs', 'cronjob', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
