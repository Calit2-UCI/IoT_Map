{include file="header.tpl"}
{include file="left_panel.tpl" module="start"}
<div class="rc">
	<div class="content-block">
		<h1>
			{seotag tag='header_text'}
		</h1>
		{foreach item=item from=$pages}
			<div class="content">
				<div class="links">
					<a href="{seolink module='content' method='view' data=$item}">{$item.title}</a>
				</div>
				<div class="clr"></div>
			</div>
		{foreachelse}
			<div class="empty">{l i="no_content_yet_header" gid='content'}</div>
		{/foreach}
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
