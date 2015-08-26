{include file="header.tpl" load_type='ui'}
{js file='easyTooltip.min.js'}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_config_change' gid='uploads'}{else}{l i='admin_header_config_add' gid='uploads'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle_long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="middle_long"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_max_width' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.max_width}" name="max_width" class="short"> px <i>{l i='int_unlimit_condition' gid='uploads'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_max_height' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.max_height}" name="max_height" class="short"> px <i>{l i='int_unlimit_condition' gid='uploads'}</i></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_max_size' gid='uploads'}: </div>
			<div class="v"><input type="text" value="{$data.max_size}" name="max_size" class="short"> b <i>{l i='int_unlimit_condition' gid='uploads'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name_format' gid='uploads'}: </div>
			<div class="v">
				<select name="name_format" id="name_format" class="middle_long">{foreach item=item key=key from=$lang_name_format.option}<option value="{$key}" {if $key eq $data.name_format}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_file_formats' gid='uploads'}: </div>
			<div class="v">
				{foreach item=item from=$formats}<input type="checkbox" name="file_formats[]" value="{$item}" {if $data.enable_formats[$item]}checked{/if} id="frm_{$item}"> <label for="frm_{$item}">{$item}</label><br>{/foreach}
			</div>
		</div>
		<div class="row">
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
		<a class="cancel" href="{$site_url}admin/uploads/configs">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
<script type='text/javascript'>
{literal}
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: 'tt_'+$(this).attr('id')
			});
		});
	});
{/literal}
</script>
{include file="footer.tpl"}