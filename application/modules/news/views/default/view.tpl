{include file="header.tpl"}
<div class="content-block">
	<h1>{seotag tag='header_text'}</h1>
	<div class="news wysiwyg">
		{*<h3>{$data.name}</h3>*}
		{if $data.img}
			{if $data.media.img.thumbs.big}
			{l i='text_news_photo' gid='news' type='button' assign='text_news_photo' replace_array=$data}
				<img src="{$data.media.img.thumbs.big}" align="left" hspace="5" vspace="5" alt="{$text_news_photo}" title="{$text_news_photo}" />
			{/if}
		{/if}
		<span class="date">{$data.date_add|date_format:$page_data.date_format}</span><br>
		{if !$data.content}<span class="annotation">{$data.annotation}</span><br>
		{else}<span class="annotation">{$data.content}</span><br>{/if}
		{if $data.video_content.embed}
			{$data.video_content.embed}<br>
		{/if}

		{if $data.feed_link}{l i='feed_source' gid='news'}: <a href="{$data.feed_link}">{$data.feed.title}</a>{/if}
		<div class="clr"></div>
		{block name='comments_form' module='comments' gid=news id_obj=$data.id hidden=0 count=$data.comments_count}
		<br><a href="{seolink module='news' method='index'}">{l i='link_back_to_news' gid='news'}</a>
	</div>
</div>

{block name='show_social_networks_like' module='social_networking'}
{block name='show_social_networks_share' module='social_networking'}
{block name='show_social_networks_comments' module='social_networking'}
<div class="clr"></div>
{include file="footer.tpl"}
