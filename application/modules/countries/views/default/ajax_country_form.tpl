<div class="load_content_controller">
	<h1>
	{if $type eq 'country'}{l i='header_country_select' gid='countries'}
	{elseif $type eq 'region'}{l i='header_region_select' gid='countries'}
	{elseif $type eq 'city'}{l i='header_city_select' gid='countries'}
	{/if}</h1>
	<div class="inside">
	{if $type eq 'region'}<div class="crumb">{$data.country.name}</div>
	{elseif $type eq 'city'}<div class="crumb">{$data.country.name} > {$data.region.name}</div>
	{/if}
	{if $type eq 'city'}<input type="text" id="city_search" class="controller-search">{/if}
		<ul class="controller-items" id="country_select_items"></ul>
	
		<div class="controller-actions">
			<div id="city_page" class="fright"></div>
			<div>
			{if $type eq 'region'}<a href="#" id="country_select_back" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i>{l i='link_select_another_country' gid='countries'}</i></a>
			{elseif $type eq 'city'}<a href="#" id="country_select_back" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i>{l i='link_select_another_region' gid='countries'}</i></a>
			{/if}
			</div>
			<div class="fright">
				<a href="javascript:void(0);" id="country_select_close" class="btn-link"><i class="icon-arrow-left icon-big edge"></i><i>{l i='link_close' gid='countries'}</i></a>
			</div>
		</div>

	</div>
</div>