{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_news_menu'}
<div class="actions">&nbsp;</div>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_news_settings' gid='news'}</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_userside_items' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.userside_items_per_page}" name="userside_items_per_page" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_userhelper_items' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.userhelper_items_per_page}" name="userhelper_items_per_page" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_news_max_count' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.rss_news_max_count}" name="rss_news_max_count" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_use_feeds_news' gid='news'}: </div>
			<div class="v"><input type="checkbox" value="1" {if $data.rss_use_feeds_news}checked{/if} name="rss_use_feeds_news" ></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_channel_title' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.rss_feed_channel_title}" name="rss_feed_channel_title" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_channel_description' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.rss_feed_channel_description}" name="rss_feed_channel_description" class="middle" ></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_image_title' gid='news'}: </div>
			<div class="v"><input type="text" value="{$data.rss_feed_image_title}" name="rss_feed_image_title" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_settings_rss_image_url' gid='news'}: </div>
			<div class="v">
				<input type="file" name="rss_logo">
				{if $data.rss_feed_image_url}
				<br><img src="{$data.rss_feed_image_media.thumbs.rss}"  hspace="2" vspace="2" />
				<br><input type="checkbox" name="rss_logo_delete" value="1" id="uichb"><label for="uichb">{l i='field_icon_delete' gid='news'}</label>
				{/if}
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}