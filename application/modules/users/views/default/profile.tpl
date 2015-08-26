{include file="header.tpl" load_type='ui'}
<div class="content-block">
	{if $action == 'view' || $action == 'wall' || $action == 'gallery'}
		{include file="user_profile.tpl" module="users" theme="default"}
	{else}
		{include file="user_form.tpl" module="users" theme="default"}
	{/if}
</div>
<div class="clr"></div>
{include file="footer.tpl"}
