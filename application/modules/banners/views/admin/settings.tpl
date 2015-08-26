{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_banners_menu'}
<div class="actions">&nbsp;</div>

<form method="post" action="" name="moder_sattings_save">
	<div class="edit-form n150">
		<div class="row">
			<div class="h"><label for="period">{l i='field_period' gid='banners'}</label>: </div>
			<div class="v"><input type="text" name="period" class="short" value="{$data.period|escape}"></div>
		</div>
		<div class="row">
			<div class="h"><label for="moderation_send_mail">{l i='field_moderation_send_mail' gid='banners'}</label>: </div>
			<div class="v">
				<input type="hidden" name="moderation_send_mail" value="0"> 
				<input type="checkbox" name="moderation_send_mail" value="1" id="moderation_send_mail" {if $data.moderation_send_mail}checked="checked"{/if}> 
				&nbsp;&nbsp;
				{l i='field_admin_moderation_emails' gid='banners'}
				<input type="text" name="admin_moderation_emails" value="{$data.admin_moderation_emails|escape}" id="admin_moderation_emails" class="middle" {if !$data.moderation_send_mail}disabled{/if}> 
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>
<script>{literal}
$(function(){
	$("div.row:not(.hide):even").addClass("zebra");
	$('#moderation_send_mail').bind('change', function(){
		if(this.checked){
			$('#admin_moderation_emails').removeAttr('disabled');
		}else{
			$('#admin_moderation_emails').attr('disabled', 'disabled');
		}
	});
});
{/literal}</script>


{include file="footer.tpl"}
