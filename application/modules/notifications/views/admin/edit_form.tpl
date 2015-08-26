{include file="header.tpl"}


<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_template_edit' gid='notifications'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_template_gid' gid='notifications'}: </div>
			<div class="v">{if $allow_edit}<input type="text" value="{$data.gid}" name="gid" class="middle">{else}{$data.gid}{/if}</div>
		</div>

		<div class="row">
			<div class="h">{l i='field_notification_name' gid='notifications'}: </div>
			<div class="v">
				{assign var="lang_gid" value=$data.lang_gid}
				{assign var="name_i" value=$data.name_i}
				<input type="text" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{l i=$data.name_i gid='notifications' lang=$cur_lang}{/if}" name="langs[{$cur_lang}]" class="middle">
				{if $languages_count > 1}
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='notifications'}</a><br>
					<div id="name_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
								<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{l i=$data.name_i gid='notifications' lang=$lang_id}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
						{/if}{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_send_type' gid='notifications'}: </div>
			<div class="v">{if $allow_edit}
				<select name="send_type" class="long">
					<option value="que" {if $data.send_type eq 'que'}selected{/if}>{l i='field_send_type_que' gid='notifications'}</option>
					<option value="simple" {if $data.send_type eq 'simple'}selected{/if}>{l i='field_send_type_simple' gid='notifications'}</option>
				</select>
				{elseif $data.send_type eq 'que'}{l i='field_send_type_que' gid='notifications'}
					{elseif $data.send_type eq 'simple'}{l i='field_send_type_simple' gid='notifications'}
						{/if}
						</div>
					</div>
					<div class="row">
						<div class="h">{l i='field_default_template' gid='notifications'}: </div>
						<div class="v">
							<select name="id_template_default" class="long">
						{foreach item=item from=$templates}<option value="{$item.id}" {if $data.id_template_default eq $item.id}selected{/if}>{$item.name}</option>{/foreach}
					</select>
				</div>
			</div>

			<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<a class="cancel" href="{$site_url}admin/notifications/index">{l i='btn_cancel' gid='start'}</a>
		</div>
	</form>
	<script>
		{literal}
function showLangs(divId){
	$('#'+divId).slideToggle();
}

function openTab(id, object){
	$('#edit_divs > div.tab').hide();
	$('#'+id).show();
	$('#edit_tabs > li').removeClass('active');
	$(object).addClass('active');
}

		{/literal}
	</script>
	<div class="clr"></div>
	{include file="footer.tpl"}