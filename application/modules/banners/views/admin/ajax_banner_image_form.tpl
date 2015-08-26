<div class="row">
	<div class="h">{l i='field_link' gid='banners'}: </div>
	<div class="v">{if $data.user_id eq 0}<input type="text" value="{$data.link}" name="link" class="long">{else}<input type="hidden" name='link' value="{$data.link}">{$data.link}{/if}</div>
</div>
<div class="row">
	<div class="h">{l i='field_alt_text' gid='banners'}: </div>
	<div class="v">{if $data.user_id eq 0}<input type="text" value="{$data.alt_text}" name="alt_text" class="long">{else}<input type="hidden" name='alt_text' value="{$data.alt_text}">{$data.alt_text}{/if}</div>
</div>
{if $data.user_id eq 0}
<div class="row">
	<div class="h">{l i='field_number_of_clicks' gid='banners'}: </div>
	<div class="v"><input type="text" value="{$data.number_of_clicks}" name="number_of_clicks" class="short"></div>
</div>
<div class="row">
	<div class="h">{l i='field_number_of_views' gid='banners'}: </div>
	<div class="v"><input type="text" value="{$data.number_of_views}" name="number_of_views" class="short"></div>
</div>
<div class="row">
	<div class="h">{l i='field_expiration_date' gid='banners'}: </div>
	<div class="v">
		<input type="checkbox" name="expiration_date_on" value="1" {if $data.expiration_date_on}checked{/if}>
		<input type="text" value="{if $data.expiration_date|strtotime>0}{$data.expiration_date|date_format:$page_data.date_format|escape}{/if}" name="expiration_date_visible" class="datepicker" id="expiration_date">
		<input type="hidden" value="{$data.expiration_date}" name="expiration_date" id="expiration_date_hide">
	</div>
</div>
<div class="row">
	<div class="h">{l i='field_new_window' gid='banners'}: </div>
	<div class="v"><input type="checkbox" value="1" name="new_window"{if $data.new_window eq 1}checked{/if}></div>
</div>
{/if}
<div class="row">
	<div class="h">{l i='field_image' gid='banners'}: </div>
	<div class="v">
			{if $data.user_id eq 0}
				<input type="file" value="" name="banner_image_file">
				{if $data.banner_image}
				<br>
				<a href="{$data.media.banner_image.file_url}" id="view_banner" target="blank">{l i='btn_preview' gid='start'}</a>&nbsp;&nbsp;
				<input type="checkbox" name="banner_image_delete" value="1" id="banner_image_delete"> <label for="banner_image_delete">{l i='field_image_delete' gid='banners'}</label>
				{/if}
			{else}
				<a href="{$data.media.banner_image.file_url}" id="view_banner" target="blank">{l i='btn_preview' gid='start'}</a>
			{/if}
	</div>
</div>
<script type="text/javascript">{literal}
	$(function(){
		admin_banners.initImageForm();
	});
{/literal}</script>
