{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $add_flag}{l i='admin_header_reasons_create' gid='spam'}{else}{l i='admin_header_reasons_edit' gid='spam'}{/if}</div>
		{foreach item=item key=lang_id from=$langs}
		<div class="row">
			<div class="h">{$item.name}:&nbsp;* </div>
			<div class="v"><input type="text" value="{$lang_data[$lang_id]|escape}" name="lang_data[{$lang_id}]" class="long"></div>
		</div>
		{/foreach}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/spam/reasons/{$current_lang_id}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}
