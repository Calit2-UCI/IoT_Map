{include file="header.tpl"}
<div class="edit-form n150">
	<div class="row header">{l i='admin_header_alerts_show' gid='spam'}</div>
	<div class="row">
		<div class="h">{l i='field_alert_content' gid='spam'}: </div>
		<div class="v spam_content">{$data.content.content.view}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_alert_user' gid='spam'}: </div>
		<div class="v">{$data.content.user_content}</div>
	</div>
	{if $data.subpost.body}
	<div class="row">
		<div class="h">{l i='field_spam_subpost' gid='spam'}: </div>
		<div class="v spam_subcontent">{$data.subpost.body}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_spam_subpost_author' gid='spam'}: </div>
		<div class="v">{$data.subpost.author}</div>
	</div>
	{/if}
	{if $data.type.form_type eq 'select_text'}
	<div class="row">
		<div class="h">{l i='field_spam_reason' gid='spam'}: </div>
		<div class="v">{$data.reason}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_spam_message' gid='spam'}: </div>
		<div class="v">{$data.message}</div>
	</div>
	{/if}
	<div class="row">
		<div class="h">{l i='field_alert_poster' gid='spam'}: </div>
		<div class="v">{$data.poster.output_name}</div>
	</div>
	<div class="row">
		<div class="h">{l i='field_alert_date_add' gid='spam'}: </div>
		<div class="v">{$data.date_add|date_format:$date_format}</div>
	</div>
</div>
<div class="btn"><div class="l"><a href="{$site_url}admin/spam/alerts_delete/without_object/{$data.id}">{l i='link_alerts_delete' gid='spam'}</a></div></div>
{if $data.link}<div class="btn"><div class="l"><a href="{$data.link}">{l i='btn_content_edit' gid='spam'}</a></div></div>{/if}
{if $data.delete_link && $data.content.user_content!=''}<div class="btn"><div class="l"><a href="{$data.delete_link}{$data.id}">{l i='btn_content_delete' gid='spam'}</a></div></div>{/if}
<a class="cancel" href="{$site_url}admin/spam/index">{l i='btn_cancel' gid='start'}</a>
<div class="clr"></div>
<script>{literal}
$(function(){
	var div_spam_content = $(".spam_content");
	var iframe = div_spam_content.find('iframe');
	if(iframe.length){
		iframe.css('width', '100%');
	}
	var a_href = div_spam_content.find('a');
	if(a_href.length){
		a_href.css('width', '100%');
	}
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>
{include file="footer.tpl"}
