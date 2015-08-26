{switch from=$block_width}
	{case value='30'}
		{assign var='block_row_count' value=1}
		{assign var='block_col_md' value=12}
			
	{case value='50'}
		{assign var='block_row_count' value=1}
		{assign var='block_col_md' value=12}
		
	{case value='70'}
		{assign var='block_row_count' value=2}
		{assign var='block_col_md' value=6}
		
	{case value='100'}	
		{assign var='block_row_count' value=2}
		{assign var='block_col_md' value=6}
{/switch}
<div class="latest_added_news_block">
	<div class="row">
		{foreach item=item key=key from=$news}
		<div class="news col-md-{$block_col_md}">
			{if $item.img}
			<div class="photo-small">
				<a href="{seolink module='news' method='view' data=$item}" class="thumbnail">
					<img src="{$item.media.img.thumbs.small}" align="left" class="img-responsive">
				</a>
			</div>
			{/if}
			<div class="strings text-ellipsis">
				<b>{$item.name}</b><br>
				<span class="annotation">{$item.annotation}</span><br>
				<a href="{seolink module='news' method='view' data=$item}">{l i='link_view_more' gid='news'}</a>
			</div>
		</div>
		{counter print=false assign=counter}
		{if $counter is div by $block_row_count}</div><div class="row">{/if}	
		{/foreach}
	</div>
	<p><a href="{seolink module='news' method='index'}">{l i='link_read_more' gid='news'}</a></p>
</div>

