{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_im_menu'}
{strip}
<div class="actions">&nbsp;</div>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='im_settings_module' gid='im'}</div>
		
		<div class="row">
			<div class="h">{l i='im_status_field' gid='im'}:</div>
			<div class="v">

				<input type="checkbox" name="status" value="1" {if $settings_data.status}checked{/if} class="short" id="im_status">
			
			</div>
		</div>
		
		<div class="row">
			<div class="h">{l i='im_message_max_chars_field' gid='im'}:</div>
			<div class="v">

				<input type="text" name="message_max_chars" value="{$settings_data.message_max_chars}" class="short" id="message_max_chars">
				
			</div>
		</div>
		
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
</form>
</div>
<script>{literal}
	$("div.row:odd").addClass("zebra");
{/literal}</script>
{/strip}
{include file="footer.tpl"}
