{include file="header.tpl" load_type='ui'}
{js module='banners' file='admin_banner.js'}
<script type="text/javascript">{literal}
	var admin_banners;
	$(function(){
		$("div.row:odd").addClass("zebra");
		admin_banners =  new AdminBanners({
			siteUrl: '{/literal}{$site_url}{literal}',
			banner_id: '{/literal}{$data.id}{literal}',
			init_banner_form: true
		});
	});
{/literal}</script>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_banner_change' gid='banners'}{else}{l i='admin_header_banners_add' gid='banners'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='banners'}: </div>
			<div class="v"><input type="text" value="{$data.name|escape}" name="name" class="long"></div>
		</div>
		{if $data.user_id ne 0}
		<div class="row">
			<div class="h">{l i='field_user' gid='banners'}: </div>
			<div class="v">{$data.user.output_name}</div>
		</div>
		{else}
		<div class="row">
			<div class="h">{l i='field_status' gid='banners'}: </div>
			<div class="v"><input type="checkbox" value="1" name="status" {if $data.status}checked{/if}></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='banner_place' gid='banners'}: </div>
			<div class="v">
			{if $data.user_id eq 0}
				<select id="banner_place" name="banner_place_id" class="long">
					<option value="" {if $data.banner_place_id}selected{/if}>...</option>
					{foreach from=$places item=place}
					<option value="{$place.id}" {if $place.id eq $data.banner_place_id}selected{/if}>{$place.name} ({$place.width}x{$place.height})</option>
					{/foreach}
				</select>
			{else}
				<input type="hidden" name='banner_place_id' value="{$data.banner_place_id|escape}">
				{if $data.banner_place_id}
					{foreach from=$places item=place}
						{if $place.id eq $data.banner_place_id}
							{$place.name} ({$place.width}x{$place.height})
						{/if}
					{/foreach}
				{else}
				...
				{/if}
			{/if}
			</div>
		</div>

		<div id="banner_groups">{$banner_place_block}</div>

		<div class="row">
			<div class="h">{l i='field_type' gid='banners'}: </div>
			<div class="v">
			{if $data.user_id eq 0}
				<select name="banner_type" id="banner_type" class="long">
				<option value="0">...</option>
				{foreach item=item key=key from=$banner_type_lang.option}<option value="{$key}"{if $key eq $data.banner_type} selected{/if}>{$item}</option>{/foreach}
				</select>
			{else}
				<input type="hidden" name='banner_type' value="{$data.banner_type|escape}">
				{foreach item=item key=key from=$banner_type_lang.option}
					{if $key eq $data.banner_type}{$item}{/if}
				{/foreach}
			{/if}
			</div>
		</div>

		<div id="second_form">{$banner_type_block}</div>

		<div id="result"></div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/banners{if $data.user_id}/index/user{/if}">{l i='btn_cancel' gid='start'}</a>
</form>


{include file="footer.tpl"}
