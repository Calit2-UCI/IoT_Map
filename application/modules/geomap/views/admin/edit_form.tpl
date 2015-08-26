{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_geomap_change' gid='geomap'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_title' gid='geomap'}: </div>
			<div class="v">{$data.name}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_regkey' gid='geomap'}: </div>
			<div class="v"><input type="text" value="{$data.regkey|escape}" name="regkey" class="long"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/geomap">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
