<div class="content-info-page-block">
	<h2>
		{$info_page_data.data.title}
	</h2>
	{if $info_page_data.data.img}
	<div class="media">
		<a href="{seolink module='content' method='view' data=$info_page_data.data}" class="thumbnail pull-left">
			<img src="{$info_page_data.data.media.img.thumbs.big}" alt="" class="img-responsive">
		</a>
		<div class="media-body text-content">
			{if $info_page_data.data.annotation}
				{$info_page_data.data.annotation}
			{else}
				{$info_page_data.data.content|truncate:255}
			{/if}
			<br><br>
			<a href="{seolink module=content method=view data=$info_page_data.data}">
				{l i='link_view_more' gid='content'}
			</a>
		</div>
	</div>
	{else}
	<div class="text-content text">
		{if $info_page_data.data.annotation}
			{$info_page_data.data.annotation}
		{else}
			{$info_page_data.data.content|truncate:255}
		{/if}
		<br><br>
		<a href="{seolink module=content method=view data=$info_page_data.data}">
			{l i='link_view_more' gid='content'}
		</a>
	</div>
	{/if}
	
</div>
