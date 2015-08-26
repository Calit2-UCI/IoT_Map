{include file="header.tpl"}
<div class="content-block">
	<h1>
		{seotag tag='header_text'}
		<div class="fright">
			<a target="_blank" class="icon-rss icon-big edge hover zoom20" href="{$site_url}news/rss"></a>
		</div>
	</h1>

	{foreach item=item from=$news}
		<div class="news news-item">
			<a href="{seolink module='news' method='view' data=$item}">{$item.name}</a>
			{if $item.img}
				{if $item.media.img.thumbs.small}
				{l i='text_news_photo' gid='news' type='button' assign='text_news_photo' replace_array=$item}
					<img src="{$item.media.img.thumbs.small}" align="left" alt="{$text_news_logo}" title="{$text_news_logo}" />
				{/if}
			{/if}
			<span class="date">{$item.date_add|date_format:$page_data.date_format}</span><br>
			<span class="annotation">{$item.annotation}</span><br>
			<div class="links">
				{if $item.feed}{l i='feed_source' gid='news'}: <a href="{$item.feed.site_link}">{$item.feed.title}</a><br>{/if}
				<a href="{seolink module='news' method='view' data=$item}">{l i='link_view_more' gid='news'}</a>
			</div>
			<div>
				{block name='comments_form' module='comments' gid=news id_obj=$item.id hidden=1 count=$item.comments_count}
			</div>
			<div class="clr"></div>
		</div>
	{foreachelse}
		<div class="empty">{l i="no_news_yet_header" gid='news'}</div>
	{/foreach}
	<div class="clr"></div>
	{if $news}<div class="line top">{pagination data=$page_data type='full'}</div>{/if}
</div>
<div class="clr"></div>
{include file="footer.tpl"}
