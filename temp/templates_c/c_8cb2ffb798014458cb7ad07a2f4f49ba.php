<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:01:39 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_video_menu'), $this);?>
<div class="actions">&nbsp;</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w100"><?php echo l('field_gid', 'video_uploads', '', 'text', array()); ?></th>
	<th class=""><?php echo l('field_name', 'video_uploads', '', 'text', array()); ?></th>
	<th class="w100"><?php echo l('field_upload_type', 'video_uploads', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['configs']) and count((array)$this->_vars['configs'])): foreach ((array)$this->_vars['configs'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><b><?php echo $this->_vars['item']['gid']; ?>
</b></td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center">
		<?php if ($this->_vars['item']['upload_type'] == 'local'):  echo l('field_upload_type_local', 'video_uploads', '', 'text', array()); ?>
		<?php elseif ($this->_vars['item']['upload_type'] == 'youtube'):  echo l('field_upload_type_youtube', 'video_uploads', '', 'text', array()); ?>
		<?php endif; ?>
	</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/video_uploads/config_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_config', 'video_uploads', '', 'text', array()); ?>" title="<?php echo l('link_edit_config', 'video_uploads', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td class="center zebra" colspan=3><?php echo l('no_configs', 'video_uploads', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
