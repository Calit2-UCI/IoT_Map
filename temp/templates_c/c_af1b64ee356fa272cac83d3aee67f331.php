<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-04 20:27:36 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>

<script type="text/javascript"><?php echo '
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
'; ?>
</script>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['comments_type']['id']):  echo l('admin_header_comments_type_change', 'comments', '', 'text', array());  else:  echo l('admin_header_comments_type_add', 'comments', '', 'text', array());  endif; ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_status', 'comments', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="status" <?php if ($this->_vars['comments_type']['status']): ?>checked<?php endif; ?>/></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_use_likes', 'comments', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="use_likes" <?php if ($this->_vars['comments_type']['settings']['use_likes']): ?>checked<?php endif; ?>/></div>
		</div>
		<?php if ($this->_vars['comments_type']['gid'] != 'user_avatar'): ?>
		<div class="row">
			<div class="h"><?php echo l('field_guest_access', 'comments', '', 'text', array()); ?>: </div>
			<div class="v"><input type="checkbox" value="1" name="guest_access" <?php if ($this->_vars['comments_type']['settings']['guest_access']): ?>checked<?php endif; ?>/></div>
		</div>
		<?php endif; ?>
		<div class="row">
			<div class="h"><?php echo l('field_char_count', 'comments', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['comments_type']['settings']['char_count']; ?>
" name="char_count" class="middle"/></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/comments/index/<?php echo $this->_vars['data']['page']; ?>
/"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>


<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
