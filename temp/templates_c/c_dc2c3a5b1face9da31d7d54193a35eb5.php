<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:06:54 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_shoutbox_menu'), $this);?>
<div class="actions">
	<ul>
		<?php if ($this->_vars['messages']): ?>
			<li>
				<div class="l">
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/shoutbox/messages_delete/" class="subscribe" id="delete_selected">
						<?php echo l('link_delete_selected', 'shoutbox', '', 'text', array()); ?>
					</a>
				</div>
			</li>
		<?php endif; ?>
	</ul>&nbsp;
</div>

<form id="messages_form" action="" method="post">
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><input type="checkbox" id="grouping_all"></th>
		<th class="w300"><a href="<?php echo $this->_vars['sort_links']['message']; ?>
"<?php if ($this->_vars['order'] == 'message'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_message', 'shoutbox', '', 'text', array()); ?></a></th>
		<th><?php echo l('field_author', 'shoutbox', '', 'text', array()); ?></th>
		<th><a href="<?php echo $this->_vars['sort_links']['date_created']; ?>
"<?php if ($this->_vars['order'] == 'date_created'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_created', 'shoutbox', '', 'text', array()); ?></a></th>
		<th class="w30">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['messages']) and count((array)$this->_vars['messages'])): foreach ((array)$this->_vars['messages'] as $this->_vars['message']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first w20 center"><input type="checkbox" class="grouping" value="<?php echo $this->_vars['message']['id']; ?>
" name="ids[]"></td>
			<td><?php echo $this->_vars['message']['message']; ?>
</td>
			<td class="center">
				<?php if ($this->_vars['message']['user_info']['nickname']): ?>
					<?php echo $this->_vars['message']['user_info']['nickname']; ?>
 <?php if ($this->_vars['message']['user_info']['output_name'] != ''): ?>(<?php echo $this->_vars['message']['user_info']['output_name']; ?>
)<?php endif; ?>
				<?php else: ?>
					<font class="error"><?php echo $this->_vars['message']['user_info']['output_name']; ?>
</font>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php echo $this->_vars['message']['date_created']; ?>

			</td>
			<td class="w50 icons">
				<?php echo tpl_function_block(array('name' => 'contact_user_link','module' => 'tickets','id_user' => $this->_vars['message']['user_info']['id']), $this);?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/shoutbox/delete/<?php echo $this->_vars['message']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_message', 'shoutbox', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_message', 'shoutbox', '', 'text', array()); ?>" title="<?php echo l('link_delete_message', 'shoutbox', '', 'text', array()); ?>"></a>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="5" class="center"><?php echo l('no_messages', 'shoutbox', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
</form>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<script type="text/javascript">
<?php echo '
$(function(){
	$(\'#grouping_all\').bind(\'click\', function(){
		var checked = $(this).is(\':checked\');
		if(checked){
			$(\'input.grouping\').prop(\'checked\', true);
		}else{
			$(\'input.grouping\').prop(\'checked\', false);
		}
	});
	$(\'#delete_selected\').bind(\'click\', function(){
		if(!$(\'input[type=checkbox].grouping\').is(\':checked\')) return false; 
		if(!confirm(\'';  echo l('note_alerts_delete_all', 'shoutbox', '', 'js', array());  echo '\')) return false;
		$(\'#messages_form\').attr(\'action\', $(this).attr(\'href\')).submit();		
		return false;
	});
});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
