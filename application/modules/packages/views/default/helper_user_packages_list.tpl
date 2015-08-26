{strip}
<div class="packages">
	{assign var=is_inactive_packages value=0}
	{l i='service_activate_confirm' gid='services' assign=data_alert_lng}
	{section loop=$block_user_packages name=s}
		{if !$block_user_packages[s].is_active}{assign var=is_inactive_packages value=1}{/if}
		<dl class="user-package{if !$block_user_packages[s].is_active} inactive hide-always{/if}">
			<div class="h2">{$block_user_packages[s].package_info.name}</div>
			<div class="h3 p5">{l i='package_active_till' gid='packages'}:&nbsp;{$block_user_packages[s].till_date|date_format:$block_user_packages_date_formats.date_time_format}</div>
			<div class="services">
				{foreach from=`$block_user_packages[s].user_services` item='service'}
					<div class="service{if !$service.count || !$service.status} inactive{/if}">
						<div class="table">
							<dl>
								<dt class="view">
									<h2 class="m0">{$service.name}&nbsp;({$service.count})</h2>
									<div class="t-2">
										{if !$service.is_active}<div class="pb5">{$service.date_modified|date_format:$block_user_packages_date_formats.date_format}</div>{/if}
										{if $service.description}<div><span>{$service.description}</span></div>{/if}
										{foreach from=$service.service.template.data_admin_array key='setting_gid' item='setting_options'}
											<div><span>{$setting_options.name}: {$service.service.data_admin[$setting_gid]}</span></div>
										{/foreach}
									</div>
								</dt>
								<dt class="righted">
									{if $block_user_packages[s].is_active && $service.count && $service.status}
										<input type="button" onclick="
											var href='{$site_url}services/user_service_activate/{$block_user_packages[s].id_user}/{$service.id}/{$service.service_gid}';
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
									{/if}
								</dt>
							</dl>
						</div>
					</div>
				{/foreach}
			</div>
		</dl>
	{/section}

	{if $is_inactive_packages}
		<div><span class="a" onclick="$(this).parents('.packages').find('.user-package.inactive').toggleClass('hide-always');">{l i='show_hide_inactive_packages' gid='services'}</span></div>
	{/if}
</div>
{/strip}
