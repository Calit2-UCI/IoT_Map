{if $block_memberships}
    {if $headline}
        <div class="expandable">
            <h2 class="h2">{l i='header_memberships_index' gid='memberships'}</h2>
        </div>
    {/if}
	<div class="table-scroll memberships">
		<div>
			<table>
				{*invisible part*}
				<colgroup>
					<col>
					{foreach item=membership from=$block_memberships key=mkey}
						<col{if !empty($membership.is_mine)} class="my"{/if}>
					{/foreach}
				</colgroup>
				<caption></caption>
				<thead>
					<tr>
						{*first row (headers)*}
						<th></th>
						{foreach item=membership from=$block_memberships key=mkey}
							<th>
								<h3>{$membership.name}</h3>
								<div><b class="price">{block name='currency_format_output' module='start' value=$membership.price}</b></div>
								<div class="info">
									{$membership.period_count} {$membership.period_type_output}
								</div>
								{if !empty($membership.is_mine)}
										{l i='text_expires' gid='memberships'} {$membership.expired|date_format:$block_memberships_date_format}
								{elseif empty($hide_buy_btn)}
									<div><a href="{seolink module='memberships' method='form' gid=$membership.gid}" 
									   class="button">{l i='btn_buy_now' gid='memberships'}</a></div>
								{/if}
							</th>
						{/foreach}
					</tr>
				</thead>
				<tbody>
					{*rows*}
					{foreach item=service from=$all_services key=tpl_gid}
						<tr>
							<th>{$service.name}</th>
							{foreach item=membership from=$block_memberships}
								{assign var='mId' value=$membership.id}
								<td>
									{if $service.membership_templates[$mId]}
										+
									{else}
										-
									{/if}
								</td>
							{/foreach}
						</tr>
					{/foreach}
					{if $duplicate_buttons && empty($hide_buy_btn)}
						<tr>
							<th></th>
							{section loop=$block_memberships name=m}
								<td>
									<a href="{seolink module='memberships' method='form' gid=$block_memberships[m].gid}" 
									   class="button">{l i='btn_buy_now' gid='memberships'}</a>
								</td>
							{/section}
						</tr>
					{/if}
				</tbody>
				<tfoot>
				</tfoot>
			</table>
		</div>
	</div>
{else}
	{l i='no_memberships' gid='memberships'}
{/if}
