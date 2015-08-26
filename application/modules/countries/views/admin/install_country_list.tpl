{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_countries_menu'}
<form action="{$site_url}admin/countries/install/selected_countries/{$country.code}" method="post">
<div class="actions">
{*	<ul>
		<li><div class="l"><a href="{$site_url}admin/ausers/edit">{l i='link_add_country' gid='countries'}</a></div></li>
	</ul>*}
	<ul>
		<li><div class="l"><input type="submit" name="install-btn" value="{l i='install_regions_link' gid='countries' type='button'}" onclick="javascript: return checkBoxes();"></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="{if $filter eq 'all'}active{/if}{if !$filter_data.all} hide{/if}"><a href="{$site_url}admin/countries/install/country/all">{l i='filter_all_countries' gid='countries'} ({$filter_data.all})</a></li>
		<li class="{if $filter eq 'installed'}active{/if}{if !$filter_data.installed} hide{/if}"><a href="{$site_url}admin/countries/install/country/installed">{l i='filter_installed_countries' gid='countries'} ({$filter_data.installed})</a></li>
		<li class="{if $filter eq 'not-installed'}active{/if}{if !$filter_data.not_installed} hide{/if}"><a href="{$site_url}admin/countries/install/country/not-installed">{l i='filter_not_installed_countries' gid='countries'} ({$filter_data.not_installed})</a></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w30"><input type="checkbox" onclick="javascript: checkAll(this.checked);"></th>
	<th class="first w50">{l i='field_country_code' gid='countries'}</th>
	<th>{l i='field_country_name' gid='countries'}</th>
	<th class="w100">{l i='field_country_status' gid='countries'}</th>
	<th class="w100">&nbsp;</th>
</tr>
{foreach item=item from=$list}
{assign var="country_code" value=$item.code}

{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center"><input type="checkbox" class="ch-reg" name="countries[]" value="{$item.code}" {if $installed[$country_code]}disabled{/if}></td>
	<td class="first center">{$item.code}</td>
	<td>{$item.name}</td>
	<td class="icons">{if $installed[$country_code]}<i>{l i='country_installed' gid='countries'}</i>{else}<i>{l i='country_not_installed' gid='countries'}</i>{/if}&nbsp;</td>
	<td class="icons"><a href="{$site_url}admin/countries/install/region/{$item.code}">{if $installed[$country_code]}{l i='view_regions_link' gid='countries'}{else}{l i='country_install_link' gid='countries'}{/if}</a></td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center zebra">{l i='no_countries' gid='countries'}</td></tr>
{/foreach}
</table>
</form>

<script>{literal}
	function checkAll(checked){
		if(checked)
			$('.ch-reg:enabled').prop('checked', true);
		else
			$('.ch-reg:enabled').prop('checked', false);
	}
	function checkBoxes(){
		if($('.ch-reg:checked').length > 0){
			return true;
		}else{
			return false;
		}
	}
{/literal}</script>
{include file="footer.tpl"}