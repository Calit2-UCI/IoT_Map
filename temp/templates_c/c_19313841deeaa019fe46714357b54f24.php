<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.ld_option.php');
$this->register_function("ld_option", "tpl_function_ld_option"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 01:30:49 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="menu-level2">
	<ul>
		<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/<?php echo $this->_vars['user_id']; ?>
"><?php echo l('table_header_personal', 'users', '', 'text', array()); ?></a></div></li>
		<?php if ($this->_vars['sections']): ?>
			<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<li><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/<?php echo $this->_vars['item']['gid']; ?>
/<?php echo $this->_vars['user_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
			<?php endforeach; endif; ?>
		<?php endif; ?>
		<?php /*
			<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/seo/<?php echo $this->_vars['user_id']; ?>
"><?php echo l('filter_section_seo', 'seo', '', 'text', array()); ?></a></li>
		*/ ?>
		<li class="active"><a href="<?php echo $this->_vars['site_url']; ?>
admin/media/user_media/<?php echo $this->_vars['user_id']; ?>
/<?php echo $this->_vars['param']; ?>
"><?php echo l('filter_section_uploads', 'media', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>
<div class="actions">&nbsp;</div>
<div class="menu-level3">
	<ul>
		<li <?php if ($this->_vars['param'] == 'photo'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_vars['site_url']; ?>
admin/media/user_media/<?php echo $this->_vars['user_id']; ?>
/photo"><?php echo l('filter_section_photos', 'media', '', 'text', array()); ?></a></li>
		<li <?php if ($this->_vars['param'] == 'video'): ?>class="active"<?php endif; ?>><a href="<?php echo $this->_vars['site_url']; ?>
admin/media/user_media/<?php echo $this->_vars['user_id']; ?>
/video"><?php echo l('filter_section_videos', 'media', '', 'text', array()); ?></a></li>
	</ul>
	&nbsp;
</div>
<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w110"><?php echo l('field_files', 'media', '', 'text', array()); ?></th>
		<th><?php echo l('media_info', 'media', '', 'text', array()); ?></th>
		<th class="w100">&nbsp;</th>
	</tr>
	<?php if (is_array($this->_vars['media']) and count((array)$this->_vars['media'])): foreach ((array)$this->_vars['media'] as $this->_vars['item']): ?>
	<?php echo tpl_function_counter(array('print' => false,'assign' => counter), $this);?>
	<tr<?php if (!($this->_vars['counter'] % 2)): ?> class="zebra"<?php endif; ?>>
		<td class="first center">
			<?php if ($this->_vars['item']['media']): ?>
				<a href="<?php echo $this->_vars['item']['media']['mediafile']['file_url']; ?>
" target="_blank"><img src="<?php echo $this->_vars['item']['media']['mediafile']['thumbs']['small']; ?>
"/></a>
			<?php endif; ?>
			<?php if ($this->_vars['item']['video_content']): ?>
				<span onclick="vpreview = new loadingContent(<?php echo '{\'closeBtnClass\': \'w\'}'; ?>
); vpreview.show_load_block('<?php echo $this->_run_modifier($this->_vars['item']['video_content']['embed'], 'escape', 'plugin', 1); ?>
');"><img class="pointer" src="<?php echo $this->_vars['item']['video_content']['thumbs']['small']; ?>
"/></span>
				
			<?php endif; ?>
		</td>
		<td>
			<b><?php echo l('media_owner', 'media', '', 'text', array()); ?></b>: <?php echo $this->_vars['item']['owner_info']['output_name']; ?>
<br>			
			<b><?php echo l('media_user', 'media', '', 'text', array()); ?></b>: <?php echo $this->_vars['item']['user_info']['output_name']; ?>
<br>			
			<b><?php echo l('field_permitted_for', 'media', '', 'text', array()); ?></b>: <?php echo tpl_function_ld_option(array('i' => 'permissions','gid' => 'media','option' => $this->_vars['item']['permissions']), $this);?>
		</td>
		<td class="icons">
			<a href="<?php echo $this->_vars['site_url']; ?>
admin/media/delete_media/<?php echo $this->_vars['item']['id']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-delete.png" width="16" height="16" border="0" alt="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>" title="<?php echo l('link_delete_service', 'packages', '', 'text', array()); ?>"></a>
		</td>
	</tr>
	<?php endforeach; else: ?>
	<tr><td colspan="4" class="center"><?php echo l('no_media', 'media', '', 'text', array()); ?></td></tr>
	<?php endif; ?>
</table>
<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "pagination.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<div class="clr"></div>
<a class="cancel" href="<?php echo $this->_vars['back_url']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
