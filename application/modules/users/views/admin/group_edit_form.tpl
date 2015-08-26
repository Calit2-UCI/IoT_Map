{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_groups_change' gid='users'}{else}{l i='admin_header_groups_add' gid='users'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_group_gid' gid='users'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
		</div>
		{foreach item=item key=lang_id from=$languages}
		<div class="row">
			<div class="h">{l i='field_group_name' gid='users'}({$item.name}): </div>
			<div class="v">
				<input type="text" name="langs[{$lang_id}]" value="{$data.langs[$lang_id]}">
			</div>
		</div>
		{/foreach}
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/users/groups">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}