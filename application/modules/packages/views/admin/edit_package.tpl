{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_package_change' gid='packages'}{else}{l i='admin_header_package_add' gid='packages'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='packages'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle_long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='packages'}: </div>
			<div class="v">
				<input type="text" class="middle_long" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]">
				{if $languages_count > 1}
				&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='services'}</a><br>
				<div id="name_langs" class="hide p-top2">
					{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
					<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{$data.name}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
					{/if}{/foreach}
				</div>
				{/if}
			</div>
		</div>
		
		<div class="row">
			<div class="h">{l i='field_price' gid='packages'}: </div>
			<div class="v"><input type="text" value="{$data.price}" name="price" class="short"> {block name='currency_format_output' module='start'}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_available_days' gid='packages'}: </div>
			<div class="v"><input type="text" value="{$data.available_days}" name="available_days" class="short"></div>
		</div>
		
		<div class="row">
			<div class="h">{l i='field_pay_type' gid='services'}: </div>
			<div class="v"><select name="pay_type" class="middle_long">
				{foreach item=item key=key from=$pay_type_lang.option}<option value="{$key}"{if $key eq $data.pay_type} selected{/if}>{$item}</option>{/foreach}
			</select></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_status' gid='packages'}: </div>
			<div class="v"><input type="checkbox" value="1" {if $data.status}checked{/if} name="status"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/packages/index">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
function showLangs(divId){
	$('#'+divId).slideToggle();
}

{/literal}</script>

{include file="footer.tpl"}