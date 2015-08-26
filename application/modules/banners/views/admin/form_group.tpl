{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_group_change' gid='banners'}{else}{l i='admin_header_group_add' gid='banners'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_group_name' gid='banners'}: </div>
			<div class="v">
				{*<input type="text" value="{$data.name}" name="name">*}
				<input type="text" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]" class="middle">
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
			<div class="h">{l i='field_group_gid' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.gid|escape}" name="gid" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_group_price' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.price|escape}" name="price" class="short"> {block name='currency_format_output' module='start'}</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/banners/groups_list">{l i='btn_cancel' gid='start'}</a>
</form>
<script type="text/javascript">{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
	function showLangs(divId){
		$('#'+divId).slideToggle();
	}
{/literal}</script>

{include file="footer.tpl"}
