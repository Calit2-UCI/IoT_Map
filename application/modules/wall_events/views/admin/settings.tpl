{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_wall_events_menu'}
{strip}
<div class="actions">&nbsp;</div>
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='wall_events_settings_module' gid='wall_events'}</div>
		<div class="row">
			<div class="h">{l i='wall_events_live_period_field' gid='wall_events'}:</div>
			<div class="v">
				<input type="text" name="live_period" value="{$settings_data.live_period}" class="short" id="live_period">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='wall_events_events_max_count_field' gid='wall_events'}:</div>
			<div class="v">
				<input type="text" name="events_max_count" value="{$settings_data.events_max_count}" class="short" id="events_max_count">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/wall_events">{l i='btn_cancel' gid='start'}</a>
</form>
<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{/strip}
{include file="footer.tpl"}