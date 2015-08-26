{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_place_change' gid='banners'}{else}{l i='admin_header_place_add' gid='banners'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_place_name' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_place_keyword' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.keyword}" name="keyword" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_place_sizes' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.width}" name="width" class="short"> X <input type="text" value="{$data.height}" name="height" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_place_rotate_time' gid='banners'}: <br></div>
			<div class="v"><input type="text" value="{$data.rotate_time}" name="rotate_time" class="short"> sec. <i>(0 - {l i='no_rotation' gid='banners'})</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_place_in_rotation' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.places_in_rotation}" name="places_in_rotation" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_access' gid='banners'}: </div>
			<div class="v">
				<select name="access" class="middle">
				<option value="0">...</option>
				{foreach item=item key=key from=$place_access_lang.option}<option value="{$key}"{if $key eq $data.access} selected{/if}>{$item}</option>{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_groups' gid='banners'}: </div>
			<div class="v">
				{foreach item=item key=key from=$groups}
					<div><input type="checkbox" name="place_groups[]" value="{$item.id}" id="pg_{$item.id}" {if $item.selected eq '1'}checked{/if}> <label for="pg_{$item.id}">{$item.name}</label></div>
				{/foreach}
				&nbsp;
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/banners/places_list">{l i='btn_cancel' gid='start'}</a>
</form>
<script type="text/javascript">{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
{/literal}</script>

{include file="footer.tpl"}