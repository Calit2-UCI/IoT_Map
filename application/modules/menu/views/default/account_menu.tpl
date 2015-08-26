<ul id="user_account_menu">
	{foreach key=key item=item from=$menu}
		<li {if $item.active && !$item.sub}class="active"{/if}>
			<a id="account_menu_{$item.gid}" href="{$item.link}">
				{$item.value}{if $item.indicator}<span class="num">{$item.indicator}</span>{/if}
			</a>
			{if $item.sub}
				<ul>
					{foreach key=key item=s from=$item.sub}
						<li {if $s.active}class="active"{/if}>
							<a id="account_menu_{$item.gid}_{$s.gid}" href="{$s.link}">{$s.value}{if $s.indicator}<span class="num">{$s.indicator}</span>{/if}</a>
						</li>
					{/foreach}
				</ul>
			{/if}
		</li>
	{/foreach}
</ul>
<script type="text/javascript">{literal}
$(function(){
	$('#user_account_menu > li').each(function(){
		if($(this).find('ul > li').length > 0){
			$(this).find(' > a').attr('data-pjax', '0').bind('click', function(){
				$(this).parent().find('ul').slideToggle();
				return false;
			});
		}
		if($(this).find('ul > li.active').length > 0 ){
			$(this).find('ul').slideDown();
		}
	});
});
{/literal}</script>