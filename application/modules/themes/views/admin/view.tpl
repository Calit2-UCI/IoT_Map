{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form"  enctype="multipart/form-data">

	<div class="filter-form">
		<div class="form">
			<b>{$theme.name}</b><br>{$theme.description}<br><br>
			{if $theme.img}<img src="{$theme.img}"><br><br>{/if}
			{l i='field_default' gid='themes'}: {if $theme.default}{l i='status_default_yes' gid='themes'}{else}{l i='status_default_no' gid='themes'}{/if}<br>
			{l i='field_active' gid='themes'}: {if $theme.active}{l i='status_active_yes' gid='themes'}{else}{l i='status_active_no' gid='themes'}{/if}<br><br>
		</div>
	</div>
	<br>
	
	<h2>{l i='admin_header_logo_editor' gid='themes'}</h2>
	
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_logo_settings' gid='themes'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_logo_width' gid='themes'}: </div>
			<div class="v"><input type="text" value="{$theme.logo_width}" name="logo_width" class="short"> {l i='size_px' gid='themes'}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_logo_height' gid='themes'}: </div>
			<div class="v"><input type="text" value="{$theme.logo_height}" name="logo_height" class="short"> {l i='size_px' gid='themes'}</div>
		</div>
	</div>
	<br />
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_mini_logo_settings' gid='themes'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_logo_width' gid='themes'}: </div>
			<div class="v"><input type="text" value="{$theme.mini_logo_width}" name="mini_logo_width" class="short"> {l i='size_px' gid='themes'}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_logo_height' gid='themes'}: </div>
			<div class="v"><input type="text" value="{$theme.mini_logo_height}" name="mini_logo_height" class="short"> {l i='size_px' gid='themes'}</div>
		</div>
	</div>
	<br />
	<div class="menu-level3">
		<ul id="edit_tabs">
			{foreach item=item key=key from=$langs}
			<li{if $item.id eq $lang_id} class="active"{/if}><a href="{$site_url}admin/themes/view_installed/{$theme.id}/{$item.id}">{$item.name}</a></li>
			{/foreach}
		</ul>
		&nbsp;
	</div>	
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_logo_upload' gid='themes'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_logo_file' gid='themes'}: </div>
			<div class="v">
				<input type="file" value="" name="logo">
				<br><br>
				<img src="{$theme.logo_url}" width="{$theme.logo_width}" height="{$theme.logo_height}" />
			</div>
			{if $theme.logo}
			<div class="row zebra">
				<div class="h">{l i='field_logo_delete' gid='themes'}: </div>
				<div class="v"><input type="checkbox" name="logo_delete" value="1"></div>
			</div>
			{/if}
		</div>
		<div class="row ">
			<div class="h">{l i='field_mini_logo_file' gid='themes'}: </div>
			<div class="v">
				<input type="file" value="" name="mini_logo">
				<br><br>
				<img src="{$theme.mini_logo_url}" width="{$theme.mini_logo_width}" height="{$theme.mini_logo_height}" />
			</div>
			{if $theme.mini_logo}
			<div class="row">
				<div class="h">{l i='field_logo_delete' gid='themes'}: </div>
				<div class="v"><input type="checkbox" name="mini_logo_delete" value="1"></div>
			</div>
			{/if}
		</div>
	</div>	
	
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/themes/installed_themes/{$theme.type}">{l i='btn_cancel' gid='start'}</a>

</form>
{include file="footer.tpl"}