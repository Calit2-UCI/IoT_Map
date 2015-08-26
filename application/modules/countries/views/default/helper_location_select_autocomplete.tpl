<input name="region_name" type="text" id="country_text_{$country_helper_data.rand}"
	   value="{$country_helper_data.location_text}" 
	   autocomplete="off" 
	   placeholder="{$country_helper_data.placeholder}">
<span id="country_msg_{$country_helper_data.rand}" 
	  class="hide pginfo msg region_name info">{l i='text_select_from_list' gid='countries'}</span>
<input name="{$country_helper_data.var_country_name}" type="hidden"
	   id="country_hidden_{$country_helper_data.rand}" 
	   value="{$country_helper_data.country.code}">
<input name="{$country_helper_data.var_region_name}" type="hidden"
	   id="region_hidden_{$country_helper_data.rand}" 
	   value="{$country_helper_data.region.id}">
<input name="{$country_helper_data.var_city_name}" type="hidden"
	   id="city_hidden_{$country_helper_data.rand}" 
	   value="{$country_helper_data.city.id}">
<script type='text/javascript'>
{*if $country_helper_data.var_js_name}var {$country_helper_data.var_js_name};{/if*}
{literal}
$(function(){
	loadScripts(
		"{/literal}{js module='countries' file='location-autocomplete.js' return='path'}{literal}",
		function(){
			new locationAutocomplete({
				siteUrl: '{/literal}{$site_url}{literal}',
				rand: '{/literal}{$country_helper_data.rand}{literal}',
				id_country: '{/literal}{$country_helper_data.country.code}{literal}',
				id_region: '{/literal}{$country_helper_data.region.id}{literal}',
				id_city: '{/literal}{$country_helper_data.city.id}{literal}'
			});
		},
		'region_{/literal}{$country_helper_data.rand}{literal}'
	);
});
{/literal}</script>