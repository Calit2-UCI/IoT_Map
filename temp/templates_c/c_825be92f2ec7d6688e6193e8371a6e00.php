<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:20:16 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="menu-level2" id="menu-bookmark-level2">
	<ul>
		<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['lang_id'] => $this->_vars['item']): ?>
		<li<?php if ($this->_vars['lang_id'] == $this->_vars['current_lang']): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['lang_id']; ?>
"><?php echo $this->_vars['item']['name']; ?>
</a></div></li>
		<?php endforeach; endif; ?>
	</ul>
	&nbsp;
</div>
<?php echo tpl_function_js(array('module' => menu,'file' => 'menu-bookmark.js'), $this);?>
<script><?php echo '
	$(function(){
		new menuBookmark({\'bmID\': \'menu-bookmark-level2\', bmElement: \'li\', padding: 0,});
	});
'; ?>
</script>


<div class="actions">&nbsp;</div>

<form method="post" action="" name="save_form">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_promo_block_main', 'content', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_promo_type', 'content', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="content_type" id="content_type" class="middle">
				<option value="t"<?php if ($this->_vars['promo_data']['content_type'] == 't'): ?> selected<?php endif; ?>><?php echo l('field_promo_type_text', 'content', '', 'text', array()); ?></option>	
				<option value="f"<?php if ($this->_vars['promo_data']['content_type'] == 'f'): ?> selected<?php endif; ?>><?php echo l('field_promo_type_flash', 'content', '', 'text', array()); ?></option>	
				
				</select>
			</div>
		</div>
		<div id="content_block_t" class="content_block_box ">
			<div class="row zebra">
				<div class="h"><?php echo l('field_block_width', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="t[block_width_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
						<option value="%"<?php if ($this->_vars['promo_data']['block_width_unit'] == '%'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_percent', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="t[block_width]" value="<?php echo $this->_vars['promo_data']['block_width']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_block_height', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="t[block_height_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="t[block_height]" value="<?php echo $this->_vars['promo_data']['block_height']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
		</div>
		<div id="content_block_s" class="content_block_box <?php if ($this->_vars['promo_data']['content_type'] != 's'): ?>hide<?php endif; ?>">
			<div class="row zebra">
				<div class="h"><?php echo l('field_block_width', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="s[block_width_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
						<option value="%"<?php if ($this->_vars['promo_data']['block_width_unit'] == '%'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_percent', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="block_width" value="<?php echo $this->_vars['promo_data']['block_width']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_block_height', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="s[block_height_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="s[block_height]" value="<?php echo $this->_vars['promo_data']['block_height']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
		</div>
		<div id="content_block_f" class="content_block_box <?php if ($this->_vars['promo_data']['content_type'] != 'f'): ?>hide<?php endif; ?>">
			<div class="row zebra">
				<div class="h"><?php echo l('field_block_width', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="f[block_width_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
						<option value="%"<?php if ($this->_vars['promo_data']['block_width_unit'] == '%'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_percent', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="f[block_width]" value="<?php echo $this->_vars['promo_data']['block_width']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_block_height', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="f[block_height_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="f[block_height]" value="<?php echo $this->_vars['promo_data']['block_height']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
		</div>
		<div id="content_block_v" class="content_block_box <?php if ($this->_vars['promo_data']['content_type'] != 'v'): ?>hide<?php endif; ?>">
			<div class="row zebra">
				<div class="h"><?php echo l('field_block_width', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="v[block_width_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_width_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
						<option value="%"<?php if ($this->_vars['promo_data']['block_width_unit'] == '%'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_percent', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="v[block_width]" value="<?php echo $this->_vars['promo_data']['block_width']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_width_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_block_height', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="v[block_height_unit]" class="units">
						<option value="auto"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_auto', 'content', '', 'text', array()); ?></option>	
						<option value="px"<?php if ($this->_vars['promo_data']['block_height_unit'] == 'px'): ?> selected<?php endif; ?>><?php echo l('field_block_unit_px', 'content', '', 'text', array()); ?></option>	
					</select>
					<input type="text" name="v[block_height]" value="<?php echo $this->_vars['promo_data']['block_height']; ?>
" class="short unit_val" <?php if ($this->_vars['promo_data']['block_height_unit'] == 'auto'): ?> disabled<?php endif; ?>>
				</div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save_settings" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<div class="clr"></div>
</form>
<script><?php echo '
	$(\'#content_type\').bind(\'change\', function(){
		$(\'.content_block_box\').hide();
		$(\'#content_block_\'+$(this).val()).show();
	});
'; ?>
</script>

<div class="menu-level3">
	<ul>
		<li<?php if ($this->_vars['content_type'] == 't'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/t"><?php echo l('field_promo_type_text', 'content', '', 'text', array()); ?></a></div></li>
		<li<?php if ($this->_vars['content_type'] == 'f'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/f"><?php echo l('field_promo_type_flash', 'content', '', 'text', array()); ?></a></div></li>
		<li<?php if ($this->_vars['content_type'] == 'v'): ?> class="active"<?php endif; ?>><div class="l"><a href="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/v"><?php echo l('field_promo_type_video', 'content', '', 'text', array()); ?></a></div></li>	</ul>
	&nbsp;
</div>

	<?php switch($this->_vars['content_type']): case 't':  ?>
		<form method="post" action="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/<?php echo $this->_vars['content_type']; ?>
" name="save_form"  enctype="multipart/form-data">
			<div class="edit-form n150">
				<div class="row header">&nbsp;</div>
				<div class="row">
					<div class="h"><?php echo l('field_promo_text', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<?php echo $this->_vars['promo_data']['promo_text_fck']; ?>

					</div>
				</div>
				<div class="row zebra">
					<div class="h"><?php echo l('field_block_img_align_hor', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<select name="block_align_hor">
							<option value="center"<?php if ($this->_vars['promo_data']['block_align_hor'] == 'center'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_center', 'content', '', 'text', array()); ?></option>
							<option value="left"<?php if ($this->_vars['promo_data']['block_align_hor'] == 'left'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_left', 'content', '', 'text', array()); ?></option>
							<option value="right"<?php if ($this->_vars['promo_data']['block_align_hor'] == 'right'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_right', 'content', '', 'text', array()); ?></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_block_img_align_ver', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<select name="block_align_ver">
							<option value="center"<?php if ($this->_vars['promo_data']['block_align_ver'] == 'center'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_center', 'content', '', 'text', array()); ?></option>
							<option value="top"<?php if ($this->_vars['promo_data']['block_align_ver'] == 'top'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_top', 'content', '', 'text', array()); ?></option>
							<option value="bottom"<?php if ($this->_vars['promo_data']['block_align_ver'] == 'bottom'): ?> selected<?php endif; ?>><?php echo l('field_block_img_align_bottom', 'content', '', 'text', array()); ?></option>
						</select>
					</div>
				</div>
				<div class="row zebra">
					<div class="h"><?php echo l('field_block_img_repeating', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<select name="block_image_repeat">
						<option value="repeat"<?php if ($this->_vars['promo_data']['block_image_repeat'] == 'repeat'): ?> selected<?php endif; ?>><?php echo l('field_block_img_repeat', 'content', '', 'text', array()); ?></option>
						<option value="no-repeat"<?php if ($this->_vars['promo_data']['block_image_repeat'] == 'no-repeat'): ?> selected<?php endif; ?>><?php echo l('field_block_img_no_repeat', 'content', '', 'text', array()); ?></option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h"><?php echo l('field_promo_img', 'content', '', 'text', array()); ?>: </div>
					<div class="v">
						<input type="file" name="promo_image">
						<?php if ($this->_vars['promo_data']['promo_image']): ?><br><img src="<?php echo $this->_vars['promo_data']['media']['promo_image']['file_url']; ?>
" width="500"><?php endif; ?>
					</div>
				</div>
				<?php if ($this->_vars['promo_data']['promo_image']): ?>
				<div class="row zebra">
					<div class="h"><?php echo l('field_promo_image_delete', 'content', '', 'text', array()); ?>: </div>
					<div class="v"><input type="checkbox" name="promo_image_delete" value="1"></div>
				</div>
				<?php endif; ?>
			</div>
			<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
			<div class="clr"></div>
		</form>
	<?php break; case 'f':  ?>
	<form method="post" action="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/<?php echo $this->_vars['content_type']; ?>
" name="save_form"  enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">&nbsp;</div>
			<div class="row">
				<div class="h"><?php echo l('field_promo_flash', 'content', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="file" name="promo_flash"><br>
					<?php if ($this->_vars['promo_data']['promo_flash']): ?>
					<i><?php echo l('field_promo_flash_uploaded', 'content', '', 'text', array()); ?></i>
					<object width="100%" height="100%" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
						<param value="Always" name="allowScriptAccess">
						<param value="<?php echo $this->_vars['promo_data']['media']['promo_flash']['file_url']; ?>
" name="movie">
						<param value="false" name="menu">
						<param value="high" name="quality">
						<param value="opaque" name="wmode">
						<param value="" name="flashvars">
						<embed width="100%" height="100%" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" swliveconnect="FALSE" menu="false" wmode="opaque" allowscriptaccess="Always" quality="high" flashvars="" src="<?php echo $this->_vars['promo_data']['media']['promo_flash']['file_url']; ?>
"> 
					</object>
					<?php endif; ?>
				</div>
			</div>
			<?php if ($this->_vars['promo_data']['promo_flash']): ?>
			<div class="row zebra">
				<div class="h"><?php echo l('field_promo_flash_delete', 'content', '', 'text', array()); ?>: </div>
				<div class="v"><input type="checkbox" name="promo_flash_delete" value="1"></div>
			</div>
			<?php endif; ?>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<div class="clr"></div>
	</form>
	<?php break; case 'v':  ?>
	<form method="post" action="<?php echo $this->_vars['site_url']; ?>
admin/content/promo/<?php echo $this->_vars['current_lang']; ?>
/<?php echo $this->_vars['content_type']; ?>
" name="save_form"  enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">&nbsp;</div>
			<div class="row">
				<div class="h"><?php echo l('field_promo_video', 'content', '', 'text', array()); ?>:&nbsp;* </div>
				<div class="v">
					<p>
						<?php echo l('max_video_header', 'content', '', 'text', array()); ?>: <b>1</b><br>
						<?php if ($this->_vars['video_settings']['max_size_str']):  echo l('max_video_size_header', 'content', '', 'text', array()); ?>: <b><?php echo $this->_vars['video_settings']['max_size_str']; ?>
</b><br><?php endif; ?>
						<?php if ($this->_vars['video_settings']['file_formats']):  echo l('text_accepted_file_types', 'content', '', 'text', array()); ?>: <b><?php echo $this->_run_modifier(', ', 'implode', 'PHP', 1, $this->_vars['video_settings']['file_formats']); ?>
</b><br><?php endif; ?>
					</p>
					<?php if ($this->_vars['promo_data']['promo_video']): ?>
						<?php if ($this->_vars['promo_data']['promo_video_data']['data']['upload_type'] != 'embed'): ?>
							<br><br><?php echo l('field_video_status', 'content', '', 'text', array()); ?>:
							<?php if ($this->_vars['promo_data']['promo_video_data']['status'] == 'end' && $this->_vars['promo_data']['promo_video_data']['errors']): ?><font color="red"><?php if (is_array($this->_vars['promo_data']['promo_video_data']['errors']) and count((array)$this->_vars['promo_data']['promo_video_data']['errors'])): foreach ((array)$this->_vars['promo_data']['promo_video_data']['errors'] as $this->_vars['item']):  echo $this->_vars['item']; ?>
<br><?php endforeach; endif; ?></font>
							<?php elseif ($this->_vars['promo_data']['promo_video_data']['status'] == 'end'): ?><font color="green"><?php echo l('field_video_status_end', 'content', '', 'text', array()); ?></font><br>
							<?php elseif ($this->_vars['promo_data']['promo_video_data']['status'] == 'images'): ?><font color="yellow"><?php echo l('field_video_status_images', 'content', '', 'text', array()); ?></font><br>
							<?php elseif ($this->_vars['promo_data']['promo_video_data']['status'] == 'waiting'): ?><font color="yellow"><?php echo l('field_video_status_waiting', 'content', '', 'text', array()); ?></font><br>
							<?php elseif ($this->_vars['promo_data']['promo_video_data']['status'] == 'start'): ?><font color="yellow"><?php echo l('field_video_status_start', 'content', '', 'text', array()); ?></font><br>
							<?php endif; ?>
						<?php endif; ?>
						<?php if ($this->_vars['promo_data']['promo_video_content']['embed']): ?><br><?php echo $this->_vars['promo_data']['promo_video_content']['embed'];  endif; ?>
						<br><input type="checkbox" name="promo_video_delete" value="1" id="uvchb"><label for="uvchb"><?php echo l('field_promo_video_delete', 'content', '', 'text', array()); ?></label>
					<?php else: ?>
						<input type="file" name="promo_video"><br><br>
						<?php echo l('text_video_embed', 'content', '', 'text', array()); ?><br>
						<textarea name="promo_video_embed" rows="10" cols="80"><?php if ($this->_vars['promo_data']['promo_video'] && $this->_vars['promo_data']['promo_video_data']['data']['upload_type'] == 'embed'):  echo $this->_run_modifier($this->_vars['promo_data']['promo_video_content']['embed'], 'escape', 'plugin', 1);  endif; ?></textarea>
					<?php endif; ?>
				</div>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<div class="clr"></div>
	</form>
<?php break; endswitch; ?>
<script type="text/javascript"><?php echo '
$(function(){
	$(\'.units\').bind(\'change\', function(){
		if($(this).val() == \'auto\'){
			$(this).parent().find(\'input.unit_val\').attr(\'disabled\', \'disabled\');
		}else{
			$(this).parent().find(\'input.unit_val\').removeAttr(\'disabled\');
		}	
	});
});
'; ?>
</script>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
