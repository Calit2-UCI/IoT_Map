{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_config_change' gid='uploads'}{else}{l i='admin_header_config_add' gid='uploads'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_default_img' gid='uploads'}: </div>
			<div class="v"><input type="file" value="" name="default_img" class="file">
			{if $data.default_img}
			<br><a href="{$data.default_img_url}" target="blank">{l i='view_source_link' gid='uploads'}</a>
			{foreach item=item from=$thumbs}
			<a href="{$data.default_url}{$item.prefix}-{$data.default_img}" class="tooltip" id="thumb_{$item.id}">{l i='btn_view' gid='start'} {$item.width}x{$item.height}</a>
			<div style="display: none" id="tt_thumb_{$item.id}"><img src="{$data.default_url}{$item.prefix}-{$data.default_img}"></div>
			{/foreach}
			{/if}
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/file_uploads/test">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
{include file="footer.tpl"}