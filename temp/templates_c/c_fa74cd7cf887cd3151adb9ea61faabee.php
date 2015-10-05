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
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-29 03:11:00 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_news_menu'), $this);?>
<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_edit"><?php echo l('link_add_feeds', 'news', '', 'text', array()); ?></a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['id_lang'] == 0): ?>active<?php endif;  if (! $this->_vars['filter_data']['0']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feeds/0"><?php echo l('filter_all_feeds', 'news', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['0']; ?>
)</a></li>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lid'] => $this->_vars['item']): ?>
		<li class="<?php if ($this->_vars['lid'] == $this->_vars['id_lang']): ?>active<?php endif;  if (! $this->_vars['filter_data'][$this->_vars['lid']]): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feeds/<?php echo $this->_vars['lid']; ?>
"><?php echo $this->_vars['item']['name']; ?>
 (<?php echo $this->_vars['filter_data'][$this->_vars['lid']]; ?>
)</a></li>
		<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first"><a href="<?php echo $this->_vars['sort_links']['date_add']; ?>
"<?php if ($this->_vars['order'] == 'date_add'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_add', 'news', '', 'text', array()); ?></a></th>
	<th><?php echo l('field_feed_title', 'news', '', 'text', array()); ?></th>
	<th class="w100">&nbsp;</th>
</tr>
<?php if (is_array($this->_vars['feeds']) and count((array)$this->_vars['feeds'])): foreach ((array)$this->_vars['feeds'] as $this->_vars['item']):  echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
	<td class="first center w150"><?php echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']); ?>
</td>
	<td><b><?php echo $this->_vars['item']['title']; ?>
</b><?php if ($this->_vars['item']['description']): ?><br><i><?php echo $this->_vars['item']['description']; ?>
</i><?php endif; ?></td>
	<td class="icons">
		<?php if ($this->_vars['item']['status']): ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_feed', 'news', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_feed', 'news', '', 'text', array()); ?>"></a>
		<?php else: ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_feed', 'news', '', 'text', array()); ?>" title="<?php echo l('link_activate_feed', 'news', '', 'text', array()); ?>"></a>
		<?php endif; ?>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_feed', 'news', '', 'text', array()); ?>" title="<?php echo l('link_edit_feed', 'news', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_parse/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-play.png" width="16" height="16" border="0" alt="<?php echo l('link_parse_feed', 'news', '', 'text', array()); ?>" title="<?php echo l('link_parse_feed', 'news', '', 'text', array()); ?>"></a>
		<a href="<?php echo $this->_vars['site_url']; ?>
admin/news/feed_delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_feed', 'news', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_feed', 'news', '', 'text', array()); ?>" title="<?php echo l('link_delete_feed', 'news', '', 'text', array()); ?>"></a>
	</td>
</tr>
<?php endforeach; else: ?>
<tr><td colspan="3" class="center"><?php echo l('no_feeds', 'news', '', 'text', array()); ?></td></tr>
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
