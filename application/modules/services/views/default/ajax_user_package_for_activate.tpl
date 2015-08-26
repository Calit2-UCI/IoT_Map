{strip}
<div class="content-block load_content">
	<h1>{l i='header_use_services' gid='services'}</h1>

	<div class="inside">
		{l i='service_activate_confirm' gid='services' assign=data_alert_lng}

		{if $service.is_free_status || $block_data.user_services}
			{if $service.is_free_status}
				<div class="h3">{l i='service_activate_free_text' gid='services'}</div>
				<div class="service{if $block_data.user_services} mb10{/if}">
					<div class="table">
						<dl>
							<dt class="view">
								<div class="h2">{$service.name}</div>
								<div class="t-2">
									{foreach from=$service.template.data_admin_array key='setting_gid' item='setting_options'}
										<div><span>{$setting_options.name}: {$service.data_admin[$setting_gid]}</span></div>
									{/foreach}
								</div>
							</dt>
							<dt class="righted">
								<input type="button" onclick="
									var href='{$site_url}services/user_service_activate/{$user_session_data.user_id}/0/{$service.gid}';
									var alert='{$data_alert_lng|escape}<br>{$service.name|escape}<br>({$service.description|escape})';
									{literal}
									if(!parseInt('{/literal}{$service.template.alert_activate}{literal}')) {
										locationHref(href);
									} else {
										alerts.show({
											text: alert.replace(/<br>/g, '\n'),
											type: 'confirm',
											ok_callback: function(){
												locationHref(href);
											}
										});
									}{/literal}" value="{l i='btn_activate' gid='services'}" />
							</dt>
						</dl>
					</div>
				</div>
				{if $block_data.user_services}
					<div class="centered h3">{l i='or' gid='start'}</div>
				{/if}
			{/if}

			{if $block_data.user_services}
				<div class="h3">{l i='service_spend_text' gid='services'}</div>
				<form method="POST" action="" id="ability_form" class="oya {if $service.is_free_status}maxh150{else}maxh250{/if}">
					{foreach from=$block_data.user_services item=item}
						<div class="service">
							<div class="table">
								<dl>
									<dt class="view">
										<div class="h2">{if $item.package_name}{$item.package_name} : {/if}{if $item.service.name}{$item.service.name}{else}{$item.name}{/if}{if $item.count}&nbsp;({$item.count}){/if}</div>
										{if $item.package_till_date}<div>{l i='package_active_till' gid='packages'}:&nbsp;{$item.package_till_date|date_format:$block_data.date_time_format}</div>{/if}
										<div class="t-2">
											{foreach from=$item.service.template.data_admin_array key='setting_gid' item='setting_options'}
												<div><span>{$setting_options.name}: {$item.service.data_admin[$setting_gid]}</span></div>
											{/foreach}
										</div>
									</dt>
									<dt class="righted">
										<input type="button" data-value="{$item.id}" data-alert="{if $item.template.alert_activate}{$data_alert_lng|escape:html}<br>{$item.name|escape:html}<br>{$item.description|escape:html}<br>{$item.alert|escape:html}{/if}" value="{l i='btn_activate' gid='services'}" />
									</dt>
								</dl>
							</div>
						</div>
					{/foreach}
				</form>
			{/if}
		{else}
			<p>{l i='service_buy_text' gid='services'}</p>
			<a href="{seolink module='services' method='index' gid=$template.gid}" target="blank">{l i='service_link_buy' gid='services'}</a>
		{/if}
	</div>
	<div class="clr"></div>
</div>
{/strip}
