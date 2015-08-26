{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_menu_change' gid='menu'}{else}{l i='admin_header_menu_add' gid='menu'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_menu_name' gid='menu'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_menu_gid' gid='menu'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_menu_check_permissions' gid='menu'}: </div>
			<div class="v"><input type="checkbox" name="check_permissions" value="1" {if $data.check_permissions}checked{/if}></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/menu">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

{include file="footer.tpl"}