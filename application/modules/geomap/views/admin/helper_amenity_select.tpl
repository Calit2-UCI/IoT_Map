<div id="amenity_select_{$amenity_helper_data.rand}" class="controller-select">

	{if $amenity_helper_data.max eq '1'}
	<span id="amenity_text_{$amenity_helper_data.rand}">{$amenity_helper_data.selected_data}</span>
	<a href="#" id="amenity_open_{$amenity_helper_data.rand}">{l i='link_select_amenity' gid='geomap'}</a>
	{else}
	<a href="#" id="amenity_open_{$amenity_helper_data.rand}">{l i='link_select_amenities' gid='geomap'}</a>
	<span id="amenity_list_{$amenity_helper_data.rand}">{$amenity_helper_data.selected_text}</span>
	<i>(<span id="amenity_text_{$amenity_helper_data.rand}">{$amenity_helper_data.selected_data}</span> from {$amenity_helper_data.max} selected)</i>
	{/if}

	{foreach item=item key=key from=$amenity_helper_data.selected_all}
	<input type="hidden" name="{if $amenity_helper_data.var}{$amenity_helper_data.var}{else}id_amenity{/if}{if $amenity_helper_data.max > 1}[]{/if}" value="{$key}" id="sel_{$amenity_helper_data.rand}_{$key}" >
	{/foreach}

</div>
{js module='geomap' file='geomap-amenity-select.js'}
<script>{literal}
{/literal}{if $amenity_helper_data.var_js_name}var {$amenity_helper_data.var_js_name};{/if}{literal}
$(function(){
	{/literal}{if $amenity_helper_data.var_js_name}{$amenity_helper_data.var_js_name} = {/if}{literal}new geomapAmenitySelect({
		{/literal}
		siteUrl: '{$site_url}',
		rand: '{$amenity_helper_data.rand}',
		{if $amenity_helper_data.var}hidden_name: '{$amenity_helper_data.var}',{/if}
		amenities: {$amenity_helper_data.selected_all_json},
		raw_data: {$amenity_helper_data.raw_data_json},
		max: {$amenity_helper_data.max},
		gid: '{$amenity_helper_data.gid}',
		output: '{$amenity_helper_data.output}'
		{literal}
	});
});
{/literal}</script>
