{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_ausers_change' gid='ausers'}{else}{l i='admin_header_ausers_add' gid='ausers'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_nickname' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.nickname}" name="nickname" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_email' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.email}" name="email" class="middle"></div>
		</div>
		{if $data.id}
		<div class="row">
			<div class="h">{l i='field_change_password' gid='ausers'}: </div>
			<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_password' gid='ausers'}: </div>
			<div class="v"><input type="password" value="" name="password" id="pass_field" class="middle"{if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_repassword' gid='ausers'}: </div>
			<div class="v"><input type="password" value="" name="repassword" class="middle" id="repass_field"{if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='ausers'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name"  class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='ausers'}: </div>
			<div class="v"><textarea name="description" class="long pb2">{$data.description}</textarea></div>
		</div>	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/ausers">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
	$("#pass_change_field").click(function(){
		if(this.checked){
			$("#pass_field").removeAttr("disabled"); $("#repass_field").removeAttr("disabled");
		}else{
			$("#pass_field").attr('disabled', 'disabled'); $("#repass_field").attr('disabled', 'disabled');
		}
	});
});
{/literal}</script>

{include file="footer.tpl"}