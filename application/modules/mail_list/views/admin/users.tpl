{include file="header.tpl"}
<div id="mail_list">
	{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_mail_list_menu'}
	<div class="actions">
		<ul>
			{if $data.filter ne 'subscribed'}
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="subscribe_all">
						{l i='link_subscribe_all' gid='mail_list'}
					</a>
				</div></li>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="subscribe_selected">
						{l i='link_subscribe_selected' gid='mail_list'}
					</a>
				</div></li>
			{/if}
			{if $data.filter ne 'not_subscribed'}
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="unsubscribe_all">
						{l i='link_unsubscribe_all' gid='mail_list'}
					</a>
				</div></li>
				<li><div class="l">
					<a href="javascript:void(0);" class="subscribe" id="unsubscribe_selected">
						{l i='link_unsubscribe_selected' gid='mail_list'}
					</a>
				</div></li>
			{/if}
		</ul>&nbsp;
	</div>
	{include file="users_form.tpl" module="mail_list" theme="admin"}
	<div class="menu-level3">
		<ul>
			<li class="{if $data.filter eq 'all'}active{/if}{if !$users_count.all} hide{/if}">
				<a href="{$site_url}admin/mail_list/users/all">
					{l i='filter_all' gid='mail_list'} (<span id="count_all">{$users_count.all}</span>)
				</a>
			</li>
			<li class="{if $data.filter eq 'not_subscribed'}active{/if}{if !$users_count.not_subscribed} hide{/if}">
				<a href="{$site_url}admin/mail_list/users/not_subscribed">
					{l i='filter_not_subscribed' gid='mail_list'} (<span id="count_not_subscribed">{$users_count.not_subscribed}</span>)
				</a>
			</li>
			<li class="{if $data.filter eq 'subscribed'}active{/if}{if !$users_count.subscribed} hide{/if}">
				<a href="{$site_url}admin/mail_list/users/subscribed">
					{l i='filter_subscribed' gid='mail_list'} (<span id="count_subscribed">{$users_count.subscribed}</span>)
				</a>
			</li>
		</ul>
		&nbsp;
	</div>
	<table cellspacing="0" cellpadding="0" id="tbl_users" class="data" width="100%">
	<tr>
		<th class="first"><input type="checkbox" id="grouping_all"></th>
		<th>{l i='field_nickname' gid='mail_list'}</th>
		<th>{l i='field_email' gid='mail_list'}</th>
		<th class="w150">{l i='field_registration_date' gid='mail_list'}</th>
		<th>{l i='field_user_type' gid='mail_list'}</th>
		<th class="w150">{l i='field_location' gid='mail_list'}</th>
		<th class="w50">&nbsp;</th>
	</tr>
	{foreach item=user from=$users}
		{counter print=false assign=counter}
		<tr{if $counter is div by 2} class="zebra"{/if} id="user{$user.id}">
			<td class="first w20 center"><input id="cb_user_{$user.id}" type="checkbox" class="grouping" value="{$user.id}"></td>
			<td><label for="cb_user_{$user.id}"><b>{$user.nickname}</b><br>{$user.fname} {$user.sname}</label></td>
			<td>{$user.email}</td>
			<td class="center">{$user.date_created|date_format:$date_format}</td>
			<td class="center">{$user.user_type_str}</td>
			<td class="center">{$user.location}</td>
			<td class="icons">
				{if $user.id_subscription eq $data.id_subscription}
					<a class="unsubscribe_one mark" href="javascript:void(0);" title="{l i='link_unsubscribe_user' gid='mail_list'}">
						<img src="{$site_root}{$img_folder}icon-mark.png" width="16" height="16" border="0" alt="{l i='link_unsubscribe_user' gid='mail_list'}">
					</a>
				{else}
					<a class="subscribe_one" href="javascript:void(0);" title="{l i='link_subscribe_user' gid='mail_list'}">
						<img src="{$site_root}{$img_folder}icon-unmark.png" width="16" height="16" border="0" alt="{l i='link_subscribe_user' gid='mail_list'}">
					</a>
				{/if}
			</td>
		</tr>
	{foreachelse}
		<tr><td colspan="7" class="center">{l i='no_users' gid='mail_list'}</td></tr>
	{/foreach}
	</table>
	{include file="pagination.tpl"}
	<div class="btn">
		<div class="l">
			<a id="btn_save" name="btn_save" href="javascript:void(0);">{l i='btn_save_filter' gid='mail_list' type='button'}</a>
		</div>
	</div>
		<div class="clr"></div>
	<script type="text/javascript">
	{literal}
	var mail_list;
	$(function(){
		mail_list = new adminMailList({
			siteUrl: '{/literal}{$site_url}{literal}',
			imgsUrl: '{/literal}{$site_url}{$img_folder}{literal}'
		});
		mail_list.bind_users_events();
	});
	{/literal}
	</script>
</div>
{include file="footer.tpl"}
