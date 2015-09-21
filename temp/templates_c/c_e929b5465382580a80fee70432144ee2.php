<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.date_format.php');
$this->register_modifier("date_format", "tpl_modifier_date_format"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-15 02:09:35 Pacific Daylight Time */ ?>

<h2 class="line top bottom linked">
	<?php echo l('table_header_activity', 'users', '', 'text', array()); ?>
</h2>
<div class="view-section">
	<?php if (! $this->_vars['data']['activity'] || ! $this->_vars['data']['approved'] || ! $this->_vars['data']['confirm']): ?>
		<div><?php echo l("text_inactive_in_search", 'users', '', 'text', array()); ?></div>
		<?php if ($this->_vars['data']['approved'] && $this->_vars['data']['confirm']): ?>
			<?php if ($this->_vars['data']['available_activation']['status']): ?>
				<script type='text/javascript'><?php echo '
					$(function(){
						loadScripts(
							"';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", 
							function(){
								activate_available_view = new available_view({
									siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
									checkAvailableAjaxUrl: \'users/ajax_available_user_activate_in_search/\',
									buyAbilityAjaxUrl: \'users/ajax_activate_user_activate_in_search/\',
									buyAbilityFormId: \'ability_form\',
									buyAbilitySubmitId: \'ability_form_submit\',
									success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'';  echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this); echo '\')},
									fail_request: function(message) {error_object.show_error_block(message, \'error\');},
								});
							},
							\'activate_available_view\',
							{async: false}
						);
					});
				</script>'; ?>

				<div class="pt10"><input type="button" onclick="activate_available_view.check_available();" value="<?php echo l('btn_activate', 'start', '', 'text', array()); ?>" name="btn_activate_save" id="btn_activate_save"></div>
			<?php else: ?>
				<div><?php echo l("text_need_for_activation", 'users', '', 'text', array()); ?></div>
				<ul>
					<?php if (is_array($this->_vars['data']['available_activation']['fields']) and count((array)$this->_vars['data']['available_activation']['fields'])): foreach ((array)$this->_vars['data']['available_activation']['fields'] as $this->_vars['field']): ?>
						<li><?php if ($this->_vars['field'] == 'user_logo_moderation'):  echo l("wait_image_approve", 'users', '', 'text', array());  elseif ($this->_vars['field'] == 'user_logo'):  echo l('upload_photo', 'users', '', 'text', array());  else:  echo l("fill_field", 'users', '', 'text', array()); ?>: <?php echo l("field_".$this->_vars['field'], 'users', '', 'text', array());  endif; ?></li>
					<?php endforeach; endif; ?>
				</ul>
			<?php endif; ?>
		<?php else: ?>
			<div><?php echo l('profile_not_confirm', 'users', '', 'text', array()); ?></div>
		<?php endif; ?>
	<?php else: ?>
		<div><?php echo l("text_active_in_search", 'users', '', 'text', array()); ?></div>
		<?php if ($this->_vars['data']['activated_end_date'] && $this->_vars['data']['activated_end_date'] != '0000-00-00 00:00:00'): ?>
			<div><?php echo l("text_active_until", 'users', '', 'text', array()); ?>: <?php echo $this->_run_modifier($this->_vars['data']['activated_end_date'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_time_format']); ?>
</div>
		<?php endif; ?>
	<?php endif; ?>
        
	<?php if ($this->_vars['data']['is_hide_on_site'] && $this->_vars['data']['services_status']['hide_on_site']['service']['status']): ?>
		<div class="pt10"><?php echo l('profile_hide_on_site', 'users', '', 'text', array()); ?></div>
	<?php endif; ?>
</div>
<h2 class="line top bottom linked">
	<?php echo l('table_header_personal', 'users', '', 'text', array()); ?>
	<?php 
$this->assign('personal_section_name', l('filter_section_personal', 'users', '', 'text', array()));
 ?>
	<a class="fright" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile','section-code' => 'personal','section-name' => $this->_vars['personal_section_name']), $this);?>"><i class="fa-pencil icon-big edge hover"></i></a>
</h2>
<div class="view-section">
	<?php 
$this->assign('no_info_str', l('no_information', 'users', '', 'text', array()));
 ?>
	<div class="r">
		<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['user_type_str']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['looking_user_type_str']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['looking_user_type_str']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_min']): ?>
	<div class="r hide">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('from', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_min']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_max']): ?>
	<div class="r hide">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('to', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_max']; ?>
</div>
	</div>
	<?php endif; ?>
	<div class="r">
		<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['nickname']; ?>
</div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['fname']; ?>
</div>
	</div>
	<div class="r">
		<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['sname']; ?>
</div>
	</div>
	<div class="r hide">
		<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_run_modifier($this->_vars['data']['birth_date'], 'date_format', 'plugin', 1, $this->_vars['page_data']['date_format'], '', $this->_vars['no_info_str']); ?>
</div>
	</div>
	<?php if ($this->_vars['data']['location']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['location']; ?>
</div>
	</div>
	<?php endif; ?>
</div>
<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['item']): ?>
	<h2 class="line top bottom linked">
		<?php echo $this->_vars['item']['name']; ?>

		<a class="fright" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile','section-code' => $this->_vars['item']['gid'],'section-name' => $this->_vars['item']['name']), $this);?>"><i class="fa-pencil icon-big edge hover"></i></a>
	</h2>
	<div class="view-section">
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_view_fields.tpl", array('fields_data' => $this->_vars['item']['fields'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	</div>
<?php endforeach; endif; ?>
