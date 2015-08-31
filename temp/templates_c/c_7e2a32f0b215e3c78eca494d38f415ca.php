<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:03:26 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header"><?php echo l('header_settings', 'users', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_allow"><?php echo l('field_guest_view_profile_allow', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_allow" type="checkbox" name="guest_view_profile_allow" 
					   value="1" <?php if ($this->_vars['users_settings']['guest_view_profile_allow']): ?>checked="checked"<?php endif; ?>>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_limit"><?php echo l('field_guest_view_profile_limit', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_limit" type="checkbox" name="guest_view_profile_limit" 
					   value="1" <?php if ($this->_vars['users_settings']['guest_view_profile_limit']): ?>checked="checked"<?php endif; ?>>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="guest_view_profile_num"><?php echo l('field_guest_view_profile_num', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="guest_view_profile_num" type="number" min="0" max="1000" name="guest_view_profile_num" 
					   value="<?php echo $this->_vars['users_settings']['guest_view_profile_num']; ?>
" class="short">
			</div>
		</div>

		<div class="row">
			<div class="h">
				<label for="user_approve"><?php echo l('field_user_approve', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<select id="user_approve" name="user_approve" class="middle_short">
					<option value="0"<?php if ($this->_vars['users_settings']['user_approve'] == '0'): ?>selected="selected"<?php endif; ?>>
						<?php echo l('field_user_approve_no_value', 'users', '', 'text', array()); ?>
					</option>
					<option value="1"<?php if ($this->_vars['users_settings']['user_approve'] == '1'): ?>selected="selected"<?php endif; ?>>
						<?php echo l('field_user_approve_admin_value', 'users', '', 'text', array()); ?>
					</option>
					<option value="2"<?php if ($this->_vars['users_settings']['user_approve'] == '2'): ?>selected="selected"<?php endif; ?>>
						<?php echo l('field_user_approve_service_value', 'users', '', 'text', array()); ?>
					</option>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="user_confirm"><?php echo l('field_user_confirm', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="user_confirm" type="checkbox" name="user_confirm" 
					   value="1" <?php if ($this->_vars['users_settings']['user_confirm']): ?>checked="checked"<?php endif; ?>>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="hide_user_names"><?php echo l('field_hide_user_names', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="hide_user_names" type="checkbox" name="hide_user_names" 
					   value="1" <?php if ($this->_vars['users_settings']['hide_user_names']): ?>checked="checked"<?php endif; ?>>
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="age_min"><?php echo l('field_age_min', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="age_min" type="number" name="age_min" value="<?php echo $this->_vars['users_settings']['age_min']; ?>
" class="short">
			</div>
		</div>
		<div class="row">
			<div class="h">
				<label for="age_max"><?php echo l('field_age_max', 'users', '', 'text', array()); ?>:</label>
			</div>
			<div class="v">
				<input id="age_max" type="number" name="age_max" value="<?php echo $this->_vars['users_settings']['age_max']; ?>
" class="short">
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>">
		</div>
	</div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/start/menu/system-items"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<?php echo tpl_function_js(array('module' => 'users','file' => 'users-settings.js'), $this);?>
<script><?php echo '
	$(function () {
		new usersSettings();
		$(\'div.row:odd\').addClass(\'zebra\');
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>