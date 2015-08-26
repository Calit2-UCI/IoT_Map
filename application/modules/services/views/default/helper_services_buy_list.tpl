{strip}
<div class="services">
	{l i='service_activate_confirm' gid='services' assign=data_alert_lng}
	{foreach from=$services_block_services item=item}
		<div class="service">
			<div class="table">
				<dl>
					<dt class="view">
						<div class="h2">{$item.name}{if $item.price} - {block name='currency_format_output' module='start' value=$item.price}{/if}</div>
						<div class="t-2">
							{if $item.description}<span>{$item.description}</span>{/if}
							{foreach from=$item.template.data_admin_array key='setting_gid' item='setting_options'}
								<div><span>{$setting_options.name}: {$item.data_admin[$setting_gid]}</span></div>
							{/foreach}
						</div>
					</dt>
					<dt class="righted">
						{if $item.price || $item.template.price_type != 1}
							<input type="button" onclick="locationHref('{seolink module='services' method='form' gid=$item.gid}');" value="{l i='btn_buy_now' gid='services'}" />
						{else}
							<input type="button" onclick="
								var href='{$site_url}services/user_service_activate/{$user_id}/0/{$item.gid}';
								var alert='{$data_alert_lng|escape}<br>{$item.name|escape}<br>({$item.description|escape})';
								{literal}
								if(!parseInt('{/literal}{$item.template.alert_activate}{literal}')) {
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
						{/if}
					</dt>
				</dl>
			</div>
		</div>
	{foreachelse}
		{l i='no_services' gid='services'}
	{/foreach}
</div>
{/strip}
