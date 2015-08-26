{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_countries_menu'}
<div class="actions">&nbsp;</div>
{$countries_list_length}

<div class="filter-form">
	<div class="install_main_window">
		<div class="pad">
			<h3>{l i='countries_install_progress' gid='countries'}</h3>
            <div class="bar-level1" id="regions_bar"><div class="bar">0%</div></div>
			<br>
			<div class="bar-level2" id="overall_bar"><div class="bar">0%</div></div>
			<br>
			<div id="region_reload">
				{counter start=1 print=false name=countries_counter assign=counter_country}
				{foreach value=c_item key=c_key from=$countries_list}
					<div class="country-block">
					<span id="country_{$counter_country}">{$c_item.name}</span><br>
					{counter name=countries_counter print=false assign=counter_country}
					{assign var='code' value=$c_key}
					{counter start=1 print=false name=regions_counter assign=regions_counter}
					{foreach value=item key=key from=$regions_list[$code]}
						<div class="region-block">
						<span id="region_{$item.country_code}_{$regions_counter}">{$item.name}</span><br>
						{counter print=false name=regions_counter assign=regions_counter}
						</div>
					{/foreach}
					</div>
				{/foreach}
				<div class="clr"></div>
			</div>
		
		</div>

	</div>
</div>
<div class="btn hide" id="back_btn"><div class="l"><a href="{$back_link}">{l i='btn_back' gid='start'}</a></div></div>
<script>
{literal}
var country_install;
$(function(){
	country_install=new adminCountriesSelected({
		siteUrl: '{/literal}{$site_url}{literal}',
		countries: '{/literal}{$countries_list_json}{literal}',
		regions_list: '{/literal}{$full_list}{literal}',
	});
	country_install.start_country_install();
});
{/literal}
</script>
<div class="clr"></div>

{include file="footer.tpl"}
