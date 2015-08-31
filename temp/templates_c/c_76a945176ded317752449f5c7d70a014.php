<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.lower.php');
$this->register_modifier("lower", "tpl_modifier_lower"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:09:52 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo tpl_function_helper(array('func_name' => 'get_admin_level1_menu','helper_name' => 'menu','func_param' => 'admin_polls_menu'), $this);?>

<div class="actions">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/edit"><?php echo l('link_add_poll', 'polls', '', 'text', array()); ?></a></div></li>

	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="<?php if ($this->_vars['filter'] == 'all'): ?>active<?php endif;  if (! $this->_vars['filter_data']['all']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/index/all"><?php echo l('filter_all_polls', 'polls', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['all']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'active'): ?>active<?php endif;  if (! $this->_vars['filter_data']['active']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/index/active"><?php echo l('filter_active_polls', 'polls', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['active']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'feauture'): ?>active<?php endif;  if (! $this->_vars['filter_data']['feauture']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/index/feauture"><?php echo l('filter_feauture_polls', 'polls', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['feauture']; ?>
)</a></li>
		<li class="<?php if ($this->_vars['filter'] == 'end'): ?>active<?php endif;  if (! $this->_vars['filter_data']['end']): ?> hide<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/index/end"><?php echo l('filter_end_polls', 'polls', '', 'text', array()); ?> (<?php echo $this->_vars['filter_data']['end']; ?>
)</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		
		<th class="first"><a href="<?php echo $this->_vars['sort_links']['question']; ?>
"<?php if ($this->_vars['order'] == 'question'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_question', 'polls', '', 'text', array()); ?></a></th>
		<th><a href="<?php echo $this->_vars['sort_links']['language']; ?>
"<?php if ($this->_vars['order'] == 'language'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_language', 'polls', '', 'text', array()); ?></a></th>
		<th><a href="<?php echo $this->_vars['sort_links']['poll_type']; ?>
"<?php if ($this->_vars['order'] == 'poll_type'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_poll_type', 'polls', '', 'text', array()); ?></a></th>
		<th><a href="<?php echo $this->_vars['sort_links']['date_start']; ?>
"<?php if ($this->_vars['order'] == 'date_start'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_start', 'polls', '', 'text', array()); ?></a></th>
		<th><a href="<?php echo $this->_vars['sort_links']['date_end']; ?>
"<?php if ($this->_vars['order'] == 'date_end'): ?> class="<?php echo $this->_run_modifier($this->_vars['order_direction'], 'lower', 'plugin', 1); ?>
"<?php endif; ?>><?php echo l('field_date_end', 'polls', '', 'text', array()); ?></a></th>
		<th class="w150">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['polls']) and count((array)$this->_vars['polls'])): foreach ((array)$this->_vars['polls'] as $this->_vars['item']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<?php if ($this->_vars['item']['language']): ?>
			<?php $this->assign('cur_lang', $this->_vars['item']['language']); ?>
		<?php endif; ?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first"><?php if ($this->_vars['item']['question'][$this->_vars['cur_lang']]):  echo $this->_vars['item']['question'][$this->_vars['cur_lang']];  else:  echo $this->_vars['item']['question']['default'];  endif; ?></td>
			<td><?php if ($this->_vars['item']['language']):  $this->assign('lang_id', $this->_vars['item']['language']);  echo $this->_vars['languages'][$this->_vars['lang_id']]['name'];  else:  echo l('all_languages', 'polls', '', 'text', array());  endif; ?></td>
			<td><?php if ($this->_vars['item']['poll_type_val']):  echo $this->_vars['item']['poll_type_val'];  elseif ($this->_vars['item']['poll_type'] == -1):  echo l('poll_type_1', 'polls', '', 'text', array());  elseif ($this->_vars['item']['poll_type'] == -2):  echo l('poll_type_2', 'polls', '', 'text', array());  else:  echo l('poll_type_0', 'polls', '', 'text', array());  endif; ?></td>
			<td><?php echo $this->_vars['item']['date_start']; ?>
</td>
			<td><?php if ($this->_vars['item']['use_expiration']):  echo $this->_vars['item']['date_end'];  else:  echo l('field_unlim', 'polls', '', 'text', array());  endif; ?></td>
			<td class="icons">
			<?php if (( $this->_vars['item']['status'] && ! $this->_vars['item']['use_expiration'] ) || $this->_vars['item']['status']): ?>

					<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/activate/<?php echo $this->_vars['item']['id']; ?>
/0"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" border="0" alt="<?php echo l('link_deactivate_poll', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_poll', 'polls', '', 'text', array()); ?>"></a>
				<?php else: ?>
					<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/activate/<?php echo $this->_vars['item']['id']; ?>
/1"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" border="0" alt="<?php echo l('link_activate_poll', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_activate_poll', 'polls', '', 'text', array()); ?>"></a>
				<?php endif; ?>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/results/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-stats.png" width="16" height="16" border="0" alt="<?php echo l('link_results', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_results', 'polls', '', 'text', array()); ?>"></a>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/answers/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-list.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_answers', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_edit_answers', 'polls', '', 'text', array()); ?>"></a>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/edit/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" border="0" alt="<?php echo l('link_edit_poll', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_edit_poll', 'polls', '', 'text', array()); ?>"></a>
				<a href="<?php echo $this->_vars['site_url']; ?>
admin/polls/delete/<?php echo $this->_vars['item']['id']; ?>
" onclick="javascript: if(!confirm('<?php echo l('note_delete_poll', 'polls', '', 'js', array()); ?>')) return false;"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_poll', 'polls', '', 'text', array()); ?>" title="<?php echo l('link_delete_poll', 'polls', '', 'text', array()); ?>"></a>

			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="8" class="center"><?php echo l('no_polls', 'polls', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<?php echo '
	<script type="text/javascript">
		$(function(){
			$(\'input#grouping_all\').bind(\'click\', function(){
				var checked = $(this).is(\':checked\');
				if(checked){
					$(\'input[type=checkbox]\').prop(\'checked\', true);
				}else{
					$(\'input[type=checkbox]\').prop(\'checked\', false);
				}
			});
		});

	</script>
'; ?>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
