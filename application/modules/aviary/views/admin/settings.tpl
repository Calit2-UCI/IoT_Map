{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n250">
		<div class="row header">{l i='admin_header_settings_edit' gid='aviary'}</div>
		<div class="row">
			<div class="h">{l i='field_used' gid='aviary'}:</div>
			<div class="v">
				<input type="hidden" name="data[used]" value="0">
				<input type="checkbox" name="data[used]" value="1" {if $data.used}checked{/if} id="aviary_used">
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_api_key' gid='aviary'}:</div>
			<div class="v">
				<input type="text" name="data[api_key]" value="{$data.api_key|escape}" id="aviary_api_key" class="middle" {if !$data.used}disabled{/if}>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/menu/add_ons_items">{l i='btn_cancel' gid='start'}</a>
</form>
<script>{literal}
	$(function(){
		$('#aviary_used').bind('click', function(){
			if(this.checked){
				$('#aviary_api_key').prop('disabled', false);
			}else{
				$('#aviary_api_key').prop('disabled', true);
			}
		});
	});
{/literal}</script>

{include file="footer.tpl"}
