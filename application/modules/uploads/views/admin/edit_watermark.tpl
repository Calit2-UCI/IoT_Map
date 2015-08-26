{include file="header.tpl" load_type='ui'}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
<div class="lef">
	<div class="edit-form n100">
		<div class="row header">{if $data.id}{l i='admin_header_watermark_change' gid='uploads'}{else}{l i='admin_header_watermark_add' gid='uploads'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_position' gid='uploads'}: </div>
			<div class="v">
				<select name="position_hor" id="wm_position_hor" class="middle_short">{foreach item=item key=key from=$lang_positions_hor.option}<option value="{$key}" {if $key eq $data.position_hor}selected{/if}>{$item}</option>{/foreach}</select><br>
				<select name="position_ver" id="wm_position_ver" class="middle_short">{foreach item=item key=key from=$lang_positions_ver.option}<option value="{$key}" {if $key eq $data.position_ver}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>

		<div class="row">
			<div class="h">{l i='field_wm_type' gid='uploads'}: </div>
			<div class="v">
				<select name="wm_type" id="wm_type" class="middle_short" onchange="javascript: change_block(this.value);">{foreach item=item key=key from=$lang_wm_type.option}<option value="{$key}" {if $key eq $data.wm_type}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>

		<div id="wm_img_block" {if $data.wm_type ne 'img'}style="display: none"{/if}>
			<div class="row zebra">
				<div class="h">{l i='field_alpha' gid='uploads'}: </div>
				<div class="v">
					<input type="hidden" name="alpha" value="{$data.alpha}" id="wm_alpha_value">
					<div id='wm_alpha_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all'></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_alpha_slider_val">{$data.alpha}%</div>
					<script>{literal}
						$(document).ready(function(){
							$("#wm_alpha_slider").slider({
								range: "min",
								value: {/literal}{$data.alpha}{literal},
								min: 1,
								max: 100,
								slide: function(event, ui) {
									$("#wm_alpha_value").val(ui.value);
									$("#wm_alpha_slider_val").html(ui.value+"%");
								}
							});
						});
					{/literal}</script>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_img' gid='uploads'}: </div>
				<div class="v"><input type="file" value="" name="img" class="file" id="wm_img">
				{if $data.img}<br><img src="{$data.img_url}">{/if}
				</div>
			</div>

		</div>

		<div id="wm_text_block" {if $data.wm_type ne 'text'}style="display: none"{/if}>
			<div class="row zebra">
				<div class="h">{l i='field_font_text' gid='uploads'}: </div>
				<div class="v"><input type="text" id="wm_font_text" value="{$data.font_text}" name="font_text" ></div>
			</div>

			<div class="row">
				<div class="h">{l i='field_font_family' gid='uploads'}: </div>
				<div class="v">
					<select name="font_face" id="wm_font_face" class="middle">{foreach item=item key=key from=$lang_font_face.option}<option value="{$key}" {if $key eq $data.font_face}selected{/if}>{$item}</option>{/foreach}</select>
				</div>
			</div>
			<div class="row zebra">
				<div class="h">{l i='field_font_size' gid='uploads'}: </div>
				<div class="v">
					<input type="hidden" name="font_size" value="{$data.font_size}" id="wm_font_size_value">
					<div id='wm_font_size_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all' ></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_font_size_slider_val">{if $data.font_size}{$data.font_size}{else}{$wm_text_limits.min_font_size}{/if}</div>
					<script>{literal}
						$(document).ready(function(){
							$("#wm_font_size_slider").slider({
								range: "min",
								value: {/literal}{if $data.font_size}{$data.font_size}{else}{$wm_text_limits.min_font_size}{/if}{literal},
								min: {/literal}{$wm_text_limits.min_font_size}{literal},
								max: {/literal}{$wm_text_limits.max_font_size}{literal},
								slide: function(event, ui) {
									$("#wm_font_size_value").val(ui.value);
									$("#wm_font_size_slider_val").html(ui.value);
								}
							});
						});
					{/literal}</script>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_shadow_distance' gid='uploads'}: </div>
				<div class="v">
					<input type="hidden" name="shadow_distance" value="{$data.shadow_distance}" id="wm_shadow_distance_value">
					<div id='wm_shadow_distance_slider' class='ui-slider ui-slider-horizontal ui-widget ui-widget-content ui-corner-all slider-block'><a href='#' class='ui-slider-handle ui-state-default ui-corner-all' ></a><div class='ui-slider-range ui-slider-range-min ui-widget-header'></div></div>
					<div id="wm_shadow_distance_slider_val">{if $data.shadow_distance}{$data.shadow_distance}{else}{$wm_text_limits.min_shadow_distance}{/if}</div>
					<script>{literal}
						$(document).ready(function(){
							$("#wm_shadow_distance_slider").slider({
								range: "min",
								value: {/literal}{if $data.shadow_distance}{$data.shadow_distance}{else}{$wm_text_limits.min_shadow_distance}{/if}{literal},
								min: {/literal}{$wm_text_limits.min_shadow_distance}{literal},
								max: {/literal}{$wm_text_limits.max_shadow_distance}{literal},
								slide: function(event, ui) {
									$("#wm_shadow_distance_value").val(ui.value);
									$("#wm_shadow_distance_slider_val").html(ui.value);
								}
							});
						});
					{/literal}</script>
				</div>
			</div>
			<div class="row zebra">
				<div class="h">{l i='field_font_color' gid='uploads'}: </div>
				<div class="v">
					<input type="hidden" name="font_color" id="wm_font_color" value="{$data.font_color}">
					<input class="color-pick" id="font_color_block" readonly> <span class="color-pick-data" id="font_color_data">#{$data.font_color}</span>
					<script>{literal}
					$(function(){
						if($('#wm_font_color').val() != '') $('#font_color_block').css('background-color', '#'+$('#wm_font_color').val());
						$('#font_color_block').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$('#wm_font_color').val(hex);
								$('#font_color_data').html('#' + hex);
								$('#font_color_block').css('background-color', '#' + hex);
								$(el).ColorPickerHide();
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($('#wm_font_color').val());
							}
						});
					});
					{/literal}</script>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_shadow_color' gid='uploads'}: </div>
				<div class="v">
					<input type="hidden" name="shadow_color" id="wm_shadow_color" value="{$data.shadow_color}">
					<input class="color-pick" id="shadow_color_block" readonly> <span class="color-pick-data" id="shadow_color_data">#{$data.shadow_color}</span>
					<script>{literal}
					$(function(){
						if($('#wm_shadow_color').val() != '') $('#shadow_color_block').css('background-color', '#'+$('#wm_shadow_color').val());
						$('#shadow_color_block').ColorPicker({
							onSubmit: function(hsb, hex, rgb, el) {
								$('#wm_shadow_color').val(hex);
								$('#shadow_color_data').html('#' + hex);
								$('#shadow_color_block').css('background-color', '#' + hex);
								$(el).ColorPickerHide();
							},
							onBeforeShow: function () {
								$(this).ColorPickerSetColor($('#wm_shadow_color').val());
							}
						});
					});
					{/literal}</script>
				</div>
			</div>

		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<!-- <a class="cancel" href="{$site_url}admin/uploads/watermarks">{l i='btn_cancel' gid='start'}</a> -->
	<div class="btn gray fright"><div class="l"><input type="button" name="btn_preview" value="{l i='btn_preview' gid='start' type='button'}" onclick="javascript: wm_preview();"></div></div>
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header">{l i='admin_header_preview' gid='uploads'}</div>
		<div class="row preview zebra">
		{if $data.id}<img src="{$site_root}admin/uploads/wm_preview/{$data.id}" id="wm_preview_img">{else}<img src="{$watermark_test}" id="wm_preview_img">{/if}
		</div>
	</div>
</div>
</form>
<div class="clr"></div>
<link type="text/css" rel="stylesheet" href="{$site_root}application/modules/uploads/js/colorpicker/colorpicker.css"/>
{js module='uploads' file='colorpicker.min.js'}
{js file='jquery.form.min.js'}
<script>
var preview_post_link = '{$site_url}admin/uploads/wm_save_preview_data/data/wm_preview';
var preview_file_link = '{$site_url}admin/uploads/wm_save_preview_data/file/wm_preview';
var wm_img_preview_link = '{$site_url}admin/uploads/wm_preview/{$data.id}/wm_preview';
var wm_id = '{$data.id}';

{literal}
function wm_preview(){
	if($('#wm_img').val()){
		load_file_params();
	}else{
		if(!wm_id)
			return false;
		load_post_params();
	}
}

function load_post_params(){
	var data = new Object();
	data.position_hor = $('#wm_position_hor').val();
	data.position_ver = $('#wm_position_ver').val();
	data.wm_type = $('#wm_type').val();
	data.font_text = $('#wm_font_text').val();
	data.font_size = $('#wm_font_size_value').val();
	data.font_color = $('#wm_font_color').val();
	data.font_face = $('#wm_font_face').val();
	data.shadow_color = $('#wm_shadow_color').val();
	data.shadow_distance = $('#wm_shadow_distance_value').val();
	data.alpha = $('#wm_alpha_value').val();

	$.ajax({
		url: preview_post_link,
		type: 'POST',
		cache: false,
		data: data,
		success: function(data){
			$('#wm_preview_img').attr('src', wm_img_preview_link+'?t='+(1000*Math.random()));
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
	$('#wm_text_block').hide();
	$('#wm_img_block').hide();
	$('#wm_'+type+'_block').show();
}
{/literal}
</script>
{include file="footer.tpl"}