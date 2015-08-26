<div class="info_pages_block">
	<h2>
		{if $section.title}
			{$section.title}
		{else}
			{l i='header_info_pages' gid='content'}
		{/if}
	</h2>
	{switch from=$block_width}
		{case value='30'}
			{assign var='pages_per_row' value=1}
			{assign var='pages_col_md' value=12}
				
		{case value='50'}
			{assign var='pages_per_row' value=2}
			{assign var='pages_col_md' value=6}
			
		{case value='70'}
			{assign var='pages_per_row' value=2}
			{assign var='pages_col_md' value=6}
			
		{case value='100'}	
			{assign var='pages_per_row' value=3}
			{assign var='pages_col_md' value=4}
	{/switch}
	<div class="row">
		{counter print=false assign=counter start=0}
		{foreach item=item key=key from=$pages}
		<div class="info_page col-md-{$pages_col_md} column">
			{if $item.img}
			<div class="text text-center">
				<a href="{seolink module='content' method='view' data=$item}" class="">
					<img src="{$item.media.img.thumbs.small}" alt="">
				</a>
				<div class="text-content">
					<a href="{seolink module='content' method='view' data=$item}" class="ellipsis">{$item.title}</a><br>
					<span class="text-content">
						{if $item.annotation}
							{$item.annotation}
						{else}
							{$item.content|truncate:255}
						{/if}
					</span>
				</div>
			</div>
			{else}
			<div class="text">
				<a href="{seolink module='content' method='view' data=$item}" class="ellipsis">{$item.title}</a><br>
				<span class="text-content">
					{if $item.annotation}
						{$item.annotation}
					{else}
						{$item.content|truncate:255}
					{/if}
				</span>
			</div>
			{/if}
		</div>
		{counter print=false assign=counter}
		{if $counter is div by $pages_per_row}</div><div class="row">{/if}
		{/foreach}
	</div>
</div>
