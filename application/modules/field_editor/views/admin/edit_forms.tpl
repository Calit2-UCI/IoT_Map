{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_form_change' gid='field_editor'}{else}{l i='admin_header_form_add' gid='field_editor'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='field_editor'}: </div>
			<div class="v">{if $data.id}<input type="hidden" value="{$data.gid}" name="gid">{$data.gid}{else}<input type="text" value="{$data.gid}" name="gid">{/if}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_form_type' gid='field_editor'}: </div>
			<div class="v">
				{if $data.id}
				{foreach item=item from=$types}{if $item.gid eq $data.editor_type_gid}<input type="hidden" value="{$data.editor_type_gid}" name="editor_type_gid">{$item.name}{/if}{/foreach}
				{else}
				<select name="editor_type_gid">{foreach item=item from=$types}<option value="{$item.gid}"{if $item.gid eq $data.editor_type_gid}selected{/if}>{$key} {$item.name}</option>{/foreach}</select>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='field_editor'}: </div>
			<div class="v">
				<input type="text" value="{$data.name}" name="name">
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/field_editor/forms/{$data.editor_type_gid}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});

{/literal}</script>

{include file="footer.tpl"}