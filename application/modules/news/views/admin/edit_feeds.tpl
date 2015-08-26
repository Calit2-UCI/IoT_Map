{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_feed_change' gid='news'}{else}{l i='admin_header_feed_add' gid='news'}{/if}</div>
		{if $data.id}
		<div class="row">
			<div class="h">{l i='field_feed_title' gid='news'}: </div>
			<div class="v"><a href="{$data.site_link}"><b>{$data.title}</b></a>{if $data.description}<br><i>{$data.description}</i>{/if}</div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_feed_link' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.link}" name="link" class="long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_feed_max_news' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.max_news}" name="max_news" class="short"><br><i>{l i='field_feed_max_news_text' gid='news'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_news_lang' gid='news'}: </div>
			<div class="v"><select name="id_lang" class="short_long">{foreach item=item from=$languages}<option value="{$item.id}"{if $item.id eq $data.id_lang} selected{/if}>{$item.name}</option>{/foreach}</select></div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/news/feeds">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}