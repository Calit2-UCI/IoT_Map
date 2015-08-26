{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $add_flag}{l i='admin_header_ds_item_add' gid='properties'}{else}{l i='admin_header_ds_item_change' gid='properties'}{/if}&nbsp;[ID = {$option_gid}]</div>
		{foreach item=item key=lang_id from=$langs}
			<div class="row striped">
				<div class="h">{$item.name}: </div>
				<div class="v"><input type="text" value="{$lang_data[$lang_id]|escape}" name="lang_data[{$lang_id}]" class="long"></div>
			</div>
		{/foreach}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/properties/property/{$current_gid}/{$current_lang_id}/">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{include file="footer.tpl"}