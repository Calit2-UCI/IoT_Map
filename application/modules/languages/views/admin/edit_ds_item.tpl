{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $current_index}{l i='admin_header_ds_item_change' gid='languages'}{else}{l i='admin_header_ds_item_add' gid='languages'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='languages'}: </div>
			<div class="v">{if $option_gid}{$option_gid}{else}<input type="text" value="" name="option_gid">{/if}</div>
		</div>
		{foreach item=item key=lang_id from=$langs}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" value="{$lang_data[$lang_id]|escape}" name="lang_data[{$lang_id}]" class="long"></div>
		</div>
		{/foreach}
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/languages/ds_items/{$current_lang_id}/{$current_module_id}/{$current_gid}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}