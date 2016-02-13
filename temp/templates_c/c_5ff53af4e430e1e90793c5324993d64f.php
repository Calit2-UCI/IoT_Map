<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-06 00:42:35 Pacific Standard Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>


<?php if ($this->_vars['data']['id']): ?>
	<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'user_menu')); tpl_block_capture(array('assign' => 'user_menu'), null, $this); ob_start();  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>
		<?php if ($this->_vars['sections']): ?>
			<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['key'] => $this->_vars['item']): ?>
			<li<?php if ($this->_vars['item']['gid'] == $this->_vars['section']): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/<?php echo $this->_vars['item']['gid']; ?>
/<?php echo $this->_vars['data']['id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
			<?php endforeach; endif; ?>
		<?php endif; ?>
					<li class="<?php if ($this->_vars['section'] == 'seo'): ?>active<?php endif; ?>"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/seo/<?php echo $this->_vars['data']['id']; ?>
"><?php echo l('filter_section_seo', 'seo', '', 'text', array()); ?></a></li>
							<li><a href="<?php echo $this->_vars['site_url']; ?>
admin/media/user_media/<?php echo $this->_vars['data']['id']; ?>
"><?php echo l('filter_section_uploads', 'media', '', 'text', array()); ?></a></li>
			<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
	<?php if ($this->_vars['user_menu']): ?>
	<div class="menu-level2">
		<ul>
			<li<?php if ($this->_vars['section'] == 'personal'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/users/edit/personal/<?php echo $this->_vars['data']['id']; ?>
"><?php echo l('table_header_personal', 'users', '', 'text', array()); ?></a></div></li>
			<?php echo $this->_vars['user_menu']; ?>

		</ul>
		&nbsp;
	</div>
	<?php endif;  endif; ?>
	<?php switch($this->_vars['section']): case 'personal':  ?>
		<form action="<?php echo $this->_vars['data']['action']; ?>
" method="post" enctype="multipart/form-data" name="save_form">
		<div class="edit-form n150">
			<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_users_change', 'users', '', 'text', array());  else:  echo l('admin_header_users_add', 'users', '', 'text', array());  endif; ?></div>
			<?php if (! $this->_vars['data']['id']): ?>
			<div class="row">
				<div class="h"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="user_type" class="middle">
					<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['user_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="h"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="looking_user_type" class="middle" >
						<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['looking_user_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="row hide">
				<div class="h hide"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?>: </div>
				<div class="v hide">
					<?php echo l('from', 'users', '', 'text', array()); ?>
					<select name="age_min" class="short">
						<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
						<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_min']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
						<?php endforeach; endif; ?>
					</select>&nbsp;
					<?php echo l('to', 'users', '', 'text', array()); ?>
					<select name="age_max" class="short">
						<?php if (is_array($this->_vars['age_range']) and count((array)$this->_vars['age_range'])): foreach ((array)$this->_vars['age_range'] as $this->_vars['age']): ?>
						<option value="<?php echo $this->_vars['age']; ?>
"<?php if ($this->_vars['age'] == $this->_vars['data']['age_max']): ?> selected<?php endif; ?>><?php echo $this->_vars['age']; ?>
</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" name="email" value="<?php echo $this->_run_modifier($this->_vars['data']['email'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" name="nickname" value="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
			</div>
			<?php if ($this->_vars['data']['id']): ?>
			<div class="row">
				<div class="h"><?php echo l('field_change_password', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
			</div>
			<?php endif; ?>
			<div class="row">
				<div class="h"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="password" name="password" id="pass_field" class="middle" <?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
			</div>
			<?php if (! empty ( $this->_vars['use_repassword'] )): ?>
				<div class="row">
					<div class="h"><?php echo l('field_repassword', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="password" name="repassword" id="repass_field" class="middle" <?php if ($this->_vars['data']['id']): ?>disabled<?php endif; ?>></div>
				</div>
			<?php endif; ?>
			<div class="row">
				<div class="h"><?php echo l('field_fname', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" name="fname" value="<?php echo $this->_run_modifier($this->_vars['data']['fname'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
			</div>
			<div class="row hide">
				<div class="h"><?php echo l('field_sname', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" name="sname" value="<?php echo $this->_run_modifier($this->_vars['data']['sname'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_icon', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="file" name="user_icon">
					<?php if ($this->_vars['data']['user_logo'] || $this->_vars['data']['user_logo_moderation']): ?>
					<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb"><?php echo l('field_icon_delete', 'users', '', 'text', array()); ?></label><br>
					<?php if ($this->_vars['data']['user_logo_moderation']): ?><img src="<?php echo $this->_vars['data']['media']['user_logo_moderation']['thumbs']['middle']; ?>
"><?php else: ?><img src="<?php echo $this->_vars['data']['media']['user_logo']['thumbs']['middle']; ?>
"><?php endif; ?>
					<?php endif; ?>
				</div>
			</div>
			<div class="row hide">
				<div class="h"><?php echo l('birth_date', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type='text' value='0' name="birth_date" id="datepicker" maxlength="10" class="middle"></div>
				<?php echo tpl_function_js(array('file' => 'jquery-ui.custom.min.js'), $this);?>
				<link href='<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
				<script><?php echo '
					$(function(){
						var date_now = new Date();
						var date_min = new Date(date_now.getYear() - ';  echo $this->_vars['age_max'];  echo ', 0, 1);
						var date_max = new Date(date_now.getYear() - ';  echo $this->_vars['age_min'];  echo ', 0, 1);
						var yr =  (date_min.getFullYear()) + \':\' + (date_max.getFullYear());
						$( "#datepicker" ).datepicker({
							dateFormat :\'yy-mm-dd\',
							changeYear: true,
							changeMonth: true,
							yearRange: yr,
							defaultDate: date_max
						});
					});
				'; ?>
</script>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_region', 'users', '', 'text', array()); ?>: </div>
				<div class="v">
					<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city'],'var_country_name' => 'id_country','var_region_name' => 'id_region','var_city_name' => 'id_city'), $this);?>
				</div>
				<input type="hidden" name="lat" value="<?php echo $this->_run_modifier($this->_vars['data']['lat'], 'escape', 'plugin', 1); ?>
" id="lat">
				<input type="hidden" name="lon" value="<?php echo $this->_run_modifier($this->_vars['data']['lon'], 'escape', 'plugin', 1); ?>
" id="lon">
			</div>
							<?php echo tpl_function_block(array('name' => geomap_load_geocoder,'module' => 'geomap'), $this);?>
				
			<script><?php echo '
				$(function(){
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
			'; ?>
</script>
			
			<?php if ($this->_vars['data']['id']): ?>
			<?php if ($this->_vars['data']['confirm']): ?>
			<input type="hidden" name="confirm" value="1">
			<?php else: ?>
			<div class="row">
				<div class="h"><?php echo l('field_confirm', 'users', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" name="confirm" value="1"></div>
			</div>
			<?php endif; ?>
			<?php endif; ?>		
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['back_url']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
		<div class="clr"></div>
	<?php break; case 'seo':  ?>
				<?php if (is_array($this->_vars['seo_fields']) and count((array)$this->_vars['seo_fields'])): foreach ((array)$this->_vars['seo_fields'] as $this->_vars['key'] => $this->_vars['section']): ?>
		<form method="post" action="<?php echo $this->_run_modifier($this->_vars['data']['action'], 'escape', 'plugin', 1); ?>
" name="seo_<?php echo $this->_vars['section']['gid']; ?>
_form">
		<div class="edit-form n150">
			<div class="row header"><?php echo $this->_vars['section']['name']; ?>
</div>
			<?php if ($this->_vars['section']['tooltip']): ?>
			<div class="row">
				<div class="h">&nbsp;</div>
				<div class="v"><?php echo $this->_vars['section']['tooltip']; ?>
</div>
			</div>
			<?php endif; ?>		
			<?php if (is_array($this->_vars['section']['fields']) and count((array)$this->_vars['section']['fields'])): foreach ((array)$this->_vars['section']['fields'] as $this->_vars['field']): ?>
			<div class="row">
				<div class="h"><?php echo $this->_vars['field']['name']; ?>
: </div>
				<div class="v">
					<?php $this->assign('field_gid', $this->_vars['field']['gid']); ?>
											<?php switch($this->_vars['field']['type']): case 'checkbox':  ?>
							<input type="hidden" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
]" value="0">
							<input type="checkbox" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
]" value="1" <?php if ($this->_vars['seo_settings'][$this->_vars['field_gid']]): ?>checked<?php endif; ?>>
						<?php break; case 'text':  ?>
							<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
							<?php $this->assign('section_gid', $this->_vars['section']['gid'].'_'.$this->_vars['lang_id']); ?>
							<input type="<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>text<?php else: ?>hidden<?php endif; ?>" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
" class="long" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
">
							<?php endforeach; endif; ?>
							<a href="#" lang-editor="button" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16" alt="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>" title="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>"></a>
						<?php break; case 'textarea':  ?>
							<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['lang_item']): ?>
							<?php $this->assign('section_gid', $this->_vars['section']['gid'].'_'.$this->_vars['lang_id']); ?>
							<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang_id']): ?>
							<textarea name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" rows="5" cols="80" class="long" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
</textarea>
							<?php else: ?>
							<input type="hidden" name="<?php echo $this->_vars['section']['gid']; ?>
[<?php echo $this->_vars['field_gid']; ?>
][<?php echo $this->_vars['lang_id']; ?>
]" value="<?php echo $this->_run_modifier($this->_vars['seo_settings'][$this->_vars['section_gid']][$this->_vars['field_gid']], 'escape', 'plugin', 1); ?>
" lang-editor="value" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field_gid']; ?>
" lang-editor-lid="<?php echo $this->_vars['lang_id']; ?>
">
							<?php endif; ?>
							<?php endforeach; endif; ?>
							<a href="#" lang-editor="button" lang-editor-type="<?php echo $this->_vars['section']['gid']; ?>
_<?php echo $this->_vars['field']['gid']; ?>
" lang-field-type="textarea"><img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['img_folder']; ?>
icon-translate.png" width="16" height="16" alt="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>" title="<?php echo l('note_types_translate', 'start', '', 'button', array()); ?>"></a>					
					<?php break; endswitch; ?><br><?php echo $this->_vars['field']['tooltip']; ?>

				</div>
			</div>
			<?php endforeach; endif; ?>	
		</div>	
		<div class="btn"><div class="l"><input type="submit" name="btn_save_<?php echo $this->_vars['section']['gid']; ?>
" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['back_url']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		<?php endforeach; endif; ?>
		<?php echo tpl_function_block(array('name' => 'lang_inline_editor','module' => 'start'), $this);?>
			<?php break; default:  ?>
		<form action="<?php echo $this->_vars['data']['action']; ?>
" method="post" enctype="multipart/form-data" name="save_form">
		<div class="edit-form n150">
			<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_users_change', 'users', '', 'text', array());  else:  echo l('admin_header_users_add', 'users', '', 'text', array());  endif; ?></div>
			<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_form_fields.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['back_url']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
		</form>
		<div class="clr"></div>
<?php break; endswitch; ?>

<script><?php echo '
	$(function(){
		$("div.row:odd").addClass("zebra");
		$("#pass_change_field").click(function(){
			if(this.checked){
				$("#pass_field").removeAttr("disabled");
				$("#repass_field").removeAttr("disabled");
			}else{
				$("#pass_field").attr(\'disabled\', \'disabled\'); 
				$("#repass_field").attr(\'disabled\', \'disabled\');
			}
		});
	});
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
