{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">
			{if $data.id}
				{l i='admin_header_reason_change' gid='contact_us'}
			{else}
				{l i='admin_header_reason_add' gid='contact_us'}
			{/if}
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='contact_us'}: </div>
			<div class="v">
				<input type="text" name="name[{$cur_lang}]" class="middle"
					   value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.names[$cur_lang]}{/if}">
				{if $languages_count > 1}
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='contact_us'}</a><br>
					<div id="name_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
						<input type="text" name="name[{$lang_id}]"
							   value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{$data.names[$lang_id]}{/if}">&nbsp;|&nbsp;{$item.name}<br>
						{/if}{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_mails' gid='contact_us'}: </div>
			<div class="v">
				<input type="text" value="{$data.mails_string}" name="mails" class="long"><br>
				<i>{l i='field_mails_text' gid='contact_us'}</i>
			</div>
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/contact_us">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script >{literal}
function showLangs(divId){
	$('#'+divId).slideToggle();
}

{/literal}</script>
{include file="footer.tpl"}