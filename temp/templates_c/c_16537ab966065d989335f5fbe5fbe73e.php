<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:05:53 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_news_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/news/edit"><?php echo l('link_add_news', 'news', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w150"><a href="<?php echo $this->_vars['sort_links']['date_add']; ?>
"<?php if ($this->_vars['order'] == 'date_add'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_add', 'news', '', 'text', array()); ?></a></th>
	<th><a href="<?php echo $this->_vars['sort_links']['name']; ?>
"<?php if ($this->_vars['order'] == 'name'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_name', 'news', '', 'text', array()); ?></a></th>
	<th class="w100"><?php echo l('field_news_type', 'news', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['news']) and count((array)$this->_vars['news'])): foreach ((array)$this->_vars['news'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td><?php echo $this->_vars['item']['name']; ?>
</td>
	<td class="center"><?php echo $this->_vars['item']['news_type']; ?>
</td>
	<td class="icons">
		<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_news', 'news', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_news', 'news', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_news', 'news', '', 'text', array()); ?>" title="<?php echo l('link_activate_news', 'news', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_news', 'news', '', 'text', array()); ?>" title="<?php echo l('link_edit_news', 'news', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_news', 'news', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_news', 'news', '', 'text', array()); ?>" title="<?php echo l('link_delete_news', 'news', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="4" class="center"><?php echo l('no_news', 'news', '', 'text', array()); ?></td></tr>
<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
