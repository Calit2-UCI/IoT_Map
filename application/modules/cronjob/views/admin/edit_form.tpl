{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_cronjob_change' gid='cronjob'}{else}{l i='admin_header_cronjob_add' gid='cronjob'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_cron_module' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.module}" name="module" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_cron_model' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.model}" name="model" class="middle"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_cron_method' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.method}" name="method" class="middle"></div>
		</div>

		<div  class="row zebra"><i>{l i='crontab_comment' gid='cronjob'}</i></div>

		<div class="row">
			<div class="h">{l i='field_cron_tab_min' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.ct_min}" name="ct_min" class="short"> <i>{l i='field_cron_tab_min_text' gid='cronjob'}</i></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_cron_tab_hour' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.ct_hour}" name="ct_hour" class="short"> <i>{l i='field_cron_tab_hour_text' gid='cronjob'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_cron_tab_day' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.ct_day}" name="ct_day" class="short"> <i>{l i='field_cron_tab_day_text' gid='cronjob'}</i></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_cron_tab_month' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.ct_month}" name="ct_month" class="short"> <i>{l i='field_cron_tab_month_text' gid='cronjob'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_cron_tab_wday' gid='cronjob'}: </div>
			<div class="v"><input type="text" value="{$data.ct_wday}" name="ct_wday" class="short"> <i>{l i='field_cron_tab_wday_text' gid='cronjob'}</i></div>
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/cronjob">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

{include file="footer.tpl"}