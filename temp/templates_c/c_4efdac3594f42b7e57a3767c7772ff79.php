<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 04:21:08 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_wall_events_menu'), $this);?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><?php echo l('field_number', 'wall_events', '', 'text', array()); ?></th>
		<th><?php echo l('field_name', 'wall_events', '', 'text', array()); ?></th>
		<th><?php echo l('field_join_period', 'wall_events', '', 'text', array()); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['wall_events_types']) and count((array)$this->_vars['wall_events_types'])): foreach ((array)$this->_vars['wall_events_types'] as $this->_vars['wall_events_type']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first w20 center"><?php echo $this->_vars['counter']; ?>
</td>
			<td><?php echo l('wetype_'.$this->_vars['wall_events_type']['gid'], 'wall_events', '', 'text', array()); ?></td>
			<td class="center"><?php echo $this->_vars['wall_events_type']['settings']['join_period']; ?>
</td>
			<td class="w150 icons">
				<span>
					<a href="javascript:void(0);" onclick="activateWallEventsType('<?php echo $this->_run_modifier($this->_vars['wall_events_type']['gid'], 'escape', 'plugin', 1, javascript); ?>
', 0, this);" <?php if (! $this->_vars['wall_events_type']['status']): ?>style="display:none;"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="<?php echo l('link_deactivate_wall_events_type', 'wall_events', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_wall_events_type', 'wall_events', '', 'text', array()); ?>"></a>
					<a href="javascript:void(0);" onclick="activateWallEventsType('<?php echo $this->_run_modifier($this->_vars['wall_events_type']['gid'], 'escape', 'plugin', 1, javascript); ?>
', 1, this);" <?php if ($this->_vars['wall_events_type']['status']): ?>style="display:none;"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="<?php echo l('link_activate_wall_events_type', 'wall_events', '', 'text', array()); ?>" title="<?php echo l('link_activate_wall_events_type', 'wall_events', '', 'text', array()); ?>"></a>
				</span>
				<a href='<?php echo $this->_vars['site_url']; ?>
admin/wall_events/edit_type/<?php echo $this->_vars['wall_events_type']['gid']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('link_edit_wall_events_type', 'wall_events', '', 'text', array()); ?>" title="<?php echo l('link_edit_wall_events_type', 'wall_events', '', 'text', array()); ?>"></a>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="8" class="center"><?php echo l('no_wall_events_types', 'wall_events', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type='text/javascript'>
<?php echo '
	function activateWallEventsType(gid, status, a_obj){
		$.post(
			site_url+\'admin/wall_events/ajax_activate_type/\',
			{gid: gid, status: status},
			function(resp){
				if(resp.status){
					$(a_obj).parent().find(\'a:hidden\').show();
					$(a_obj).hide();
					if (status==1) {
						 error_object.show_error_block("';  echo l('wetype_activated', 'wall_events', '', 'text', array());  echo '", \'success\');
					}else{
						 error_object.show_error_block("';  echo l('wetype_deactivated', 'wall_events', '', 'text', array());  echo '", \'success\');
					}
					
				}
			},
			\'json\'
		);
	}
'; ?>

</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
