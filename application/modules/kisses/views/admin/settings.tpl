{include file="header.tpl"}
{helper func_name=get_admin_level1_menu helper_name=menu func_param='admin_kisses_menu'}

<div class="actions">&nbsp;</div>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='admin_header_kisses_settings' gid='kisses'}</div>
		<div class="row">
			<div class="h">{l i='settings_admin_items_per_page' gid='kisses'}:</div>
			<div class="v">
				<input type="text" name="admin_items_per_page" value="{$settings_data.admin_items_per_page}" class="short" id="admin_items_per_page">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='settings_users_items_per_page' gid='kisses'}:</div>
			<div class="v">
				<input type="text" name="items_per_page" value="{$settings_data.items_per_page}" class="short" id="users_items_per_page">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='number_max_symbols' gid='kisses'}:</div>
			<div class="v">
				<input type="text" name="number_max_symbols" value="{$settings_data.number_max_symbols}" class="short" id="number_max_symbols">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='admin_system_settings_page' gid='kisses'}:</div>
			<div class="v">
				<input type="checkbox" name="system_settings_page" value="1" {if $settings_data.system_settings_page}checked{/if} class="short" id="system_settings_page">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
</form>
<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{include file="footer.tpl"}
