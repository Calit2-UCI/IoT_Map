<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 23:55:09 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_form_change', 'field_editor', '', 'text', array());  else:  echo l('admin_header_form_add', 'field_editor', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v"><?php if ($this->_vars['data']['id']): ?><input type="hidden" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid"><?php echo $this->_vars['data']['gid'];  else: ?><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid"><?php endif; ?></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_form_type', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if ($this->_vars['data']['id']): ?>
				<?php if (is_array($this->_vars['types']) and count((array)$this->_vars['types'])): foreach ((array)$this->_vars['types'] as $this->_vars['item']):  if ($this->_vars['item']['gid'] == $this->_vars['data']['editor_type_gid']): ?><input type="hidden" value="<?php echo $this->_vars['data']['editor_type_gid']; ?>
" name="editor_type_gid"><?php echo $this->_vars['item']['name'];  endif;  endforeach; endif; ?>
				<?php else: ?>
				<select name="editor_type_gid"><?php if (is_array($this->_vars['types']) and count((array)$this->_vars['types'])): foreach ((array)$this->_vars['types'] as $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['gid']; ?>
"<?php if ($this->_vars['item']['gid'] == $this->_vars['data']['editor_type_gid']): ?>selected<?php endif; ?>><?php echo $this->_vars['key']; ?>
 <?php echo $this->_vars['item']['name']; ?>
</option><?php endforeach; endif; ?></select>
				<?php endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_name', 'field_editor', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/field_editor/forms/<?php echo $this->_vars['data']['editor_type_gid']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
$(function(){
	$("div.row:odd").addClass("zebra");
});

'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>