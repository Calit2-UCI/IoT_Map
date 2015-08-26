{include file="header.tpl" load_type='ui'}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
<div class="lef">
	<div class="edit-form n100">
		<div class="row header">{if $set.id}{l i='admin_header_set_change' gid='themes'}{else}{l i='admin_header_set_add' gid='themes'}{/if}</div>
		<div class="row striped">
			<div class="h">{l i='field_name' gid='themes'}: </div>
			<div class="v"><input type="text" value="{$set.set_name}" name="set_name" class="middle"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_set_gid' gid='themes'}:</div>
			<div class="v"><input type="text" size="30" name="set_gid" id="set_gid" value="{$set.set_gid}" class="middle"></div>
		</div>
		<input type="hidden" name="scheme_type" value="light" />
		{*<div class="row striped">
			<div class="h">{l i='field_scheme_name' gid='themes'}:</div>
			<div class="v">
				<select name="scheme_type" id="scheme_type">
				<option value="light" {if $set.scheme_type eq 'light'}selected{/if}>{l i='field_scheme_name_light' gid='themes'}</option>
				<option value="dark" {if $set.scheme_type eq 'dark'}selected{/if}>{l i='field_scheme_name_dark' gid='themes'}</option>
				</select>
			</div>
		</div>*}

		<h2>{l i='header_text_sizes_header' gid='themes'}</h2>

		<div class="row striped">
			<div class="h">{l i='field_font_family' gid='themes'}:</div>
			<div class="v"><input type="text" size="30" name="font_family" id="font_family" value="{$set.color_settings.font_family|escape}" class="middle"></div>
		</div>
	</div>

	<div class="edit-form">
		<div class="row striped">
			<div class="h">{l i='field_main_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="main_font_size" id="main_font_size" value="{$set.color_settings.main_font_size}"> px</div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_input_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="input_font_size" id="input_font_size" value="{$set.color_settings.input_font_size}"> px</div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_h1_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="h1_font_size" id="h1_font_size" value="{$set.color_settings.h1_font_size}"> px</div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_h2_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="h2_font_size" id="h2_font_size" value="{$set.color_settings.h2_font_size}"> px</div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_small_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="small_font_size" id="small_font_size" value="{$set.color_settings.small_font_size}"> px</div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_search_btn_font_size' gid='themes'}:</div>
			<div class="v"><input type="text" class="short" size="3" name="search_btn_font_size" id="search_btn_font_size" value="{$set.color_settings.search_btn_font_size}"> px</div>
		</div>

		<h2>{l i='header_index_background' gid='themes'}</h2>
		
		<div class="row striped">
			<div class="h">{l i='field_bg_image' gid='themes'}:</div>
			<div class="v"><input type="file" name="index_bg_image" id="index_bg_image" value="{$set.color_settings.index_bg_image}" size="2" /></div>
			<input type="hidden" name="index_bg_image_ver" value="{$set.color_settings.index_bg_image_ver}" />
			{if $set.color_settings.index_bg_image}
				<div class="v"><label><input type="checkbox" name="index_bg_image_delete" />{l i='field_delete_bg_image' gid='themes'}</label></div>
				<div class="p-top2"><img src="{$bg_img_url}" class="wp100"></div>
			{/if}
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="short_long" name="index_bg_image_bg" id="index_bg_image_bg" value="{$set.color_settings.index_bg_image_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_scroll' gid='themes'}:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_scroll" value="1"{if $set.color_settings.index_bg_image_scroll} checked{/if}></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_adjust_width' gid='themes'}:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_adjust_width" value="1"{if $set.color_settings.index_bg_image_adjust_width} checked{/if}></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_adjust_height' gid='themes'}:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_adjust_height" value="1"{if $set.color_settings.index_bg_image_adjust_height} checked{/if}></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_repeat_x' gid='themes'}:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_repeat_x" value="1"{if $set.color_settings.index_bg_image_repeat_x} checked{/if}></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_bg_image_repeat_y' gid='themes'}:</div>
			<div class="v"><input type="checkbox" name="index_bg_image_repeat_y" value="1"{if $set.color_settings.index_bg_image_repeat_y} checked{/if}></div>
		</div>
		
		
		<h2>{l i='header_bright_colors_header' gid='themes'}</h2>

		<div class="row striped">
			<div class="h">{l i='field_main_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="main_bg" id="main_bg" value="{$set.color_settings.main_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_html_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="html_bg" id="html_bg" value="{$set.color_settings.html_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_header_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="header_bg" id="header_bg" value="{$set.color_settings.header_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_footer_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="footer_bg" id="footer_bg" value="{$set.color_settings.footer_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_menu_hover_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="menu_hover_bg" id="menu_hover_bg" value="{$set.color_settings.menu_hover_bg}"></div>
		</div>
		<!--div class="row striped">
			<div class="h">{l i='field_hover_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick" name="hover_bg" id="hover_bg" value="{$set.color_settings.hover_bg}"></div>
		</div-->
		<div class="row striped">
			<div class="h">{l i='field_popup_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="popup_bg" id="popup_bg" value="{$set.color_settings.popup_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_highlight_bg' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="highlight_bg" id="highlight_bg" value="{$set.color_settings.highlight_bg}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_input_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="input_color" id="input_color" value="{$set.color_settings.input_color}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_input_bg_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="input_bg_color" id="input_bg_color" value="{$set.color_settings.input_bg_color}"></div>
		</div>
		<!--div class="row striped">
			<div class="h">{l i='field_status_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick" name="status_color" id="status_color" value="{$set.color_settings.status_color}"></div>
		</div-->
		<div class="row striped">
			<div class="h">{l i='field_link_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="link_color" id="link_color" value="{$set.color_settings.link_color}"></div>
		</div>

		<h2>{l i='header_dull_colors_header' gid='themes'}</h2>
		<div class="row striped">
			<div class="h">{l i='field_font_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="font_color" id="font_color" value="{$set.color_settings.font_color}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_header_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="header_color" id="header_color" value="{$set.color_settings.header_color}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_descr_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="descr_color" id="descr_color" value="{$set.color_settings.descr_color}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_contrast_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="contrast_color" id="contrast_color" value="{$set.color_settings.contrast_color}"></div>
		</div>
		<div class="row striped">
			<div class="h">{l i='field_delimiter_color' gid='themes'}:</div>
			<div class="v"><input type="text" size="10" class="color-pick short_long" name="delimiter_color" id="delimiter_color" value="{$set.color_settings.delimiter_color}"></div>
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/themes/sets/{$id_theme}">{l i='btn_cancel' gid='start'}</a>


</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header">{l i='admin_header_generate' gid='themes'}</div>
			<script type="text/javascript">
				var schemeSettings = {$scheme_json};
				var main_bg = '{$set.color_settings.main_bg}';
				var is_new = {if $set.id}false{else}true{/if};
			</script>

			{js file='colorsets/jscolor/jscolor.js'}
			{js file='colorsets/color_colorblind.js'}
			{js file='colorsets/color_scheme.js'}

			<link type="text/css" rel="stylesheet" href="{$site_root}{$js_folder}colorsets/color_scheme.css" />

			<div class="row striped">
				<div id="color_enter">
					<b>{l i='select_color_header' gid='themes'}:</b><br><br>
					<div id="image">
						<div id="wheelarea"></div>
						<div id="pointer0"></div>
						<div id="pointer1"></div>
						<div id="pointer2"></div>
						<div id="pointer3"></div>
					</div>
					<div id="maincolorhue"></div>

					<div id="manual_color_block">
						<b>{l i='manual_color_header' gid='themes'}:</b> <input type="text" size="3" name="manual_h" id="manual_h" value="" class="short">°
					</div>
				</div>
			</div>

			<div id="coltable">
				<div id="all_examples"></div>
				<div class="row striped">
					<div class="h">{l i='field_scheme_name' gid='themes'}:</div>
					<div class="v">
					<select id="sample_scheme_type" class="middle_short">
					<option value="light">{l i='field_scheme_name_light' gid='themes'}</option>
					<option value="dark">{l i='field_scheme_name_dark' gid='themes'}</option>
					</select>
					</div>
				</div>
				<div class="row striped">
					<div class="h">{l i='field_preset' gid='themes'}:</div>
					<div class="v">
					<select id="sample_preset"  class="middle_short">
					<option value="default">{l i='field_preset_default' gid='themes'}</option>
					<option value="pastel">{l i='field_preset_pastel' gid='themes'}</option>
					<option value="soft">{l i='field_preset_soft' gid='themes'}</option>
					<option value="hard">{l i='field_preset_hard' gid='themes'}</option>
					<option value="light">{l i='field_preset_light' gid='themes'}</option>
					<option value="pale">{l i='field_preset_pale' gid='themes'}</option>
					</select>
					</div>
				</div>

				<h2>{l i='header_bright_colors_header' gid='themes'}</h2>
				<div class="row striped" id="sample_main_bg">
					<div class="h">{l i='field_main_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_html_bg">
					<div class="h">{l i='field_html_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_header_bg">
					<div class="h">{l i='field_header_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_footer_bg">
					<div class="h">{l i='field_footer_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_menu_hover_bg">
					<div class="h">{l i='field_menu_hover_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_hover_bg">
					<div class="h">{l i='field_hover_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_popup_bg">
					<div class="h">{l i='field_popup_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_highlight_bg">
					<div class="h">{l i='field_highlight_bg' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_input_color">
					<div class="h">{l i='field_input_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_input_bg_color">
					<div class="h">{l i='field_input_bg_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_status_color">
					<div class="h">{l i='field_status_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_link_color">
					<div class="h">{l i='field_link_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<h2>{l i='header_dull_colors_header' gid='themes'}</h2>
				<div class="row striped" id="sample_font_color">
					<div class="h">{l i='field_font_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_header_color">
					<div class="h">{l i='field_header_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_descr_color">
					<div class="h">{l i='field_descr_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_contrast_color">
					<div class="h">{l i='field_contrast_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>
				<div class="row striped" id="sample_delimiter_color">
					<div class="h">{l i='field_delimiter_color' gid='themes'}:</div>
					<div class="v"></div>
				</div>


				<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_apply' gid='start' type='button'}" id="color_sheme_save" onclick="javascript: apply();"></div></div>
			</div>


	</div>
</div>
</form>
<div class="clr"></div>

<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{include file="footer.tpl"}