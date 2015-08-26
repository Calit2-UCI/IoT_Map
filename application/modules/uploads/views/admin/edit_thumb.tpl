{include file="header.tpl" load_type='ui'}
<link type="text/css" rel="stylesheet" href="{$site_root}application/modules/uploads/js/colorpicker/colorpicker.css"/>
{js module='uploads' file='colorpicker.min.js'}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_thumb_change' gid='uploads'}{else}{l i='admin_header_thumb_add' gid='uploads'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_prefix' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.prefix}" name="prefix"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_sizes' gid='uploads'}: </div>
			<div class="v">
				<input type="text" value="{$data.width}" name="width" class="short"> X
				<input type="text" value="{$data.height}" name="height" class="short">
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_resize_type' gid='uploads'}: </div>
			<div class="v">
				{foreach item=item key=key from=$lang_thumb_crop_param.option}
				<input type="radio" name="crop_param" {if $data.crop_param eq $key} checked{/if} value="{$key}" id="cp_{$key}"><label for="cp_{$key}">{$item}</label>
				{if $key eq 'color'}
					&nbsp;&nbsp;{l i='field_resize_bg_color' gid='uploads'}:
					<input type="hidden" name="crop_color" id="crop_color" value="{$data.crop_color}">
					<input class="color-pick" id="crop_color_block" readonly> <span class="color-pick-data" id="crop_color_data">#{$data.crop_color}</span>
					<script>{literal}
					$(function(){
						if($('#crop_color').val() != '') $('#crop_color_block').css('background-color', '#'+$('#crop_color').val());
						$('#crop_color_block').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$('#crop_color').val(hex);
								$('#crop_color_data').html('#' + hex);
								$('#crop_color_block').css('background-color', '#' + hex);
								$(el).ColorPickerHide();
							},
							onChange: function(hsb, hex, rgb, el) {
								$('#crop_color').val(hex);
								$('#crop_color_data').html('#' + hex);
								$('#crop_color_block').css('background-color', '#' + hex);
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($('#crop_color').val());
							}
						});
					});
					{/literal}</script>

				{/if}
				<br>
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_thumb_watermark' gid='uploads'}: </div>
			<div class="v">
				<select name="watermark_id">
				<option value="0">...</option>
				{foreach item=item key=key from=$watermarks}<option value="{$item.id}" {if $item.id eq $data.watermark_id}selected{/if}>{$item.name} ({$item.gid})</option>{/foreach}
				</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_effects' gid='uploads'}: </div>
			<div class="v">
				<select name="effect">{foreach item=item key=key from=$lang_thumb_effect.option}<option value="{$key}" {if $key eq $data.effect}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/uploads/config_thumbs/{$config_id}">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
{include file="footer.tpl"}
