<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-14 19:23:39 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
<div class="lef">
	<div class="edit-form n100">
		<div class="row header"><?php if ($this->_vars['set']['id']):  echo l('admin_header_set_change', 'themes', '', 'text', array());  else:  echo l('admin_header_set_add', 'themes', '', 'text', array());  endif; ?></div>
		<div class="row striped">
			<div class="h"><?php echo l('field_name', 'themes', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['set']['set_name']; ?>
" name="set_name" class="middle"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_set_gid', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="30" name="set_gid" id="set_gid" value="<?php echo $this->_vars['set']['set_gid']; ?>
" class="middle"></div>
		</div>
		<input type="hidden" name="scheme_type" value="light" />
		

		<h2><?php echo l('header_text_sizes_header', 'themes', '', 'text', array()); ?></h2>

		<div class="row striped">
			<div class="h"><?php echo l('field_font_family', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="30" name="font_family" id="font_family" value="<?php echo $this->_run_modifier($this->_vars['set']['color_settings']['font_family'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
		</div>
	</div>

	<div class="edit-form">
		<div class="row striped">
			<div class="h"><?php echo l('field_main_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="main_font_size" id="main_font_size" value="<?php echo $this->_vars['set']['color_settings']['main_font_size']; ?>
"> px</div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_input_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="input_font_size" id="input_font_size" value="<?php echo $this->_vars['set']['color_settings']['input_font_size']; ?>
"> px</div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_h1_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="h1_font_size" id="h1_font_size" value="<?php echo $this->_vars['set']['color_settings']['h1_font_size']; ?>
"> px</div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_h2_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="h2_font_size" id="h2_font_size" value="<?php echo $this->_vars['set']['color_settings']['h2_font_size']; ?>
"> px</div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_small_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="small_font_size" id="small_font_size" value="<?php echo $this->_vars['set']['color_settings']['small_font_size']; ?>
"> px</div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_search_btn_font_size', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" class="short" size="3" name="search_btn_font_size" id="search_btn_font_size" value="<?php echo $this->_vars['set']['color_settings']['search_btn_font_size']; ?>
"> px</div>
		</div>

		<h2><?php echo l('header_index_background', 'themes', '', 'text', array()); ?></h2>
		
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="file" name="index_bg_image" id="index_bg_image" value="<?php echo $this->_vars['set']['color_settings']['index_bg_image']; ?>
" size="2" /></div>
			<input type="hidden" name="index_bg_image_ver" value="<?php echo $this->_vars['set']['color_settings']['index_bg_image_ver']; ?>
" />
			<?php if ($this->_vars['set']['color_settings']['index_bg_image']): ?>
				<div class="v"><label><input type="checkbox" name="index_bg_image_delete" /><?php echo l('field_delete_bg_image', 'themes', '', 'text', array()); ?></label></div>
				<div class="p-top2"><img src="<?php echo $this->_vars['bg_img_url']; ?>
" class="wp100"></div>
			<?php endif; ?>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="short_long" name="index_bg_image_bg" id="index_bg_image_bg" value="<?php echo $this->_vars['set']['color_settings']['index_bg_image_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_scroll', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_scroll" value="1"<?php if ($this->_vars['set']['color_settings']['index_bg_image_scroll']): ?> checked<?php endif; ?>></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_adjust_width', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_adjust_width" value="1"<?php if ($this->_vars['set']['color_settings']['index_bg_image_adjust_width']): ?> checked<?php endif; ?>></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_adjust_height', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_adjust_height" value="1"<?php if ($this->_vars['set']['color_settings']['index_bg_image_adjust_height']): ?> checked<?php endif; ?>></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_repeat_x', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_repeat_x" value="1"<?php if ($this->_vars['set']['color_settings']['index_bg_image_repeat_x']): ?> checked<?php endif; ?>></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_bg_image_repeat_y', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_repeat_y" value="1"<?php if ($this->_vars['set']['color_settings']['index_bg_image_repeat_y']): ?> checked<?php endif; ?>></div>
		</div>
		
		
		<h2><?php echo l('header_bright_colors_header', 'themes', '', 'text', array()); ?></h2>

		<div class="row striped">
			<div class="h"><?php echo l('field_main_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="main_bg" id="main_bg" value="<?php echo $this->_vars['set']['color_settings']['main_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_html_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="html_bg" id="html_bg" value="<?php echo $this->_vars['set']['color_settings']['html_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_header_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="header_bg" id="header_bg" value="<?php echo $this->_vars['set']['color_settings']['header_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_footer_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="footer_bg" id="footer_bg" value="<?php echo $this->_vars['set']['color_settings']['footer_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_menu_hover_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="menu_hover_bg" id="menu_hover_bg" value="<?php echo $this->_vars['set']['color_settings']['menu_hover_bg']; ?>
"></div>
		</div>
		<!--div class="row striped">
			<div class="h"><?php echo l('field_hover_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick" name="hover_bg" id="hover_bg" value="<?php echo $this->_vars['set']['color_settings']['hover_bg']; ?>
"></div>
		</div-->
		<div class="row striped">
			<div class="h"><?php echo l('field_popup_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="popup_bg" id="popup_bg" value="<?php echo $this->_vars['set']['color_settings']['popup_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_highlight_bg', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="highlight_bg" id="highlight_bg" value="<?php echo $this->_vars['set']['color_settings']['highlight_bg']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_input_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="input_color" id="input_color" value="<?php echo $this->_vars['set']['color_settings']['input_color']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_input_bg_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="input_bg_color" id="input_bg_color" value="<?php echo $this->_vars['set']['color_settings']['input_bg_color']; ?>
"></div>
		</div>
		<!--div class="row striped">
			<div class="h"><?php echo l('field_status_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick" name="status_color" id="status_color" value="<?php echo $this->_vars['set']['color_settings']['status_color']; ?>
"></div>
		</div-->
		<div class="row striped">
			<div class="h"><?php echo l('field_link_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="link_color" id="link_color" value="<?php echo $this->_vars['set']['color_settings']['link_color']; ?>
"></div>
		</div>

		<h2><?php echo l('header_dull_colors_header', 'themes', '', 'text', array()); ?></h2>
		<div class="row striped">
			<div class="h"><?php echo l('field_font_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="font_color" id="font_color" value="<?php echo $this->_vars['set']['color_settings']['font_color']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_header_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="header_color" id="header_color" value="<?php echo $this->_vars['set']['color_settings']['header_color']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_descr_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="descr_color" id="descr_color" value="<?php echo $this->_vars['set']['color_settings']['descr_color']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_contrast_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="contrast_color" id="contrast_color" value="<?php echo $this->_vars['set']['color_settings']['contrast_color']; ?>
"></div>
		</div>
		<div class="row striped">
			<div class="h"><?php echo l('field_delimiter_color', 'themes', '', 'text', array()); ?>:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="delimiter_color" id="delimiter_color" value="<?php echo $this->_vars['set']['color_settings']['delimiter_color']; ?>
"></div>
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/themes/sets/<?php echo $this->_vars['id_theme']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>


</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header"><?php echo l('admin_header_generate', 'themes', '', 'text', array()); ?></div>
			<script type="text/javascript">
				var schemeSettings = <?php echo $this->_vars['scheme_json']; ?>
;
				var main_bg = '<?php echo $this->_vars['set']['color_settings']['main_bg']; ?>
';
				var is_new = <?php if ($this->_vars['set']['id']): ?>false<?php else: ?>true<?php endif; ?>;
			</script>

			<?php echo tpl_function_js(array('file' => 'colorsets/jscolor/jscolor.js'), $this);?>
			<?php echo tpl_function_js(array('file' => 'colorsets/color_colorblind.js'), $this);?>
			<?php echo tpl_function_js(array('file' => 'colorsets/color_scheme.js'), $this);?>

			<link type="text/css" rel="stylesheet" href="<?php echo $this->_vars['site_root'];  echo $this->_vars['js_folder']; ?>
colorsets/color_scheme.css" />

			<div class="row striped">
				<div id="color_enter">
					<b><?php echo l('select_color_header', 'themes', '', 'text', array()); ?>:</b><br><br>
					<div id="image">
						<div id="wheelarea"></div>
						<div id="pointer0"></div>
						<div id="pointer1"></div>
						<div id="pointer2"></div>
						<div id="pointer3"></div>
					</div>
					<div id="maincolorhue"></div>

					<div id="manual_color_block">
						<b><?php echo l('manual_color_header', 'themes', '', 'text', array()); ?>:</b> <input type="text" size="3" name="manual_h" id="manual_h" value="" class="short">°
					</div>
				</div>
			</div>

			<div id="coltable">
				<div id="all_examples"></div>
				<div class="row striped">
					<div class="h"><?php echo l('field_scheme_name', 'themes', '', 'text', array()); ?>:</div>
					<div class="v">
					<select id="sample_scheme_type" class="middle_short">
					<option value="light"><?php echo l('field_scheme_name_light', 'themes', '', 'text', array()); ?></option>
					<option value="dark"><?php echo l('field_scheme_name_dark', 'themes', '', 'text', array()); ?></option>
					</select>
					</div>
				</div>
				<div class="row striped">
					<div class="h"><?php echo l('field_preset', 'themes', '', 'text', array()); ?>:</div>
					<div class="v">
					<select id="sample_preset"  class="middle_short">
					<option value="default"><?php echo l('field_preset_default', 'themes', '', 'text', array()); ?></option>
					<option value="pastel"><?php echo l('field_preset_pastel', 'themes', '', 'text', array()); ?></option>
					<option value="soft"><?php echo l('field_preset_soft', 'themes', '', 'text', array()); ?></option>
					<option value="hard"><?php echo l('field_preset_hard', 'themes', '', 'text', array()); ?></option>
					<option value="light"><?php echo l('field_preset_light', 'themes', '', 'text', array()); ?></option>
					<option value="pale"><?php echo l('field_preset_pale', 'themes', '', 'text', array()); ?></option>
					</select>
					</div>
				</div>

				<h2><?php echo l('header_bright_colors_header', 'themes', '', 'text', array()); ?></h2>
				<div class="row striped" id="sample_main_bg">
					<div class="h"><?php echo l('field_main_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_html_bg">
					<div class="h"><?php echo l('field_html_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_header_bg">
					<div class="h"><?php echo l('field_header_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_footer_bg">
					<div class="h"><?php echo l('field_footer_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_menu_hover_bg">
					<div class="h"><?php echo l('field_menu_hover_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_hover_bg">
					<div class="h"><?php echo l('field_hover_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_popup_bg">
					<div class="h"><?php echo l('field_popup_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_highlight_bg">
					<div class="h"><?php echo l('field_highlight_bg', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_input_color">
					<div class="h"><?php echo l('field_input_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_input_bg_color">
					<div class="h"><?php echo l('field_input_bg_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_status_color">
					<div class="h"><?php echo l('field_status_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_link_color">
					<div class="h"><?php echo l('field_link_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<h2><?php echo l('header_dull_colors_header', 'themes', '', 'text', array()); ?></h2>
				<div class="row striped" id="sample_font_color">
					<div class="h"><?php echo l('field_font_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_header_color">
					<div class="h"><?php echo l('field_header_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_descr_color">
					<div class="h"><?php echo l('field_descr_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_contrast_color">
					<div class="h"><?php echo l('field_contrast_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_delimiter_color">
					<div class="h"><?php echo l('field_delimiter_color', 'themes', '', 'text', array()); ?>:</div>
					<div class="v"></div>
				</div>


				<div class="btn"><div class="l"><input type="button" name="btn_save" value="<?php echo l('btn_apply', 'start', '', 'button', array()); ?>" id="color_sheme_save" onclick="javascript: apply();"></div></div>
			</div>


	</div>
</div>
</form>
<div class="clr"></div>

<script><?php echo '
	$("div.row:odd").addClass("zebra");
'; ?>
</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>