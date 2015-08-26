{include file="header.tpl"}
<div class="membership-edit">
	<form method="post" action="{$data.action}" id="save_form" enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">
				{if $data.id}
					{l i='admin_header_edit' gid='memberships'}
				{else}
					{l i='admin_header_create' gid='memberships'}
				{/if}
			</div>
			<div class="row">
				<div class="h">
					{l i='field_gid' gid='memberships'}: &nbsp;*
				</div>
				<div class="v">
					<input type="text" name="data[gid]" value="{$membership.gid|escape}" class="long">
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_name' gid='memberships'}: &nbsp;*
				</div>
				<div class="v">
					{foreach item=lang_item key=lang_id from=$langs}
						{assign var='name' value='name_'+$lang_id}
						{if $lang_id eq $current_lang_id}
							<input type="text" name="data[name_{$lang_id}]" value="{$membership[$name]|escape}" class="long" 
								   lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}" />
						{else}
							<input type="hidden" name="data[name_{$lang_id}]" value="{$membership[$name]|escape}" 
								   lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}" />
						{/if}
					{/foreach}
					<a href="#" lang-editor="button" lang-editor-type="data-name">
						<img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16">
					</a>
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_user_type_enabled' gid='memberships'}: 
				</div>
				<div class="v">
					{foreach item=item key=key from=$user_types}
						<input type="checkbox" name="data[user_type_enabled][]" id="user_type_{$key}"
							   {if !$membership.user_type_disabled_array || !in_array($key, $membership.user_type_disabled_array)}checked{/if}
								value="{$key}">
						<label for="user_type_{$key}">{$item}</label>
					{/foreach}
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_pay_type' gid='memberships'}: 
				</div>
				<div class="v">
					<select name="data[pay_type]" class="middle">
						{foreach item=pay_type from=$pay_types}
							<option value="{$pay_type}" {if $membership.pay_type eq $pay_type}selected{/if}>{l i='payment_type_'$pay_type gid='memberships'}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_price' gid='memberships'}: &nbsp;*
				</div>
				<div class="v">
					<input type="number" min="0" step=".01" value="{$membership.price|escape}" name="data[price]" class="short"> 
					{block name='currency_format_output' module='start'}
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_period' gid='memberships'}: &nbsp;*
				</div>
				<div class="v">
					<input type="number" min="0" step="1" name="data[period_count]" value="{$membership.period_count|escape}" class="middle">
					<select name="data[period_type]" class="short_long">
						{foreach item=period_type from=$period_types}
							<option value="{$period_type}" {if $membership.period_type eq $period_type}selected{/if}>{l i='period_type_'$period_type gid='memberships'}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="row">
				<div class="h">
					{l i='field_description' gid='memberships'}: 
				</div>
				<div class="v">
					{foreach item=lang_item key=lang_id from=$langs}
						{assign var='description' value='description_'+$lang_id}
						{if $lang_id eq $current_lang_id}
							<textarea name="data[description_{$lang_id}]" class="long" lang-editor="value" 
									  lang-editor-type="data-description" lang-editor-lid="{$lang_id}">{$membership[$description]|escape}</textarea>
						{else}
							<input type="hidden" name="data[description_{$lang_id}]" value="{$membership[$description]|escape}" 
								   lang-editor="value" lang-editor-type="data-description" lang-editor-lid="{$lang_id}">
						{/if}
					{/foreach}
					<a href="#" lang-editor="button" lang-editor-type="data-description" lang-field-type="textarea">
						<img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16">
					</a>
				</div>
			</div>
		</div>
		<div class="btn">
			<div class="l">
				<input type="submit" name="save" value="{l i='btn_save' gid='start' type='button'}">
			</div>
		</div>
		<a class="cancel" href="{$site_url}admin/memberships">{l i='btn_cancel' gid='start'}</a>
	</form>
	<div class="clr"></div>
	{if $membership.id}
		<h2>{l i='admin_header_services' gid='memberships'}</h2><br>
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			<tr>
				<th class="first">{l i='field_service_name' gid='memberships'}</th>
				<th class="w100">{l i='field_service_status' gid='memberships'}</th>
			</tr>
            {counter print=false assign="counter"}
			{foreach item=item from=$services}
				<tr{if $counter is div by 2} class="zebra"{/if}>
					<td class="first center">{$item.name}</td>
					<td class="center">
						{assign var='service_id' value=$item.id}
						{if $membership.services_array[$service_id].is_active}
							<a class="btn-service-activity" data-activity="true" data-id="{$item.id}" 
							   href="{$site_url}admin/memberships/deactivate_service/{$membership.id}/{$item.id}"
							   title="{l i='link_deactivate_service' gid='memberships' type='button'}">
								<i class="fa fa-circle"></i>
							</a>
						{else}
							<a class="btn-service-activity" data-activity="false" data-id="{$item.id}" 
							   href="{$site_url}admin/memberships/activate_service/{$membership.id}/{$item.id}"
							   title="{l i='link_activate_service' gid='memberships' type='button'}">
								<i class="inactive fa fa-circle"></i>
							</a>
						{/if}
					</td>
				</tr>
			{foreachelse}
				<tr><td colspan="4" class="center">{l i='no_services' gid='memberships'}</td></tr>
				{/foreach}
		</table>
	{/if}

	{capture assign='params'}
		{foreach item=item from=$services}
			{if !empty($item.template.data_membership_array)}
				{assign var='service_id' value=$item.id}
				<div class="edit-form n150">
					<div class="row header">{$item.name}</div>
					{foreach item=type key=name from=$item.template.data_membership_array}
						<div class="row">
							<div class="h">{$name}</div>
							<div class="v">
								<input type="text" name="params[{$service_id}][{$name}]" value="{$membership.services_list[$service_id].data_admin[$name]|escape}">
							</div>
						</div>
					{/foreach}
				</div>
			{/if}
		{/foreach}
	{/capture}

	{if $params|trim}
		<br><br>
		<h2>{l i='admin_header_params' gid='memberships'}</h2><br>
		<form method="post" action="{$data.action}" id="save_params_form" enctype="multipart/form-data">
			{$params}
			<div class="btn">
				<div class="l">
					<input type="submit" name="save_params" value="{l i='btn_save' gid='start' type='button'}">
				</div>
			</div>
			<a class="cancel" href="{$site_url}admin/memberships">{l i='btn_cancel' gid='start'}</a>
		</form>
	{/if}
</div>
{js file='memberships-admin.js' module='memberships'}
<script>{literal}
	$(function(){
		$("div.row:visible:odd").addClass("zebra");
		new membershipsAdmin({
        siteUrl: '{/literal}{$site_root}{literal}',
			membershipId: '{/literal}{$membership.id}{literal}',
			parent: '.membership-edit'
		});
	});
{/literal}</script>
{block name='lang_inline_editor' module='start'}
{include file="footer.tpl"}
