{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_robots_txt_editing' gid='seo_advanced'}</div>
		<div class="row">
			<div class="h">{l i='field_robots_file' gid='seo_advanced'}: </div>
			<div class="v"><textarea name="content" style="height: 170px">{$content}</textarea></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save_robots" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/seo_advanced">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
