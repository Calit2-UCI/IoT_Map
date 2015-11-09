{include file="header.tpl"}
<div class="content">
{strip}
	<h1>{seotag tag='header_text'} - {$page_data.total_rows} {l i='header_users_found' gid='users'}</h1>

	<div class="tabs tab-size-15">
		<ul>
			<li{if $views_type == 'my_guests'} class="active"{/if}><a href="{seolink module='users' method='my_guests'}">{l i='header_my_guests' gid='users'}</a></li>
			<li{if $views_type == 'my_visits'} class="active"{/if}><a href="{seolink module='users' method='my_visits'}">{l i='header_my_visits' gid='users'}</a></li>
		</ul>
	</div>
	
	<div class="sorter line">
		<select name="period" onchange="locationHref(('{seolink module='users' method=$views_type period='[period]'}').replace('[period]', this.value));">
			<option value="all"{if $period == 'all'} selected{/if}>{l i='all_time' gid='users'}</option>
			<option value="month"{if $period == 'month'} selected{/if}>{l i='last_month' gid='users'}</option>
			<option value="week"{if $period == 'week'} selected{/if}>{l i='last_week' gid='users'}</option>
			<option value="today"{if $period == 'today'} selected{/if}>{l i='today' gid='users'}</option>
		</select>
		{if $users}<div class="fright">{pagination data=$page_data type='cute'}</div>{/if}
	</div>
	

	<div id="users_block" class="user-gallery big w-subtext">
	{foreach item=item from=$users}
		{assign var=viewer_id value=$item.id}
		{if $viewer_id}
		<div id="item-block-{$item.id}" class="item">
			<div class="user">
				<div class="photo">
					{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$item}
					<a href="{seolink module='users' method='view' data=$item}"><img src="{$item.media.user_logo.thumbs.great}" alt="{$text_user_logo}" title="{$text_user_logo}" /></a>
					<div class="subtext"><span>{$view_dates[$viewer_id]|date_format:$page_data.date_time_format}</span></div>
					<div class="info">
						<!--div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a>, {$item.age}</div--> <!--remove age-->
						<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a></div>
						{if $item.location}<div class="text-overflow">{$item.location}</div>{/if}
					</div>
				</div>
			</div>
		</div>
					
		<script>{literal}
			$('#users_block').not('.w-descr').find('.photo')
				.off('mouseenter').on('mouseenter', function(){
					$(this).find('.info').stop().slideDown(100);
				}).off('mouseleave').on('mouseleave', function(){
					$(this).find('.info').stop(true).delay(100).slideUp(100);
				});
		</script>{/literal}
		{/if}
	{foreachelse}
		<div class="item empty">{l i='empty_search_results' gid='users'}</div>
	{/foreach}
	</div>
	{if $users}<div id="pages_block_2">{pagination data=$page_data type='full'}</div>{/if}
{/strip}
</div>
{include file="footer.tpl"}
