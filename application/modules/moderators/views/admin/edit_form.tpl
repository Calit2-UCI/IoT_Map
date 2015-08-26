{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_moderators_change' gid='moderators'}{else}{l i='admin_header_moderators_add' gid='moderators'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_nickname' gid='moderators'}: </div>
			<div class="v"><input type="text" value="{$data.nickname}" name="nickname" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_email' gid='moderators'}: </div>
			<div class="v"><input type="text" value="{$data.email}" name="email" class="middle"></div>
		</div>
		{if $data.id}
		<div class="row">
			<div class="h">{l i='field_change_password' gid='moderators'}: </div>
			<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field" class="middle"></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_password' gid='moderators'}: </div>
			<div class="v"><input type="password" value="" name="password" id="pass_field" class="middle" {if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_repassword' gid='moderators'}: </div>
			<div class="v"><input type="password" value="" name="repassword" id="repass_field" class="middle" {if $data.id}disabled{/if}></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='moderators'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='moderators'}: </div>
			<div class="v"><textarea name="description" class="long">{$data.description}</textarea></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_permissions' gid='moderators'}: </div>
			<div class="v">
				{foreach item=module from=$methods key=key}
				<div class="permissions">
					<input type="checkbox" name="permission_data[{$key}][{$module.main.method}]" value=1 {if $module.main.checked}checked{/if} id="pd_{$key}"> <label for="pd_{$key}"><b>{$module.module.module_name}</b></label><br>
					<ul>
					{foreach item=item from=$module.methods key=index}
					{if $index !== 'main'}<li><input type="checkbox" name="permission_data[{$key}][{$item.method}]" value=1 {if $item.checked}checked{/if} id="pd_{$key}_{$item.method}" {if !$module.main.checked}disabled{/if}> <label for="pd_{$key}_{$item.method}">{$item.name}</label></li>{/if}					
					{/foreach}					
					</ul>	
				</div>			
				{/foreach}
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/moderators">{l i='btn_cancel' gid='start'}</a>
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
	$('.permissions > input[type=checkbox]').bind('click', function(){
		if($(this).is(':checked')){
			$(this).parent().find('input[id^='+$(this).attr('id')+'_]').removeAttr("disabled");
		}else{
			$(this).parent().find('input[id^='+$(this).attr('id')+'_]').attr('disabled', 'disabled');
		}
	});
});
{/literal}</script>

{include file="footer.tpl"}