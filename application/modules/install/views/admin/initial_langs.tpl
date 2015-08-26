{include file="header.tpl"}

<form method="post" action="{$data.action}" id="install_langs">
	<table class="data" width="100%" cellspacing="0" cellpadding="0">
	<tbody>
		<tr>
			<th class="first">Name</th>
			<th class="w50">Install</th>
			<th class="w50">Default</th>
		</tr>
		{foreach item=lang from=$data.available}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if}>
			<td class="first">{$lang.name}</td>
			<td class="center"><input type="checkbox" {if !$data.install || $lang.code|in_array:$data.install} checked="checked"{/if} name="install[{$lang.id}]" value="{$lang.code}"/></td>
			<td class="center"><input type="radio" {if $data.default eq $lang.code} checked="checked"{/if} name="default" value="{$lang.code}" /></td>
		</tr>
		{/foreach}
	</tbody>
	</table>
	{if $data.available}
	<div class="btn"><div class="l"><input type="submit" name="save_install_langs" value="Next"></div></div>
	{else}
	<div class="btn gray"><div class="l"><input type="button" name="save_install_langs" value="Next" disabled="disabled"></div></div>
	{/if}
</form>
<div class="clr"></div>
{js module='install' file='product_install.js'}
<script type="text/javascript">
	var productInstall = new productInstall();
	{literal}
		$(function(){
			productInstall.langs_init();
		});
	{/literal}
</script>
{include file="footer.tpl"}