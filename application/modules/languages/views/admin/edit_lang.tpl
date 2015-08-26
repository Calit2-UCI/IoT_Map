{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_language_change' gid='languages'}{else}{l i='admin_header_language_add' gid='languages'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='languages'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_code' gid='languages'}: </div>
			<div class="v"><input type="text" value="{$data.code}" name="code" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_rtl' gid='languages'}: </div>
			<div class="v">
				<input type="radio" value="ltr" name="rtl" {if $data.rtl eq 'ltr' || !$data.rtl}checked{/if} id="ltr_val"><label for="ltr_val">{l i='field_rtl_value_ltr' gid='languages'}</label><br>
				<input type="radio" value="rtl" name="rtl" {if $data.rtl eq 'rtl'}checked{/if} id="rtl_val"><label for="rtl_val">{l i='field_rtl_value_rtl' gid='languages'}</label><br>
			</div>
		</div>
	
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/languages">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>


{include file="footer.tpl"}