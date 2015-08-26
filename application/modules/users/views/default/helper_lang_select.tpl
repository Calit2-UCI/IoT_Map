{if $count_active > 1}
	{if !$type || $type eq 'dropdown'}
		<ul>
			<li>
				<select onchange="location.href = '{$site_url}users/change_language/'+this.value;">
					{foreach item=item from=$languages}
						{if $item.status eq '1'}
							<option value="{$item.id}" {if $item.id eq $current_lang} selected{/if}>
								{$item.name}
							</option>
						{/if}
					{/foreach}
				</select>
			</li>
		</ul>
	{elseif $type eq 'menu'}
		<menu class="header-item" label="{l i='on_account_header' gid='users_payments'}">
			{$languages[$current_lang].name}&nbsp;
			<i class="fa-caret-down"></i>
			<div class="drop w150">
				<menu>
					{foreach item=item from=$languages}
						<li>
							{if $item.status eq '1'}
								<a href="#" onclick="location.href = '{$site_url}users/change_language/{$item.id}'">{$item.name}</a>
							{/if}
						</li>
					{/foreach}
				</menu>
			</div>
		</menu>
	{/if}
{/if}
