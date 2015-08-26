<h2 class="line top bottom linked">
	{l i='table_header_activity' gid='users'}
</h2>
<div class="view-section">
	{if !$data.activity || !$data.approved || !$data.confirm}
		<div>{l i="text_inactive_in_search" gid='users'}</div>
		{if $data.approved && $data.confirm}
			{if $data.available_activation.status}
				<script type='text/javascript'>{literal}
					$(function(){
						loadScripts(
							"{/literal}{js file='available_view.js' return='path'}{literal}", 
							function(){
								activate_available_view = new available_view({
									siteUrl: '{/literal}{$site_url}{literal}',
									checkAvailableAjaxUrl: 'users/ajax_available_user_activate_in_search/',
									buyAbilityAjaxUrl: 'users/ajax_activate_user_activate_in_search/',
									buyAbilityFormId: 'ability_form',
									buyAbilitySubmitId: 'ability_form_submit',
									success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('{/literal}{seolink module='users' method='profile'}{literal}')},
									fail_request: function(message) {error_object.show_error_block(message, 'error');},
								});
							},
							'activate_available_view',
							{async: false}
						);
					});
				</script>{/literal}
				<div class="pt10"><input type="button" onclick="activate_available_view.check_available();" value="{l i='btn_activate' gid='start'}" name="btn_activate_save" id="btn_activate_save"></div>
			{else}
				<div>{l i="text_need_for_activation" gid='users'}</div>
				<ul>
					{foreach from=$data.available_activation.fields item=field}
						<li>{if $field == 'user_logo_moderation'}{l i="wait_image_approve" gid='users'}{elseif $field == 'user_logo'}{l i='upload_photo' gid='users'}{else}{l i="fill_field" gid='users'}: {l i="field_"+$field gid='users'}{/if}</li>
					{/foreach}
				</ul>
			{/if}
		{else}
			<div>{l i='profile_not_confirm' gid='users'}</div>
		{/if}
	{else}
		<div>{l i="text_active_in_search" gid='users'}</div>
		{if $data.activated_end_date && $data.activated_end_date != '0000-00-00 00:00:00'}
			<div>{l i="text_active_until" gid='users'}: {$data.activated_end_date|date_format:$page_data.date_time_format}</div>
		{/if}
	{/if}
        
	{if $data.is_hide_on_site && $data.services_status.hide_on_site.service.status}
		<div class="pt10">{l i='profile_hide_on_site' gid='users'}</div>
	{/if}
</div>
<h2 class="line top bottom linked">
	{l i='table_header_personal' gid='users'}
	{l i='filter_section_personal' gid='users' assign='personal_section_name'}
	<a class="fright" href="{seolink module='users' method='profile' section-code='personal' section-name=$personal_section_name}"><i class="fa-pencil icon-big edge hover"></i></a>
</h2>
<div class="view-section">
	{l i='no_information' gid='users' assign='no_info_str'}
	<div class="r">
		<div class="f">{l i='field_user_type' gid='users'}:</div>
		<div class="v">{$data.user_type_str}</div>
	</div>
	{if $data.looking_user_type_str}
	<div class="r">
		<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
		<div class="v">{$data.looking_user_type_str}</div>
	</div>
	{/if}
	{if $data.age_min}
	<div class="r">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='from' gid='users'}:</div>
		<div class="v">{$data.age_min}</div>
	</div>
	{/if}
	{if $data.age_max}
	<div class="r">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='to' gid='users'}:</div>
		<div class="v">{$data.age_max}</div>
	</div>
	{/if}
	<div class="r">
		<div class="f">{l i='field_nickname' gid='users'}:</div>
		<div class="v">{$data.nickname}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_fname' gid='users'}:</div>
		<div class="v">{$data.fname}</div>
	</div>
	<div class="r">
		<div class="f">{l i='field_sname' gid='users'}:</div>
		<div class="v">{$data.sname}</div>
	</div>
	<div class="r">
		<div class="f">{l i='birth_date' gid='users'}:</div>
		<div class="v">{$data.birth_date|date_format:$page_data.date_format:'':$no_info_str}</div>
	</div>
	{if $data.location}
	<div class="r">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{$data.location}</div>
	</div>
	{/if}
</div>
{foreach item=item from=$sections}
	<h2 class="line top bottom linked">
		{$item.name}
		<a class="fright" href="{seolink module='users' method='profile' section-code=$item.gid section-name=$item.name}"><i class="fa-pencil icon-big edge hover"></i></a>
	</h2>
	<div class="view-section">
		{include file="custom_view_fields.tpl" fields_data=$item.fields module="users"}
	</div>
{/foreach}
