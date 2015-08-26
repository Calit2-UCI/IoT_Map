{include file="header.tpl" load_type='ui'}

<script type="text/javascript">{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
{/literal}</script>

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $comments_type.id}{l i='admin_header_comments_type_change' gid='comments'}{else}{l i='admin_header_comments_type_add' gid='comments'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_status' gid='comments'}: </div>
			<div class="v"><input type="checkbox" value="1" name="status" {if $comments_type.status}checked{/if}/></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_use_likes' gid='comments'}: </div>
			<div class="v"><input type="checkbox" value="1" name="use_likes" {if $comments_type.settings.use_likes}checked{/if}/></div>
		</div>
		{if $comments_type.gid!='user_avatar'}
		<div class="row">
			<div class="h">{l i='field_guest_access' gid='comments'}: </div>
			<div class="v"><input type="checkbox" value="1" name="guest_access" {if $comments_type.settings.guest_access}checked{/if}/></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_char_count' gid='comments'}: </div>
			<div class="v"><input type="text" value="{$comments_type.settings.char_count}" name="char_count" class="middle"/></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/comments/index/{$data.page}/">{l i='btn_cancel' gid='start'}</a>
</form>


{include file="footer.tpl"}
