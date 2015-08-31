<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:12:38 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<link type="text/css" rel="stylesheet" href="<?php echo $this->_vars['site_root']; ?>
application/modules/uploads/js/colorpicker/colorpicker.css"/>
<?php echo tpl_function_js(array('module' => 'uploads','file' => 'colorpicker.min.js'), $this);?>

<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php if ($this->_vars['data']['id']):  echo l('admin_header_thumb_change', 'uploads', '', 'text', array());  else:  echo l('admin_header_thumb_add', 'uploads', '', 'text', array());  endif; ?></div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_prefix', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" value="<?php echo $this->_vars['data']['prefix']; ?>
" name="prefix"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_sizes', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="text" value="<?php echo $this->_vars['data']['width']; ?>
" name="width" class="short"> X
				<input type="text" value="<?php echo $this->_vars['data']['height']; ?>
" name="height" class="short">
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_resize_type', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<?php if (is_array($this->_vars['lang_thumb_crop_param']['option']) and count((array)$this->_vars['lang_thumb_crop_param']['option'])): foreach ((array)$this->_vars['lang_thumb_crop_param']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
				<input type="radio" name="crop_param" <?php if ($this->_vars['data']['crop_param'] == $this->_vars['key']): ?> checked<?php endif; ?> value="<?php echo $this->_vars['key']; ?>
" id="cp_<?php echo $this->_vars['key']; ?>
"><label for="cp_<?php echo $this->_vars['key']; ?>
"><?php echo $this->_vars['item']; ?>
</label>
				<?php if ($this->_vars['key'] == 'color'): ?>
					&nbsp;&nbsp;<?php echo l('field_resize_bg_color', 'uploads', '', 'text', array()); ?>:
					<input type="hidden" name="crop_color" id="crop_color" value="<?php echo $this->_vars['data']['crop_color']; ?>
">
					<input class="color-pick" id="crop_color_block" readonly> <span class="color-pick-data" id="crop_color_data">#<?php echo $this->_vars['data']['crop_color']; ?>
</span>
					<script><?php echo '
					$(function(){
						if($(\'#crop_color\').val() != \'\') $(\'#crop_color_block\').css(\'background-color\', \'#\'+$(\'#crop_color\').val());
						$(\'#crop_color_block\').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$(\'#crop_color\').val(hex);
								$(\'#crop_color_data\').html(\'#\' + hex);
								$(\'#crop_color_block\').css(\'background-color\', \'#\' + hex);
								$(el).ColorPickerHide();
							},
							onChange: function(hsb, hex, rgb, el) {
								$(\'#crop_color\').val(hex);
								$(\'#crop_color_data\').html(\'#\' + hex);
								$(\'#crop_color_block\').css(\'background-color\', \'#\' + hex);
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($(\'#crop_color\').val());
							}
						});
					});
					'; ?>
</script>

				<?php endif; ?>
				<br>
				<?php endforeach; endif; ?>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_thumb_watermark', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="watermark_id">
				<option value="0">...</option>
				<?php if (is_array($this->_vars['watermarks']) and count((array)$this->_vars['watermarks'])): foreach ((array)$this->_vars['watermarks'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['item']['id']; ?>
" <?php if ($this->_vars['item']['id'] == $this->_vars['data']['watermark_id']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
 (<?php echo $this->_vars['item']['gid']; ?>
)</option><?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h"><?php echo l('field_effects', 'uploads', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="effect"><?php if (is_array($this->_vars['lang_thumb_effect']['option']) and count((array)$this->_vars['lang_thumb_effect']['option'])): foreach ((array)$this->_vars['lang_thumb_effect']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['effect']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?></select>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
		<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/uploads/config_thumbs/<?php echo $this->_vars['config_id']; ?>
"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
	</div>
</form>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
