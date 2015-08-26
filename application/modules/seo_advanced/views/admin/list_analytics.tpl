{include file="header.tpl"}
<div class="filter-form">
<form method="post">
http://<input type="text" name="url" value="{$url}">
<input type="submit" name="btn_save" value="{l i='btn_send' gid='start' type='button'}">
</form>
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first" colspan="2" width="30%">{l i='analytics_h_basic' gid='seo_advanced'}</th>
	<th colspan="2" width="30%">{l i='analytics_h_alexa' gid='seo_advanced'}</th>
	<th colspan="2" width="30%">{l i='analytics_h_backlinks' gid='seo_advanced'}</th>
</tr>
<tr class="zebra">
	<td class="w150">{l i='field_domain_age' gid='seo_advanced'}</td>
	<td class="center">{if $domain && $domain.registered}{$domain.age.y} {l i='da_years' gid='seo_advanced'} {$domain.age.m} {l i='da_months' gid='seo_advanced'} {$domain.age.d} {l i='da_days' gid='seo_advanced'}{else}{l i='domain_not_registered' gid='seo_advanced'}{/if}</td>
	<td class="w150">{l i='field_backlinks' gid='seo_advanced'}:</td>
	<td class="center"><a href="{if $check_links}{$check_links.alexa_backlinks}{/if}" target="_blank">{if $domain}{$domain.alexa_backlinks}{/if}</a></td>
	<td class="w150">{l i='field_google' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.google_backlinks}{/if}" target="_blank">{if $domain}{$domain.google_backlinks}{/if}</a></td>
</tr>
<tr>
	<td>{l i='field_page_rank' gid='seo_advanced'}/{l i='field_tic' gid='seo_advanced'}</td>
	<td class="center">{if $domain}{$domain.page_rank}/{$domain.tic}{/if}</td>
	<td>{l i='field_traffic_rank' gid='seo_advanced'}:</td>
	<td class="center"><a href="{if $check_links}{$check_links.alexa_rank}{/if}" target="_blank">{if $domain}{$domain.alexa_rank}{/if}</a></td>
	<td>{l i='field_yahoo' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.yahoo_backlinks}{/if}" target="_blank">{if $domain}{$domain.yahoo_backlinks}{/if}</a></td>
</tr>
<tr>
	<th class="first" colspan="2">{l i='analytics_h_tech' gid='seo_advanced'}</th>
	<th colspan="4">{l i='analytics_h_indexed' gid='seo_advanced'}</th>
</tr>
<tr class="zebra">
	<td>{l i='field_rank' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.technorati_rank}{/if}" target="_blank">{if $domain}{$domain.technorati_rank}{/if}</a></td>
	<td>{l i='field_dmoz' gid='seo_advanced'}:</td>
	<td class="center"><a href="{if $check_links}{$check_links.dmoz_listed}{/if}" target="_blank">{if $domain && $domain.dmoz_listed}{l i='field_listed' gid='seo_advanced'}{else}{l i='field_not_listed' gid='seo_advanced'}{/if}</a></td>
	<td>{l i='field_google' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.google_indexed}{/if}" target="_blank">{if $domain}{$domain.google_indexed}{/if}</a></td>
</tr>
<tr>
	<td>{l i='field_authority' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.technoraty_authority}{/if}" target="_blank">{if $domain}{$domain.technoraty_authority}{/if}</a></td>
	<td>{l i='field_yahoo' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.yahoo_indexed}{/if}" target="_blank">{if $domain}{$domain.yahoo_indexed}{/if}</a></td>
	<td>{l i='analytics_h_yandex' gid='seo_advanced'}</td>
	<td class="center"><a href="{if $check_links}{$check_links.yandex_indexed}{/if}" target="_blank">{if $domain}{$domain.yandex_indexed}{/if}</a></td>
</tr>
</table>
{include file="footer.tpl"}
