<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.truncate.php');
$this->register_modifier("truncate", "tpl_modifier_truncate"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 10:20:08 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150"><?php echo l('field_title', 'geomap', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_status', 'geomap', '', 'text', array()); ?></th>
	<th><?php echo l('field_regkey', 'geomap', '', 'text', array()); ?></th>
	<th class="w50"><?php echo l('field_link', 'geomap', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['drivers']) and count((array)$this->_vars['drivers'])): foreach ((array)$this->_vars['drivers'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center">
		<?php if ($this->_vars['item']['status']): ?>
		<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_active_geomap', 'geomap', '', 'button', array()); ?>" title="<?php echo l('link_active_geomap', 'geomap', '', 'button', array()); ?>">
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/geomap/activate/<?php echo $this->_vars['item']['gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_geomap', 'geomap', '', 'button', array()); ?>" title="<?php echo l('link_activate_geomap', 'geomap', '', 'button', array()); ?>"></a>
		<?php endif; ?>
	</td>
	<td>
		<?php if ($this->_vars['item']['need_regkey']): ?>
			<?php echo $this->_run_modifier($this->_vars['item']['regkey'], 'truncate', 'plugin', 1, 50, "...", true); ?>

		<?php else: ?>
			<?php echo l('driver_key_notrequired', 'geomap', '', 'text', array()); ?>
		<?php endif; ?>
	</td>
	<td class="center">
		<a href="<?php echo $this->_vars['item']['link']; ?>
" target="_blank">
			<?php if ($this->_vars['item']['need_regkey']): ?>
				<?php echo l('driver_registration', 'geomap', '', 'text', array()); ?>
			<?php else: ?>
				<?php echo l('driver_info', 'geomap', '', 'text', array()); ?>
			<?php endif; ?>
		</a>
	</td>
	<td class="icons">
		<?php if ($this->_vars['item']['need_regkey']): ?><a href="<?php echo $this->_vars['site_url']; ?>
admin/geomap/edit/<?php echo $this->_vars['item']['gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_driver', 'geomap', '', 'button', array()); ?>" title="<?php echo l('link_edit_driver', 'geomap', '', 'button', array()); ?>"></a><?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/geomap/settings/<?php echo $this->_vars['item']['gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-settings.png" width="16" height="16" border="0" alt="<?php echo l('link_settings_driver', 'geomap', '', 'button', array()); ?>" title="<?php echo l('link_settings_driver', 'geomap', '', 'button', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="5" class="center"><?php echo l('no_drivers', 'geomap', '', 'text', array()); ?></td></tr>
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
