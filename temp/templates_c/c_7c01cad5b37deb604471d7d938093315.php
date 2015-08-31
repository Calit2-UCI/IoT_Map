<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:11:30 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
<div class="lef">
	<div class="edit-form n100">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_watermark_change', 'uploads', '', 'text', array());  else:  echo l('admin_header_watermark_add', 'uploads', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_name', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['name']; ?>
" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_gid', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['gid']; ?>
" name="gid" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_position', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="position_hor" id="wm_position_hor" class="middle_short"><?php if (is_array($this->_vars['lang_positions_hor']['option']) and count((array)$this->_vars['lang_positions_hor']['option'])): foreach ((array)$this->_vars['lang_positions_hor']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['position_hor']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select><br>
				<select name="position_ver" id="wm_position_ver" class="middle_short"><?php if (is_array($this->_vars['lang_positions_ver']['option']) and count((array)$this->_vars['lang_positions_ver']['option'])): foreach ((array)$this->_vars['lang_positions_ver']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['position_ver']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
			</div>
		</div>

		<div class="row">
			<div class="h"><?php echo l('field_wm_type', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="wm_type" id="wm_type" class="middle_short" onchange="javascript: change_block(this.value);"><?php if (is_array($this->_vars['lang_wm_type']['option']) and count((array)$this->_vars['lang_wm_type']['option'])): foreach ((array)$this->_vars['lang_wm_type']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['wm_type']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
			</div>
		</div>

		<div id="wm_img_block" <?php if ($this->_vars['data']['wm_type'] != 'img'): ?>style="display: none"<?php endif; ?>>
			<div class="row zebra">
				<div class="h"><?php echo l('field_alpha', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="hidden" name="alpha" value="<?php echo $this->_vars['data']['alpha']; ?>
" id="wm_alpha_value">
					<div id='wm_alpha_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all'></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_alpha_slider_val"><?php echo $this->_vars['data']['alpha']; ?>
%</div>
					<script><?php echo '
						$(document).ready(function(){
							$("#wm_alpha_slider").slider({
								range: "min",
								value: ';  echo $this->_vars['data']['alpha'];  echo ',
								min: 1,
								max: 100,
								slide: function(event, ui) {
									$("#wm_alpha_value").val(ui.value);
									$("#wm_alpha_slider_val").html(ui.value+"%");
								}
							});
						});
					'; ?>
</script>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_img', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v"><input type="file" value="" name="img" class="file" id="wm_img">
				<?php if ($this->_vars['data']['img']): ?><br><img src="<?php echo $this->_vars['data']['img_url']; ?>
"><?php endif; ?>
				</div>
			</div>

		</div>

		<div id="wm_text_block" <?php if ($this->_vars['data']['wm_type'] != 'text'): ?>style="display: none"<?php endif; ?>>
			<div class="row zebra">
				<div class="h"><?php echo l('field_font_text', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v"><input type="text" id="wm_font_text" value="<?php echo $this->_vars['data']['font_text']; ?>
" name="font_text" ></div>
			</div>

			<div class="row">
				<div class="h"><?php echo l('field_font_family', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<select name="font_face" id="wm_font_face" class="middle"><?php if (is_array($this->_vars['lang_font_face']['option']) and count((array)$this->_vars['lang_font_face']['option'])): foreach ((array)$this->_vars['lang_font_face']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['font_face']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
				</div>
			</div>
			<div class="row zebra">
				<div class="h"><?php echo l('field_font_size', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="hidden" name="font_size" value="<?php echo $this->_vars['data']['font_size']; ?>
" id="wm_font_size_value">
					<div id='wm_font_size_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all' ></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_font_size_slider_val"><?php if ($this->_vars['data']['font_size']):  echo $this->_vars['data']['font_size'];  else:  echo $this->_vars['wm_text_limits']['min_font_size'];  endif; ?></div>
					<script><?php echo '
						$(document).ready(function(){
							$("#wm_font_size_slider").slider({
								range: "min",
								value: ';  if ($this->_vars['data']['font_size']):  echo $this->_vars['data']['font_size'];  else:  echo $this->_vars['wm_text_limits']['min_font_size'];  endif;  echo ',
								min: ';  echo $this->_vars['wm_text_limits']['min_font_size'];  echo ',
								max: ';  echo $this->_vars['wm_text_limits']['max_font_size'];  echo ',
								slide: function(event, ui) {
									$("#wm_font_size_value").val(ui.value);
									$("#wm_font_size_slider_val").html(ui.value);
								}
							});
						});
					'; ?>
</script>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_shadow_distance', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="hidden" name="shadow_distance" value="<?php echo $this->_vars['data']['shadow_distance']; ?>
" id="wm_shadow_distance_value">
					<div id='wm_shadow_distance_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all' ></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_shadow_distance_slider_val"><?php if ($this->_vars['data']['shadow_distance']):  echo $this->_vars['data']['shadow_distance'];  else:  echo $this->_vars['wm_text_limits']['min_shadow_distance'];  endif; ?></div>
					<script><?php echo '
						$(document).ready(function(){
							$("#wm_shadow_distance_slider").slider({
								range: "min",
								value: ';  if ($this->_vars['data']['shadow_distance']):  echo $this->_vars['data']['shadow_distance'];  else:  echo $this->_vars['wm_text_limits']['min_shadow_distance'];  endif;  echo ',
								min: ';  echo $this->_vars['wm_text_limits']['min_shadow_distance'];  echo ',
								max: ';  echo $this->_vars['wm_text_limits']['max_shadow_distance'];  echo ',
								slide: function(event, ui) {
									$("#wm_shadow_distance_value").val(ui.value);
									$("#wm_shadow_distance_slider_val").html(ui.value);
								}
							});
						});
					'; ?>
</script>
				</div>
			</div>
			<div class="row zebra">
				<div class="h"><?php echo l('field_font_color', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="hidden" name="font_color" id="wm_font_color" value="<?php echo $this->_vars['data']['font_color']; ?>
">
					<input class="color-pick" id="font_color_block" readonly> <span class="color-pick-data" id="font_color_data">#<?php echo $this->_vars['data']['font_color']; ?>
</span>
					<script><?php echo '
					$(function(){
						if($(\'#wm_font_color\').val() != \'\') $(\'#font_color_block\').css(\'background-color\', \'#\'+$(\'#wm_font_color\').val());
						$(\'#font_color_block\').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$(\'#wm_font_color\').val(hex);
								$(\'#font_color_data\').html(\'#\' + hex);
								$(\'#font_color_block\').css(\'background-color\', \'#\' + hex);
								$(el).ColorPickerHide();
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($(\'#wm_font_color\').val());
							}
						});
					});
					'; ?>
</script>
				</div>
			</div>
			<div class="row">
				<div class="h"><?php echo l('field_shadow_color', 'uploads', '', 'text', array()); ?>: </div>
				<div class="v">
					<input type="hidden" name="shadow_color" id="wm_shadow_color" value="<?php echo $this->_vars['data']['shadow_color']; ?>
">
					<input class="color-pick" id="shadow_color_block" readonly> <span class="color-pick-data" id="shadow_color_data">#<?php echo $this->_vars['data']['shadow_color']; ?>
</span>
					<script><?php echo '
					$(function(){
						if($(\'#wm_shadow_color\').val() != \'\') $(\'#shadow_color_block\').css(\'background-color\', \'#\'+$(\'#wm_shadow_color\').val());
						$(\'#shadow_color_block\').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$(\'#wm_shadow_color\').val(hex);
								$(\'#shadow_color_data\').html(\'#\' + hex);
								$(\'#shadow_color_block\').css(\'background-color\', \'#\' + hex);
								$(el).ColorPickerHide();
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($(\'#wm_shadow_color\').val());
							}
						});
					});
					'; ?>
</script>
				</div>
			</div>

		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<!-- <a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/watermarks"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a> -->
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_preview" value="<?php echo l('btn_preview', 'start', '', 'button', array()); ?>" onclick="javascript: wm_preview();"></div></div>
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header"><?php echo l('admin_header_preview', 'uploads', '', 'text', array()); ?></div>
		<div class="row preview zebra">
		<?php if ($this->_vars['data']['id']): ?><img src="<?php echo $this->_vars['site_root']; ?>
admin/uploads/wm_preview/<?php echo $this->_vars['data']['id']; ?>
" id="wm_preview_img"><?php else: ?><img src="<?php echo $this->_vars['watermark_test']; ?>
" id="wm_preview_img"><?php endif; ?>
		</div>
	</div>
</div>
</form>
<div class="clr"></div>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_vars['site_root']; ?>
application/modules/uploads/js/colorpicker/colorpicker.css"/>
<?php echo tpl_function_js(array('module' => 'uploads','file' => 'colorpicker.min.js'), $this); echo tpl_function_js(array('file' => 'jquery.form.min.js'), $this);?>
<script>
var preview_post_link = '<?php echo $this->_vars['site_url']; ?>
admin/uploads/wm_save_preview_data/data/wm_preview';
var preview_file_link = '<?php echo $this->_vars['site_url']; ?>
admin/uploads/wm_save_preview_data/file/wm_preview';
var wm_img_preview_link = '<?php echo $this->_vars['site_url']; ?>
admin/uploads/wm_preview/<?php echo $this->_vars['data']['id']; ?>
/wm_preview';
var wm_id = '<?php echo $this->_vars['data']['id']; ?>
';

<?php echo '
function wm_preview(){
	if($(\'#wm_img\').val()){
		load_file_params();
	}else{
		if(!wm_id)
			return false;
		load_post_params();
	}
}

function load_post_params(){
	var data = new Object();
	data.position_hor = $(\'#wm_position_hor\').val();
	data.position_ver = $(\'#wm_position_ver\').val();
	data.wm_type = $(\'#wm_type\').val();
	data.font_text = $(\'#wm_font_text\').val();
	data.font_size = $(\'#wm_font_size_value\').val();
	data.font_color = $(\'#wm_font_color\').val();
	data.font_face = $(\'#wm_font_face\').val();
	data.shadow_color = $(\'#wm_shadow_color\').val();
	data.shadow_distance = $(\'#wm_shadow_distance_value\').val();
	data.alpha = $(\'#wm_alpha_value\').val();

	$.ajax({
		url: preview_post_link,
		type: \'POST\',
		cache: false,
		data: data,
		success: function(data){
			$(\'#wm_preview_img\').attr(\'src\', wm_img_preview_link+\'?t=\'+(1000*Math.random()));
		}
	});
}

function load_file_params(){
	var options = {
		url: preview_file_link,
		success: function() {
			load_post_params();
		}
	};
	$("form").ajaxSubmit(options);
}

function change_block(type){
	$(\'#wm_text_block\').hide();
	$(\'#wm_img_block\').hide();
	$(\'#wm_\'+type+\'_block\').show();
}
'; ?>

</script>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>