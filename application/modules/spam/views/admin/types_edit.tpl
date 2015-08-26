{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_spam_menu'}
<form method="post" action="" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_types_edit' gid='spam'}</div>
		<div class="row">
			<div class="h">{l i='field_type_form_type' gid='spam'}:&nbsp;* </div>
			<div class="v">
				<select name="data[form_type]" class="long">
					{foreach item=item key=key from=$form_type_lang.option}<option value="{$key|escape}" {if $key eq $data.form_type}selected{/if}>{$item}</option>{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_type_send_mail' gid='spam'}: </div>
			<div class="v">
				<input type="hidden" name="data[send_mail]" value="0" />
				<input type="checkbox" name="data[send_mail]" value="1" {if $data.send_mail}checked="checked"{/if} />
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_type_status' gid='spam'}: </div>
			<div class="v">
				<input type="hidden" name="data[status]" value="0" />
				<input type="checkbox" name="data[status]" value="1" {if $data.status}checked="checked"{/if} />
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/spam/types">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}
