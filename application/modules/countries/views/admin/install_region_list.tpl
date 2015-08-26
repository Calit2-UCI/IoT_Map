{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_countries_menu'}
<form action="{$site_url}admin/countries/install/city/{$country.code}" method="post">
<div class="actions">
	<ul>
		<li><div class="l"><input type="submit" name="install-btn" value="{l i='install_regions_link' gid='countries' type='button'}" onclick="javascript: return checkBoxes();"></div></li>
	</ul>
	&nbsp;
</div>

<table cellspacing="0" cellpadding="0" class="data" width="100%">
<tr>
	<th class="first w30"><input type="checkbox" onclick="javascript: checkAll(this.checked);"></th>
	<th>{l i='field_country' gid='countries'}</th>
	<th class="w50">{l i='field_region_code' gid='countries'}</th>
	<th>{l i='field_region_name' gid='countries'}</th>
	<th class="w100">{l i='field_region_status' gid='countries'}</th>
</tr>
{foreach item=item from=$list}
{assign var="region_code" value=$item.code}
{counter print=false assign=counter}
<tr{if $counter is div by 2} class="zebra"{/if}>
	<td class="first center"><input type="checkbox" class="ch-reg" name="region[]" value="{$item.code}" {if $installed[$region_code]}disabled{/if}></td>
	<td class="center">{$country.name} ({$country.code})</td>
	<td class="center">{$item.code}</td>
	<td>{$item.name}</td>
	<td class="icons">{if $installed[$region_code]}<i>{l i='region_installed' gid='countries'}</i>{else}<i>{l i='region_not_installed' gid='countries'}</i>{/if}&nbsp;</td>
</tr>
{foreachelse}
<tr><td colspan="5" class="center zebra">{l i='no_regions' gid='countries'}</td></tr>
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
