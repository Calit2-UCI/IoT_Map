<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:01:14 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first"><?php echo l('field_number', 'comments', '', 'text', array()); ?></th>
		<th><?php echo l('field_name', 'comments', '', 'text', array()); ?></th>
		<th><?php echo l('field_char_count', 'comments', '', 'text', array()); ?></th>
		<th><?php echo l('field_guest_access', 'comments', '', 'text', array()); ?></th>
		<th><?php echo l('field_use_likes', 'comments', '', 'text', array()); ?></th>
		<th>&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['comments_types']) and count((array)$this->_vars['comments_types'])): foreach ((array)$this->_vars['comments_types'] as $this->_vars['comments_type']): ?>
		<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
		<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
			<td class="first w20 center"><?php echo $this->_vars['counter']; ?>
</td>
			<td><?php echo l('ctype_'.$this->_vars['comments_type']['gid'], 'comments', '', 'text', array()); ?></td>
			<td class="center"><?php echo $this->_vars['comments_type']['settings']['char_count']; ?>
</td>
			<td class="center">
				<?php if ($this->_vars['comments_type']['gid'] != 'user_avatar'): ?>
					<?php if ($this->_vars['comments_type']['settings']['guest_access']): ?>
						<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="" title="">
					<?php else: ?>
						<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="" title="">
					<?php endif; ?>
				<?php endif; ?>
			</td>
			<td class="center">
				<?php if ($this->_vars['comments_type']['settings']['use_likes']): ?>
					<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="" title="">
				<?php else: ?>
					<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="" title="">
				<?php endif; ?>
			</td>
			<td class="w150 icons">
				<span>
					<a href="javascript:void(0);" onclick="activateCommentsType(<?php echo $this->_vars['comments_type']['id']; ?>
, 0, this);" <?php if (! $this->_vars['comments_type']['status']): ?>style="display:none;"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-full.png" width="16" height="16" alt="<?php echo l('link_deactivate_comments_type', 'comments', '', 'text', array()); ?>" title="<?php echo l('link_deactivate_comments_type', 'comments', '', 'text', array()); ?>"></a>
					<a href="javascript:void(0);" onclick="activateCommentsType(<?php echo $this->_vars['comments_type']['id']; ?>
, 1, this);" <?php if ($this->_vars['comments_type']['status']): ?>style="display:none;"<?php endif; ?>><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-empty.png" width="16" height="16" alt="<?php echo l('link_activate_comments_type', 'comments', '', 'text', array()); ?>" title="<?php echo l('link_activate_comments_type', 'comments', '', 'text', array()); ?>"></a>
				</span>
				<a href='<?php echo $this->_vars['site_url']; ?>
admin/comments/edit_type/<?php echo $this->_vars['comments_type']['id']; ?>
'><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-edit.png" width="16" height="16" alt="<?php echo l('link_edit_comments_type', 'comments', '', 'text', array()); ?>" title="<?php echo l('link_edit_comments_type', 'comments', '', 'text', array()); ?>"></a>
			</td>
		</tr>
	<?php endforeach; else: ?>
		<tr><td colspan="8" class="center"><?php echo l('no_comments_types', 'comments', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type='text/javascript'>
<?php echo '
	function activateCommentsType(id, status, a_obj){
		$.post(
			site_url+\'admin/comments/ajax_activate_type/\',
			{id: id, status: status},
			function(resp){
				if(resp.status){
					$(a_obj).parent().find(\'a:hidden\').show();
					$(a_obj).hide();
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
