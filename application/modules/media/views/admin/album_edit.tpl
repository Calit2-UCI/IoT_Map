{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_common_album_edit' gid='media'}{else}{l i='admin_common_album_add' gid='media'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='media'}: </div>
			<div class="v">
				<input type="hidden" value="{$data.name}" name="name">
				<input type="text" value="{if $validate_lang}{$validate_lang[$cur_lang]}{else}{$data.name}{/if}" name="langs[{$cur_lang}]" class="middle">
				{if $languages_count > 1}
					&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs');
							return false;">{l i='others_languages' gid='services'}</a><br>
					<div id="name_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}
							{if $lang_id ne $cur_lang}
								<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{$data.name}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
							{/if}
						{/foreach}
					</div>
				{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_description' gid='media'}: </div>
			<div class="v"><textarea name="description" class="long">{$data.description}</textarea></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/media/common_albums/">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
	function showLangs(divId) {
		$('#' + divId).slideToggle();
	}
{/literal}</script>

{include file="footer.tpl"}