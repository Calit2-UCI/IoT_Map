{strip}
{if $headline}
    <div class="expandable">
        <h2 class="h2">{l i='header_packages_index' gid='packages'}</h2>
    </div>
{/if}
<div class="packages">
    {section loop=$block_packages name=s}
		{counter assign=fe_count}
		{if ($fe_count-1)%4 == 0}<dl class="inline-flex">{/if}
			<dt{if $stretch} class="stretch"{/if}>
				<div class="h2">{$block_packages[s].name}</div>
				<ul class="package-services">
					<div class="h3">{l i='field_available_days' gid='packages'}:&nbsp;{$block_packages[s].available_days}</div>
					{foreach from=`$block_packages[s].services_list` item='service'}
						<li class="view">
							{$service.name}&nbsp;({$service.service_count})
							{foreach from=$service.template.data_admin_array key='setting_gid' item='setting_options'}
								<div class="t-1 pl20"><span>{$setting_options.name}:&nbsp;{$service.data_admin[$setting_gid]}</span></div>
							{/foreach}
						</li>
					{/foreach}
				</ul>
				<div class="bottom ptb20 box-sizing">
					<div class="price">{block name='currency_format_output' module='start' value=$block_packages[s].price}</div>
					{if !$hide_btn}
						<div class="center">
							<input type="button" onclick="locationHref('{seolink module='packages' method='package' gid=$block_packages[s].gid}')" value="{l i='btn_buy_now' gid='services'}" />
						</div>
					{/if}
				</div>
			</dt>
		{if $fe_count%4 == 0 || $templatelite.section.s.last}</dl>{/if}
	{/section}
</div>
{/strip}
