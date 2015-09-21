<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-04 20:37:11 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="menu-level2">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/installed_themes"><?php echo l('header_installed_themes', 'themes', '', 'text', array()); ?></a></div></li>
		<li class="active"><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/enable_themes"><?php echo l('header_enable_themes', 'themes', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['type'] == 'user'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/enable_themes/user"><?php echo l('filter_user_themes', 'themes', '', 'text', array()); ?></a></li>
		<li class="<?php if ($this->_vars['type'] == 'admin'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/enable_themes/admin"><?php echo l('filter_admin_themes', 'themes', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150">&nbsp;</th>
	<th class="w100"><?php echo l('field_theme', 'themes', '', 'text', array()); ?></th>
	<th><?php echo l('field_description', 'themes', '', 'text', array()); ?></th>
	<th class="w70">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['themes']) and count((array)$this->_vars['themes'])): foreach ((array)$this->_vars['themes'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first"><?php if ($this->_vars['item']['img']): ?><img src="<?php echo $this->_vars['item']['img']; ?>
" class="img"><?php endif; ?></td>
	<td class="center"><?php echo $this->_vars['item']['theme']; ?>
</td>
	<td><b><?php echo $this->_vars['item']['theme_name']; ?>
</b><br><?php echo $this->_vars['item']['theme_description']; ?>
</td>
	<td class="icons">
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/themes/install/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo l('link_install_theme', 'themes', '', 'text', array()); ?></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_themes', 'themes', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
