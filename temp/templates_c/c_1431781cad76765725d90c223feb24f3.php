<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.replace.php');
$this->register_modifier("replace", "tpl_modifier_replace"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:14:58 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_spam_menu'), $this);?>

	<div class="actions">
		<ul>
			<?php if ($this->_vars['alerts']): ?>
				<li>
					<div class="l">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/alerts_delete/without_object/" class="subscribe" id="delete_selected">
							<?php echo l('link_delete_selected', 'spam', '', 'text', array()); ?>
						</a>
					</div>
				</li>
			<?php endif; ?>
		</ul>&nbsp;
	</div>
<?php if ($this->_vars['spam_types_count']): ?>
<div class="menu-level3">
	<ul>
		<?php if (is_array($this->_vars['spam_types']) and count((array)$this->_vars['spam_types'])): foreach ((array)$this->_vars['spam_types'] as $this->_vars['item']): ?>
		<?php $this->assign('stat_header', "stat_header_spam_" . $this->_vars['item']['gid'] . ""); ?>
		<?php if ($this->_vars['filter'] == $this->_vars['item']['gid']):  $this->assign('form_type', $this->_vars['item']['form_type']);  endif; ?>
		<li class="<?php if ($this->_vars['filter'] == $this->_vars['item']['gid']): ?>active<?php endif;  if (! $this->_vars['item']['obj_count']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/index/<?php echo $this->_vars['item']['gid']; ?>
"><?php echo l($this->_vars['stat_header'], 'spam', '', 'text', array()); ?> (<?php echo $this->_vars['item']['obj_count']; ?>
)</a></li>
		<?php endforeach; endif; ?>		
	</ul>
	&nbsp;
</div>
<?php endif; ?>

<form id="alerts_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w20"><?php if ($this->_vars['alerts']): ?><input type="checkbox" id="grouping_all"><?php endif; ?></th>
		<th><?php echo l('field_alert_content', 'spam', '', 'text', array()); ?></th>
		<th class="w100"><?php echo l('field_alert_user', 'spam', '', 'text', array()); ?></th>
		<th class="w100"><a href="<?php echo $this->_vars['sort_links']['date_add']; ?>
"<?php if ($this->_vars['order'] == 'date_add'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_alert_date_add', 'spam', '', 'text', array()); ?></a></th>
		<th class="w50">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['alerts']) and count((array)$this->_vars['alerts'])): foreach ((array)$this->_vars['alerts'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<?php $this->assign('spam_status_name', 'alert_status_'.$this->_vars['item']['spam_status']); ?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>				
			<td class="first center"><input type="checkbox" class="grouping" name="ids[]" value="<?php echo $this->_vars['item']['id']; ?>
"></td>
			<td class="spam_content"><?php if (! $this->_vars['item']['mark']): ?><b><?php endif;  if ($this->_vars['item']['reason']):  echo l('field_spam_reason', 'spam', '', 'text', array()); ?>:<?php echo $this->_vars['item']['reason']; ?>
<br/><?php endif;  echo $this->_run_modifier($this->_vars['item']['content']['content']['list'], 'replace', 'plugin', 1, $this->_vars['item']['content']['rand'], $this->_vars['item']['id']);  if (! $this->_vars['item']['mark']): ?></b><?php endif; ?></td>
			<td class="center"><?php if (! $this->_vars['item']['mark']): ?><b><?php endif;  echo $this->_vars['item']['content']['user_content'];  if (! $this->_vars['item']['mark']): ?></b><?php endif; ?></td>
			<td class="center"><?php if (! $this->_vars['item']['mark']): ?><b><?php endif;  echo $this->_run_modifier($this->_vars['item']['date_add'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format']);  if (! $this->_vars['item']['mark']): ?></b><?php endif; ?></td>
			<td class="icons" style="padding: 8px 15px;">	
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/spam/alerts_show/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-view.png" width="16" height="16" border="0" alt="<?php echo l('link_alerts_show', 'spam', '', 'button', array()); ?>" title="<?php echo l('link_alerts_show', 'spam', '', 'button', array()); ?>"></a>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="<?php if ($this->_vars['form_type'] == 'select_text'): ?>6<?php else: ?>5<?php endif; ?>" class="center"><?php echo l('no_alerts', 'spam', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
</form>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script><?php echo '
var reload_link = "';  echo $this->_vars['site_url']; ?>
admin/spam/index/<?php echo '";
var filter = \'';  echo $this->_vars['filter'];  echo '\';
var order = \'';  echo $this->_vars['order'];  echo '\';
var loading_content;
var order_direction = \'';  echo $this->_vars['order_direction'];  echo '\';
$(function(){
	$("#grouping_all").click(function()
	{
		try {
			var checked_status = this.checked;
			$("input.grouping").each(function()
			{
				this.checked = checked_status;
			});
		} catch (err) {
			alert(err);
		}
	});
	$(\'#ban_all,#unban_all,#delete_object_all\').bind(\'click\', function(){
		if(!$(\'input[type=checkbox].grouping\').is(\':checked\')) return false; 
		if(this.id == \'delete_object_all\' && !confirm(\'';  echo l('note_alerts_delete_object_all', 'spam', '', 'js', array());  echo '\')) return false;
		if(this.id == \'delete_all\' && !confirm(\'';  echo l('note_alerts_delete_all', 'spam', '', 'js', array());  echo '\')) return false;
		$(\'#alerts_form\').attr(\'action\', $(this).find(\'a\').attr(\'href\')).submit();		
		return false;
	});
	$(\'#delete_selected\').bind(\'click\', function(){
		if(!$(\'input[type=checkbox].grouping\').is(\':checked\')) return false; 
		if(!confirm(\'';  echo l('note_alerts_delete_all', 'spam', '', 'js', array());  echo '\')) return false;
		$(\'#alerts_form\').attr(\'action\', $(this).attr(\'href\')).submit();		
		return false;
	});
});
function reload_this_page(value){
	var link = reload_link + filter + \'/\' + value + \'/\' + order + \'/\' + order_direction;
	location.href=link;
}
var div_spam_content = $(".spam_content");
var iframe = div_spam_content.find(\'iframe\');
if(iframe.length){
	iframe.css(\'width\', \'460px\');
}
var a_href = div_spam_content.find(\'a\');
if(a_href.length){
	a_href.css(\'width\', \'460px\');
	a_href.css(\'height\', \'260px\');
}
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
