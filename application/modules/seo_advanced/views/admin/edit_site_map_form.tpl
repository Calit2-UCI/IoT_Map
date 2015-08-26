{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_site_xml_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_sitemap_txt_editing' gid='seo_advanced'}</div>
		{if $sitemap_data.mtime}
		<div class="row zebra">
			<div class="h">{l i='field_last_sitemap_generating' gid='seo_advanced'}: </div>
			<div class="v">{$sitemap_data.mtime|date_format:$date_format}</div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_frequency' gid='seo_advanced'}:&nbsp;* </div>
			<div class="v">
				<select name="changefreq">{foreach item=item key=key from=$frequency_lang.option}<option value="{$key}" {if $key eq $sitemap_changefreq}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_last_modified' gid='seo_advanced'}:&nbsp;* </div>
			<div class="v">
			<input type="radio" name="lastmod" value="0" id="lastmod0" {if $sitemap_lastmod eq 0}checked{/if}> <label for="lastmod0">{l i='field_last_modified_0' gid='seo_advanced'}</label><br>
			<input type="radio" name="lastmod" value="1" id="lastmod1" {if $sitemap_lastmod eq 1}checked{/if}> <label for="lastmod1">{l i='field_last_modified_1' gid='seo_advanced'}</label><br>
			<input type="radio" name="lastmod" value="2" id="lastmod2" {if $sitemap_lastmod eq 2}checked{/if}> <label for="lastmod2">{l i='field_last_modified_2' gid='seo_advanced'}</label><br>
			<input type="text" name="lastmod_date" value="{$sitemap_data.current_date|escape}">
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_priority' gid='seo_advanced'}:&nbsp;* </div>
			<div class="v">
				<select name="priority">
				<option value="0" {if $sitemap_priority eq 0}selected{/if}>{l i='field_priority_none' gid='seo_advanced'}</option>
				<option value="1" {if $sitemap_priority eq 1}selected{/if}>{l i='field_priority_auto' gid='seo_advanced'}</option>
				</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">&nbsp;</div>
			<div class="v">{l i='text_sitemap_help' gid='seo_advanced'}</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save_sitexml" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/seo_advanced">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{include file="footer.tpl"}
