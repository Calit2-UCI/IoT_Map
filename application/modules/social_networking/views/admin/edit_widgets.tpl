{include file="header.tpl" load_type='ui'}
{js file='easyTooltip.min.js'}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">

<table cellspacing="0" cellpadding="0" class="data" width="100%">
    <tr>
        <th class="first">{l i='field_name' gid='social_networking'}</th>
        <th class="w100">{l i='widget_like' gid='social_networking'}</th>
        <th class="w100">{l i='widget_share' gid='social_networking'}</th>
        <th class="w100">{l i='widget_comments' gid='social_networking'}</th>
    </tr>
	{if count($services) > 0}
    {foreach item=item key=key from=$services}
        {counter print=false assign=counter}
        <tr{if $counter is div by 2} class="zebra"{/if}>
            <td>{$item.name} {if !$item.status}({l i='no_active_widgets' gid='social_networking'}){/if}</td>
            <td class="center">{if in_array('like', $widgets_actions[$key])}<input type="checkbox" name="like[{$item.gid}]" {if !$item.status}disabled{else}{assign var=gid value=$item.gid}{if $data.data.like[$gid]}checked{/if}{/if} />{/if}</td>
            <td class="center">{if in_array('share', $widgets_actions[$key])}<input type="checkbox" name="share[{$item.gid}]" {if !$item.status}disabled{else}{assign var=gid value=$item.gid}{if $data.data.share[$gid]}checked{/if}{/if} />{/if}</td>
            <td class="center">{if in_array('comments', $widgets_actions[$key])}<input type="checkbox" name="comments[{$item.gid}]" value="{$item.id}" {if !$item.status}disabled{else}{assign var=gid value=$item.gid}{if $data.data.comments[$gid]}checked{/if}{/if} />{/if}</td>
        </tr>
	{/foreach}
    {else}
        <tr><td class="center zebra" colspan=4>{l i='no_services' gid='social_networking'}</td></tr>
    {/if}
</table>

		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/social_networking/pages/">{l i='btn_cancel' gid='start'}</a>
</form>
<script type='text/javascript'>
	{literal}
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: 'tt_'+$(this).attr('id')
			});
		});
	});
	{/literal}
</script>

{include file="footer.tpl"}