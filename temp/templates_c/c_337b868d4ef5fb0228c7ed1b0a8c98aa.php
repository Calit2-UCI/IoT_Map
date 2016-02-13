<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-01-26 06:52:42 Pacific Standard Time */ ?>

<div class="content">
<!--This file have control of the "Edit Profile" for each user.-->

<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'user_form_block')); tpl_block_capture(array('assign' => 'user_form_block'), null, $this); ob_start(); ?>
	<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
	<form method="post" enctype="multipart/form-data">
		<?php if ($this->_vars['action'] == 'personal'): ?>
			
			<?php if (! $this->_vars['not_editable_fields']['fname']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>: </div>
					<div class="v" style="height:29px"><input type="text" name="fname" value="<?php echo $this->_run_modifier($this->_vars['data']['fname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			
			<?php if (! $this->_vars['not_editable_fields']['user_type']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['user_type'] || ( ! $this->_vars['data']['user_type'] && $this->_vars['key'] == 2 )): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['looking_user_type']): ?>
				<div class="r">
					<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="looking_user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['looking_user_type'] || ( ! $this->_vars['data']['looking_user_type'] && $this->_vars['key'] == 2 )): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['age_min'] && ! $this->_vars['not_editable_fields']['age_max']): ?>
				<div class="r hide">
					<div class="f hide"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?>: </div>
					<div class="v hide">
						<?php if (! $this->_vars['not_editable_fields']['age_min']): ?>
							<?php echo l('from', 'users', '', 'text', array()); ?>&nbsp;
							<select name="age_min" class="short">
								<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
									<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_min']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
								<?php endforeach; endif; ?>
							</select>&nbsp;
						<?php endif; ?>
						<?php if (! $this->_vars['not_editable_fields']['age_max']): ?>
							<?php echo l('to', 'users', '', 'text', array()); ?>&nbsp;
							<select name="age_max" class="short">
								<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
									<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_max']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
								<?php endforeach; endif; ?>
							</select>
						<?php endif; ?>
					</div>
				</div>
			<?php endif; ?>
			<?php if (! $this->_vars['not_editable_fields']['nickname']): ?>  <!--nickname is the user name-->
				<div class="r hide">
					<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="nickname" value="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>

			<?php if (! $this->_vars['not_editable_fields']['sname']): ?>       <!--Leave this in Hide-->
				<div class="r hide">
					<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="sname" value="<?php echo $this->_run_modifier($this->_vars['data']['sname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			
			<?php if (true): ?>
				<div class="r">
					<div class="f">Website: </div>
					<!--div class="v">Empty</div-->
					<div class="v"><input type="text" name="website" value="<?php echo $this->_run_modifier($this->_vars['data']['website'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			
			<div class="r">
				<div class="f"><?php echo l('field_icon', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="file" name="user_icon" accept="image/*;capture=camera">
					<?php if ($this->_vars['data']['user_logo'] || $this->_vars['data']['user_logo_moderation']): ?>
						<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb"><?php echo l('field_icon_delete', 'users', '', 'text', array()); ?></label><br>
						<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['data'])));
 ?>
						<?php if ($this->_vars['data']['user_logo_moderation']): ?>
						<img src="<?php echo $this->_vars['data']['media']['user_logo_moderation']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
">
						<?php else: ?>
						<img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
">
						<?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<?php if (! $this->_vars['not_editable_fields']['birth_date']): ?>
				<div class="r hide">
					<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type='text' value='<?php echo $this->_vars['data']['birth_date']; ?>
' name="birth_date"></div>
				</div>
			<?php endif; ?>
			
					
			
			<?php if (true): ?>
				<div class="r">
					<div class="f">Address (street): </div>
					<!--div class="v">Empty</div-->
					<div class="v"><input type="text" name="address" value="<?php echo $this->_run_modifier($this->_vars['data']['address'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
			<?php endif; ?>
			
			<div class="r">
				<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city'],'var_country_name' => 'id_country','var_region_name' => 'id_region','var_city_name' => 'id_city'), $this);?>
				</div>
				<input type="hidden" name="lat" value="<?php echo $this->_run_modifier($this->_vars['data']['lat'], 'escape', 'plugin', 1); ?>
" id="lat">
				<input type="hidden" name="lon" value="<?php echo $this->_run_modifier($this->_vars['data']['lon'], 'escape', 'plugin', 1); ?>
" id="lon">
			</div>
		<?php else: ?>
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_form_fields.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
		<?php endif; ?>

		<div class="r">
			<div class="f">&nbsp;</div>
			<div class="v">
				<input type="submit" value="<?php if ($this->_vars['edit_mode']):  echo l('btn_save', 'start', '', 'button', array());  else:  echo l('btn_register', 'start', '', 'button', array());  endif; ?>" name="btn_register">
				<?php 
$this->assign('profile_section_name', l('filter_section_view', 'users', '', 'text', array()));
 ?>&nbsp;
				<a href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile','section-code' => 'view','section-name' => $this->_vars['profile_section_name']), $this);?>" class="btn-link"><i class="icon icon-arrow-left icon-big edge hover"></i><?php echo l('btn_back', 'start', '', 'text', array()); ?></a>
			</div>
		</div>
	</form>
	<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
			<?php echo tpl_function_block(array('name' => geomap_load_geocoder,'module' => 'geomap'), $this);?>	
		<script type='text/javascript'><?php echo '
		$(function(){
			var now = new Date();
			var yr =  (new Date(now.getYear() - ';  echo $this->_vars['age_max'];  echo ', 0, 1).getFullYear()) + \':\' + (new Date(now.getYear() - ';  echo $this->_vars['age_min'];  echo ', 0, 1).getFullYear());
			$( "#datepicker" ).datepicker({
				dateFormat :\'yy-mm-dd\',
				changeYear: true,
				changeMonth: true,
				yearRange: yr
			});
	
			loadScripts(
				["';  echo tpl_function_js(array('module' => 'users','file' => 'users-map.js','return' => 'path'), $this); echo '"],
				function(){
					users_map = new usersMap({
						siteUrl: site_url,
					});
				},
				[\'users_map\'],
				{async: true}
			);

		});
	</script>'; ?>

<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>

<div class="view small">
	<div class="image">
		<div id="user_photo" class="pos-rel dimp100 pointer">
			<?php 
$this->assign('text_user_logo', l('text_user_logo', 'users', '', 'button', array_merge(array(),$this->_vars['data'])));
 ?>
			<?php if ($this->_vars['data']['user_logo_moderation']): ?>
				<img src="<?php echo $this->_vars['data']['media']['user_logo_moderation']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" />
			<?php else: ?>
				<img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
" alt="<?php echo $this->_vars['text_user_logo']; ?>
" title="<?php echo $this->_vars['text_user_logo']; ?>
" />
			<?php endif; ?>
		</div>
	</div>
	<div class="info">
		<div class="body">
			<h1>
				<span style="font-size:30px;line-height:28px;"><?php echo $this->_vars['data']['output_name']; ?>
</span>
				<span data-role="online_status" class="fright online-status"><s class="<?php echo $this->_vars['data']['statuses']['online_status_text']; ?>
"><?php echo $this->_vars['data']['statuses']['online_status_lang']; ?>
</s></span>
			</h1>
			<div>
				<!--div class="fright"><?php echo l('views', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['views_count']; ?>
</div-->
				<!--<?php echo l('field_age', 'users', '', 'text', array()); ?>: <?php echo $this->_vars['data']['age']; ?>
-->
				
				<?php if ($this->_vars['data']['website']): ?><a href=<?php echo $this->_vars['data']['website']; ?>
 target="_blank" class="target_blank">Website</a><?php endif; ?>  
				
				<?php if ($this->_vars['data']['location']): ?><i class="delim-alone"></i><span class=""><?php echo $this->_vars['data']['location']; ?>
</span><?php endif; ?>
			</div>
		</div>
		<div class="actions noPrint">
			<?php 
$this->assign('personal_section_name', l('filter_section_personal', 'users', '', 'text', array()));
 ?>
			<a class="link-r-margin" title="<?php echo l('edit_my_profile', 'start', '', 'text', array()); ?>" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile','section-code' => 'personal','section-name' => $this->_vars['personal_section_name']), $this);?>"><i class="icon-pencil icon-big edge hover"></i></a>
			<?php if ($this->_vars['data']['services_status']['highlight_in_search']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['highlight_in_search']['name'], 'escape', 'plugin', 1); ?>
" onclick="highlight_in_search_available_view.check_available();"><i class="icon-sun icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<?php if ($this->_vars['data']['services_status']['up_in_search']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['up_in_search']['name'], 'escape', 'plugin', 1); ?>
" onclick="up_in_search_available_view.check_available();"><i class="icon-level-up icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<?php if ($this->_vars['data']['services_status']['hide_on_site']['status']): ?>
				<span class="link-r-margin" title="<?php echo $this->_run_modifier($this->_vars['data']['services_status']['hide_on_site']['name'], 'escape', 'plugin', 1); ?>
" onclick="hide_on_site_available_view.check_available();"><i class="icon-eye-close icon-big edge hover zoom20"></i></span>
			<?php endif; ?>
			<script type='text/javascript'><?php echo '
				$(function(){
					loadScripts(
						["';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '", "';  echo tpl_function_js(array('file' => 'users-avatar.js','module' => 'users','return' => 'path'), $this); echo '"],
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_highlight_in_search/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_highlight_in_search/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');}
							});
							up_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_up_in_search/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_up_in_search/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');}
							});
							hide_on_site_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: \'users/ajax_available_hide_on_site/\',
								buyAbilityAjaxUrl: \'users/ajax_activate_hide_on_site/\',
								buyAbilityFormId: \'ability_form\',
								buyAbilitySubmitId: \'ability_form_submit\',
								success_request: function(message) {error_object.show_error_block(message, \'success\'); locationHref(\'\');},
								fail_request: function(message) {error_object.show_error_block(message, \'error\');}
							});

							user_avatar = new avatar({site_url: site_url});
						},
						[\'highlight_in_search_available_view\', \'up_in_search_available_view\', \'hide_on_site_available_view\', \'user_avatar\'],
						{async: false}
					);
				});
			</script>'; ?>

		</div>
	</div>
</div>

<?php if ($this->_vars['data']['approved'] && $this->_vars['data']['confirm'] && ! $this->_vars['data']['activity'] && $this->_vars['data']['available_activation']['status']): ?>
	<div class="bg-highlight_bg mtb10 p10">
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
							fail_request: function(message) {error_object.show_error_block(message, \'error\');}
						});
					},
					\'activate_available_view\',
					{async: false}
				);
			});
		</script>'; ?>

		<div>
			<a href="<?php echo $this->_vars['site_url']; ?>
users/account/services"><button type="button" class="inline-btn"><?php echo l('link_activate_profile', 'users', '', 'text', array()); ?></button></a>
			<!--input type="button" class="inline-btn" onclick="activate_available_view.check_available();" value="<?php echo l('link_activate_profile', 'users', '', 'text', array()); ?>" /-->
			<span class="ml10"><?php echo l('text_activate_profile', 'users', '', 'text', array()); ?></span>
		</div>
	</div>
<?php endif; ?>

<div class="edit_block" id="profile_tab_sections">
	<?php $this->assign('action', 'view'); ?>
	<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "profile_menu.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div class="users-user_form">
		<?php echo $this->_vars['user_form_block']; ?>

	</div>
</div>
</div>