{include file="header.tpl" load_type='ui'}

<script type="text/javascript">{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
{/literal}</script>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $wall_events_type.gid}{l i='admin_header_wall_events_type_change' gid='wall_events'}{else}{l i='admin_header_wall_events_type_add' gid='wall_events'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_status' gid='wall_events'}: </div>
			<div class="v"><input type="checkbox" value="1" name="status" {if $wall_events_type.status}checked{/if}/></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_join_period' gid='wall_events'}: </div>
			<div class="v"><input type="text" value="{$wall_events_type.settings.join_period}" class="middle" name="join_period" /></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/wall_events/index/{$data.page}/">{l i='btn_cancel' gid='start'}</a>
</form>


{include file="footer.tpl"}
