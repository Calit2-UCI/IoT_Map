{strip}
<div class="services">
	{assign var=is_inactive_services value=0}
	{l i='service_activate_confirm' gid='services' assign=data_alert_lng}
	{foreach from=$services_block_services item=item}
		{if !$item.is_active}{assign var=is_inactive_services value=1}{/if}
		<div class="service{if !$item.is_active} inactive hide-always{/if}">
			<div class="table">
				<dl>
					<dt class="view">
						<div class="h2">{if $item.service.name_lang_gid}{l i=$item.service.name_lang_gid gid='services'}{else}{$item.name}{/if}{if $item.count}&nbsp;({$item.count}){/if}</div>
						<div class="t-2">
							{if !$item.is_active}
								<div class="pb5">
									{l i='activated' gid='services'}:&nbsp;{$item.date_modified|date_format:$services_block_date_formats.date_format}
									{if $item.date_expires}<br>
										{if $item.is_expired}
											{l i='expires' gid='services'}:
											{l i='expired' gid='services'}:
										{else}
											{l i=expires gid=services}:
										{/if}
										&nbsp;{$item.date_expires|date_format:$services_block_date_formats.date_format}
									{/if}
								</div>
							{/if}
							{if $item.service.description_lang_gid}
								<div>
									<span>{l i=$item.service.description_lang_gid gid='services'}</span>
								</div>
							{elseif $item.description}
								<div>
									<span>{$item.description}</span>
								</div>
							{/if}
							{foreach from=$item.service.template.data_admin_array key='setting_gid' item='setting_options'}
								<div>
									<span>{l i=$setting_options.name_lang_gid gid='services'}: {$item.service.data_admin_array[$setting_gid]}</span>
								</div>
							{/foreach}
						</div>
					</dt>
					<dt class="righted">
						{if $item.is_active}
							<input type="button" onclick="
								var href='{$site_url}services/user_service_activate/{$item.id_user}/{$item.id}/{$item.service_gid}';
								var alert='{$data_alert_lng|escape}<br>{$item.name|escape}<br>({$item.description|escape:javascript})';
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

	{if $is_inactive_services}
		<div><span class="a" onclick="$(this).parents('.services').find('.service.inactive').toggleClass('hide-always');">{l i='show_hide_inactive_services' gid='services'}</span></div>
	{/if}
</div>
{/strip}
