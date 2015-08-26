{include file="header.tpl" load_type='ui'}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
<div class="lef">
	<div class="edit-form n100">
		<div class="row header">{l i='admin_header_configuration' gid='widgets'}</div>	
		<div class="row">
			<div class="h">{l i='field_widget_size' gid='widgets'}:</div>
			<div class="v">
				{ld i='widget_size' gid='widgets' assign='sizes'}
				<select name="data[size]" class="middle">
					{foreach item=item key=key from=$sizes.option}
					<option value="{$key}" {if $widget.size eq $key}selected{/if}>{$item}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_widget_title' gid='widgets'}:</div>
			<div class="v">
				{foreach item=lang_item key=lang_id from=$langs}
				{assign var='name' value='title_'+$lang_id}
				<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="data[title_{$lang_id}]" value="{$widget[$name]|escape}" lang-editor="value" lang-editor-type="data-title" lang-editor-lid="{$lang_id}" class="middle"/>
				{/foreach}
				<a href="#" lang-editor="button" lang-editor-type="data-title"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
			</div>	
		</div>
		<div class="row">
			<div class="h">{l i='field_widget_footer' gid='widgets'}:</div>
			<div class="v">
				{foreach item=lang_item key=lang_id from=$langs}
				{assign var='name' value='footer_'+$lang_id}
				<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="data[footer_{$lang_id}]" value="{$widget[$name]|escape}" lang-editor="value" lang-editor-type="data-footer" lang-editor-lid="{$lang_id}" />
				{/foreach}
				<a href="#" lang-editor="button" lang-editor-type="data-footer"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_widget_colors' gid='widgets'}:</div>
			<div class="v">
				{js file='colorsets/jscolor/jscolor.js'}
				<input type="text" name="data[colors][background]" value="{$widget.colors.background|escape}" class="color-pick short_long" id="colors_background"> {l i='colors_background' gid='widgets'}
				<p><input type="text" name="data[colors][border]" value="{$widget.colors.border|escape}" class="color-pick short_long" id="colors_border"> {l i='colors_border' gid='widgets'}</p>
				<p><input type="text" name="data[colors][text]" value="{$widget.colors.text|escape}" class="color-pick short_long" id="colors_text"> {l i='colors_text' gid='widgets'}</p>
				<p><input type="text" name="data[colors][link]" value="{$widget.colors.link|escape}" class="color-pick short_long" id="colors_link"> {l i='colors_link' gid='widgets'}</p>
				<input type="text" name="data[colors][block]" value="{$widget.colors.block|escape}" class="color-pick short_long" id="colors_block"> {l i='colors_block' gid='widgets'}
			</div>
		</div>
		{$settings_form}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	{*<div class="btn gray fright"><div class="l"><input type="button" name="btn_preview" value="{l i='btn_preview' gid='start' type='button'}" onclick="javascript: wm_preview();"></div></div>*}
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header">{l i='admin_header_preview' gid='widgets'}</div>
		<div class="row preview zebra">{$widget_code}</div>
	</div>
	<br>
</div>
</form>
<div class="clr"></div>
<div class="edit-form">
	<div class="row header">{l i='admin_header_widget_code' gid='widgets'}:</div>	
	<div class="row"><textarea name="data[code]" rows="10" cols="80" id="code" readonly>{include file="widget_code.tpl" module="widgets"}</textarea></div>
</div>
<div class="clr"></div>
<script>{literal}
	$(function(){
		$('#code').bind('click', function(){this.select()}).trigger('click');
	});
{/literal}</script>
{block name='lang_inline_editor' module='start'}
{include file="footer.tpl"}
