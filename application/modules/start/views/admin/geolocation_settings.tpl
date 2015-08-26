{include file="header.tpl"}
	
	<form method="post" action="" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n250">
					<div class="row{if $key is div by 2} zebra{/if}">
						<div class="h">{l i='geolocation_onoff' gid='start'}:</div>
						<div class="v"><input type="checkbox" name="geolocation_onoff" value="1" class="short" {if $geolocation_onoff}checked{/if}></div>
					</div>
					<br>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/start/menu/system-items">{l i='btn_cancel' gid='start'}</a>
	</form>
	
<div class="clr"></div>
{include file="footer.tpl"}
