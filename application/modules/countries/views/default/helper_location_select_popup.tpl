<div id="country_select_{$country_helper_data.rand}" class="controller-select">
	<span id="country_text_{$country_helper_data.rand}">
	{if $country_helper_data.country}{$country_helper_data.country.name}{/if}
	{if $country_helper_data.region}, {$country_helper_data.region.name}{/if}
	{if $country_helper_data.city}, {$country_helper_data.city.name}{/if}
	</span>&nbsp;&nbsp;
	<a href="#" id="country_open_{$country_helper_data.rand}">{l i='link_select_region' gid='countries'}</a>
	<input type="hidden" name="{$country_helper_data.var_country_name}" id="country_hidden_{$country_helper_data.rand}" value="{$country_helper_data.country.code}">
	<input type="hidden" name="{$country_helper_data.var_region_name}" id="region_hidden_{$country_helper_data.rand}" value="{$country_helper_data.region.id}">
	<input type="hidden" name="{$country_helper_data.var_city_name}" id="city_hidden_{$country_helper_data.rand}" value="{$country_helper_data.city.id}">
</div>

<script type='text/javascript'>
{if $country_helper_data.var_js_name}var {$country_helper_data.var_js_name};{/if}
{literal}
$(function(){
	loadScripts(
		"{/literal}{js module='countries' file='location-popup.js' return='path'}{literal}", 
		function(){
			{/literal}{if $country_helper_data.var_js_name}{$country_helper_data.var_js_name} = {/if}{literal}new locationPopUp({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$country_helper_data.rand}{literal}',
				id_country: '{/literal}{$country_helper_data.country.code}{literal}',
				id_region: '{/literal}{$country_helper_data.region.id}{literal}',
				id_city: '{/literal}{$country_helper_data.city.id}{literal}',
				select_type: '{/literal}{$country_helper_data.select_type}{literal}'
			});
		},
		{/literal}{if $country_helper_data.var_js_name}{$country_helper_data.var_js_name}{else}''{/if}{literal}
	);
});
{/literal}</script>