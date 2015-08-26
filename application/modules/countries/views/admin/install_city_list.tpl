{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_countries_menu'}
<div class="actions">&nbsp;</div>
{$regions_list_length}

<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3>{$country.name}: {l i='cities_install_progress' gid='countries'}</h3>
			<div class="bar-level1" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<div id="region_reload">
				<div class="region-block">
				{counter start=0 print=false assign=counter}
				{math assign='divby' equation="ceil(x/4)" x=$regions_list|@count}
				{foreach item=item key=key from=$regions_list}
				{if $counter > 0}{if $counter is div by $divby}</div><div class="region-block">{/if}{/if}
				<span id="region_{$counter}">{$item.name}</span><br>
				{counter print=false assign=counter}
				{/foreach}
				</div>
				<div class="clr"></div>
			</div>
		
		</div>

	</div>
</div>
<div class="btn hide" id="back_btn"><div class="l"><a href="{$back_link}">{l i='btn_back' gid='start'}</a></div></div>

<div class="clr"></div>

{literal}
<script>
var country_install;
$(function(){
	country_install=new adminCountries({
		siteUrl: '{/literal}{$site_url}{literal}',
		regions: [{/literal}{foreach item=item key=key from=$regions}{if $key}, {/if}'{$item}'{/foreach}{literal}],
		country_code: '{/literal}{$country.code}{literal}'
	});

	country_install.start_city_install();
});
</script>
{/literal}

{include file="footer.tpl"}