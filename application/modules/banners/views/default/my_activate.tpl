{include file="header.tpl"}

<div class="content-block">

	<h1>{l i='header_my_banner_activate' gid='banners'}</h1>
	{if $service_data.status}<form method="post">{/if}
	<div class="content-value">
		<table class="list" id="positions">
		<tr>
			<th>{l i='field_page' gid='banners'}</th>
			<th class="w150">{l i='field_free_places' gid='banners'}</th>
			<th class="w150">{l i='field_place_price' gid='banners'}</th>
			<th class="w150">{l i='field_period' gid='banners'}</th>
			<th class="w150">{l i='field_place_numbers' gid='banners'}</th>
		</tr>
		{foreach from=$groups item=item}
		{if $item.status}
		<tr>
			<td>{$item.name}</td>
			<td><span id="free_pos_{$item.id}" int="{$item.free_positions}">{$item.free_positions}</td>
			<td><span id="price_{$item.id}" float="{$item.price}">{block name='currency_format_output' module='start' value=$item.price}</span> </td>
			<td>{$period}</td>
			<td class="centered"><input type="text" class="short" value="{$item.user_positions}" id="used_pos_{$item.id}" name="used_position[{$item.id}]"></td>
		</tr>
		{/if}
		{/foreach}
		</table>
		<br>
		<b>{l i='field_total_price' gid='banners'}: <span id="final_price">0</span>{block name='currency_format_output' module='start'}</b> &nbsp;&nbsp;&nbsp;&nbsp;
	</div>
	{if $service_data.status}
		<div class="b">
			<input type="submit" class='btn' value="{l i='link_banner_activate' gid='banners' type='button'}" name="btn_activate">
		</div>
		</form>
	{/if}
	<div class="b outside">
		<a href="{$site_url}users/account/banners" class="btn-link"><i class="fa-arrow-left icon-big edge hover"></i><i>{l i='link_back_to_my_banners' gid='banners'}</i></a>
	</div>
</div>
<div class="clr"></div>

<script type='text/javascript'>{literal}
	var banActive;
	loadScripts(
		"{/literal}{js module='banners' file='banner-activate.js' return='path'}{literal}",
		function(){
			banActive = new BannerActivate;
		},
		'banActive'
	);
</script>{/literal}

{include file="footer.tpl"}
