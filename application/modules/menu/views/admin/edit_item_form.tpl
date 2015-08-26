{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_menu_item_change' gid='menu'}{else}{l i='admin_header_menu_item_add' gid='menu'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_menu_item_gid' gid='menu'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_menu_item_link' gid='menu'}: </div>
			<div class="v">
				<input type="radio" value="out" name="link_type" id="link_type_out"{if $data.link_out} checked{/if}><input type="text" value="{$data.link_out}" name="link_out" class="long_long" onclick="javascript: check('out');"></label><br><br>
				<input type="radio" value="in" name="link_type" id="link_type_in"{if $data.link_in} checked{/if}>{$site_url} <input type="text" value="{$data.link_in}" class="middle" name="link_in" onclick="javascript: check('in');">
			</div>
		</div>
		{foreach item=item key=lang_id from=$languages}
		<div class="row">
			<div class="h">{l i='field_menu_item_value' gid='menu'}({$item.name}): </div>
			<div class="v">
				<input type="text" class="middle" name="langs[{$lang_id}]" value="{$data.langs[$lang_id]}">
			</div>
		</div>
		{/foreach}
		{if $indicators}
		<div class="row">
			<div class="h">{l i='field_indicator' gid='menu'}: </div>
			<div class="v">
				<select name="indicator_gid" id="indicator" class="middle">
					<option value="0">{l i='no_indicator' gid='menu'}</option>
					{foreach item=indicator key=indicator_gid from=$indicators}
						{if $indicator.name}
						<option value="{$indicator_gid}"{if $data.indicator_gid eq $indicator_gid} selected="selected"{/if}>{$indicator.name}</option>
						{/if}
					{/foreach}
				</select>
			</div>
		</div>
		{/if}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/menu/items/{$menu_id}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
function check(type){
	$('#link_type_'+type).attr('checked', 'checked');
}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>
{include file="footer.tpl"}