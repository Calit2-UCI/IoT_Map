{if $auth_type eq 'admin'}
{foreach item=level1 from=$menu}
{if $level1.sub}
<div class="menu">
	<div class="t">
		<div class="b">
			<ul>
				{foreach item=level2 from=$level1.sub}
				<li{if $level2.active eq 1} class="active"{/if}>
					
					<div class="r">
						<a href="{$level2.link}">
							<i class="fa fa-{$level2.icon} {if $level2.active eq 1}w{/if}"></i>
							{$level2.value}	{if $level2.indicator}<span class="num">{$level2.indicator}</span>{/if}
						</a>
					</div>
				</li>
				{/foreach}
			</ul>
		</div>
	</div>
</div>
{/if}
{/foreach}
{else}
<div class="menu">
	<div class="t">
		<div class="b min400">
		</div>
	</div>
</div>
{/if}
