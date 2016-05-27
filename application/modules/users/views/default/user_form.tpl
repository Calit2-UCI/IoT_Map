<div class="content">
<!--This file have control of the "Edit Profile" for each user.-->

{capture assign='user_form_block'}
	{strip}
	<form method="post" enctype="multipart/form-data">
		{if $action eq 'personal'}
			
			{if !$not_editable_fields.fname}
				<div class="r">
					<div class="f">{l i='field_fname' gid='users'}: </div>
					<div class="v" style="height:29px"><input type="text" name="fname" value="{$data.fname|escape}"></div>
				</div>
			{/if}
			
			{if !$not_editable_fields.user_type}
				<div class="r">
					<div class="f">{l i='field_user_type' gid='users'}:</div>
					<div class="v">
						<select name="user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.user_type || (!$data.user_type && $key == 2)} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.looking_user_type}
				<div class="r hide">
					<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
					<div class="v">
						<select name="looking_user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.looking_user_type || (!$data.looking_user_type && $key == 2)} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.age_min && !$not_editable_fields.age_max}
				<div class="r hide">
					<div class="f hide">{l i='field_partner_age' gid='users'}: </div>
					<div class="v hide">
						{if !$not_editable_fields.age_min}
							{l i='from' gid='users'}&nbsp;
							<select name="age_min" class="short">
								{foreach from=$age_range item=age}
									<option value="{$age}"{if $age == $data.age_min} selected{/if}>{$age}</option>
								{/foreach}
							</select>&nbsp;
						{/if}
						{if !$not_editable_fields.age_max}
							{l i='to' gid='users'}&nbsp;
							<select name="age_max" class="short">
								{foreach from=$age_range item=age}
									<option value="{$age}"{if $age == $data.age_max} selected{/if}>{$age}</option>
								{/foreach}
							</select>
						{/if}
					</div>
				</div>
			{/if}
			{if !$not_editable_fields.nickname}  <!--nickname is the user name-->
				<div class="r hide">
					<div class="f">{l i='field_nickname' gid='users'}: </div>
					<div class="v"><input type="text" name="nickname" value="{$data.nickname|escape}"></div>
				</div>
			{/if}

			{if !$not_editable_fields.sname}       <!--Leave this in Hide-->
				<div class="r hide">
					<div class="f">{l i='field_sname' gid='users'}: </div>
					<div class="v"><input type="text" name="sname" value="{$data.sname|escape}"></div>
				</div>
			{/if}
			
			<!--We decided to place the website and social media to the contact section, so we hide them hear.-->
			<div class="r hide">
				<div class="f">Website: </div>
				<!--div class="v">Empty</div-->
				<div class="v"><input type="text" name="website" value="{$data.website|escape}"></div>
			</div>
			

			
			<div class="r">
				<div class="f">{l i='field_icon' gid='users'}: </div>
				<div class="v">
					<input type="file" name="user_icon" accept="image/*;capture=camera">
					{if $data.user_logo || $data.user_logo_moderation}
						<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb">{l i='field_icon_delete' gid='users'}</label><br>
						{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$data}
						{if $data.user_logo_moderation}
						<img src="{$data.media.user_logo_moderation.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}">
						{else}
						<img src="{$data.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}">
						{/if}
					{/if}
				</div>
			</div>
			{if !$not_editable_fields.birth_date}
				<div class="r hide">
					<div class="f">{l i='birth_date' gid='users'}: </div>
					<div class="v"><input type='text' value='{$data.birth_date}' name="birth_date"></div>
				</div>
			{/if}
			
			
			<div class="r">
				<div class="f">Address: </div>
				<div class="v"><input id="address" type="text" name="address" value="{$data.address|escape}"></div>
			</div>			
			
			<div class="r">
				<div class="f">City, States, Country: </div>
				<div class="v">
					{block name='location_select' 
						module='countries' 
						select_type='city' 
						id_country=$data.id_country 
						id_region=$data.id_region 
						id_city=$data.id_city
						var_country_name='id_country'
						var_region_name='id_region'
						var_city_name='id_city'
					}
				</div>
				<input type="hidden" name="lat" value="{$data.lat|escape}" id="lat">
				<input type="hidden" name="lon" value="{$data.lon|escape}" id="lon">
			</div>
			
			<div class="r">
				<div class="f">Zip Code: </div>
				<div class="v"><input id="postal_code" type="text" name="postal_code" value="{$data.postal_code|escape}"></div>
			</div>
			
		{else}
			{/strip}{include file="custom_form_fields.tpl" module="users"}{strip}
		{/if}

		<div class="r">
			<div class="f">&nbsp;</div>
			<div class="v">
				<input type="submit" value="{if $edit_mode}{l i='btn_save' gid='start' type='button'}{else}{l i='btn_register' gid='start' type='button'}{/if}" name="btn_register">
				{l i='filter_section_view' gid='users' assign='profile_section_name'}&nbsp;
				<a href="{seolink module='users' method='profile' section-code='view' section-name=$profile_section_name}" class="btn-link"><i class="icon icon-arrow-left icon-big edge hover"></i>{l i='btn_back' gid='start'}</a>
			</div>
		</div>
	</form>
	{/strip}
	{depends module=geomap}
		{block name=geomap_load_geocoder module='geomap'}	
	{/depends}
	 

	<script type='text/javascript'>{literal}
		$(function(){
			var now = new Date();
			var yr =  (new Date(now.getYear() - {/literal}{$age_max}{literal}, 0, 1).getFullYear()) + ':' + (new Date(now.getYear() - {/literal}{$age_min}{literal}, 0, 1).getFullYear());
			$( "#datepicker" ).datepicker({
				dateFormat :'yy-mm-dd',
				changeYear: true,
				changeMonth: true,
				yearRange: yr
			});
	
			loadScripts(
				["{/literal}{js module='users' file='users-map.js' return='path'}{literal}"],
				function(){
					users_map = new usersMap({
						siteUrl: site_url,
					});
				},
				['users_map'],
				{async: true}
			);

		});
	</script>{/literal}
{/capture}

<div class="view small">
	<div class="image">
		<div id="user_photo" class="pos-rel dimp100 pointer">
			{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$data}
			{if $data.user_logo_moderation}
				<img src="{$data.media.user_logo_moderation.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" />
			{else}
				<img src="{$data.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" />
			{/if}
		</div>
	</div>
	<div class="info">
		<div class="body">
			<h1>
				<span style="font-size:30px;line-height:28px;">{$data.output_name}</span>
				
				<!-- hide online status, by JL
				<span data-role="online_status" class="fright online-status"><s class="{$data.statuses.online_status_text}">{$data.statuses.online_status_lang}</s></span>
				-->
			</h1>
			<div>
				<!--{l i='field_age' gid='users'}: {$data.age}-->
				
				{if $data.fe_field22}
					<a href={$data.fe_field22} target="_blank" class="target_blank">Website</a>
				{/if}  
				
				<!--{if $data.location}<i class="delim-alone"></i><span class="">{$data.location}</span>{/if}-->
				{if $data.address}
					<i class="delim-alone"></i><span class="">{$data.location}</span>
					{depends module=geomap}
						<i class="delim-alone"></i>
						<a href="javascript:void(0);" id="view_map_link" class="target_blank">{l i='link_view_map' gid='geomap'}</a>
					{/depends}
								  
				{/if}
				
			</div>
		</div>
		
		<!------->
		<!------->
		
		{depends module=geomap}
			<script type='text/javascript'>{literal}
				$(function(){		
					loadScripts(
						["{/literal}{js file='users-map.js' module='users' return='path'}{literal}"],
						function(){
							user_map = new usersMap({
								siteUrl: site_url,
								user_id: '{/literal}{$data.id}{literal}',
							});
						},
						['user_map'],
						{async: true}
					);
				});
			</script>{/literal}
		{/depends}
		
		<div class="actions noPrint">
			{l i='filter_section_personal' gid='users' assign='personal_section_name'}
			<a class="link-r-margin" title="{l i='edit_my_profile' gid='start'}" href="{seolink module='users' method='profile' section-code='personal' section-name=$personal_section_name}"><i class="icon-pencil icon-big edge hover"></i></a>
			{if $data.services_status.highlight_in_search.status}
				<span class="link-r-margin" title="{$data.services_status.highlight_in_search.name|escape}" onclick="highlight_in_search_available_view.check_available();"><i class="icon-sun icon-big edge hover zoom20"></i></span>
			{/if}
			{if $data.services_status.up_in_search.status}
				<span class="link-r-margin" title="{$data.services_status.up_in_search.name|escape}" onclick="up_in_search_available_view.check_available();"><i class="icon-level-up icon-big edge hover zoom20"></i></span>
			{/if}
			{if $data.services_status.hide_on_site.status}
				<span class="link-r-margin" title="{$data.services_status.hide_on_site.name|escape}" onclick="hide_on_site_available_view.check_available();"><i class="icon-eye-close icon-big edge hover zoom20"></i></span>
			{/if}
			<script type='text/javascript'>{literal}
				$(function(){
					loadScripts(
						["{/literal}{js file='available_view.js' return='path'}{literal}", "{/literal}{js file='users-avatar.js' module='users' return='path'}{literal}"],
						function(){
							highlight_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_highlight_in_search/',
								buyAbilityAjaxUrl: 'users/ajax_activate_highlight_in_search/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');}
							});
							up_in_search_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_up_in_search/',
								buyAbilityAjaxUrl: 'users/ajax_activate_up_in_search/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');}
							});
							hide_on_site_available_view = new available_view({
								siteUrl: site_url,
								checkAvailableAjaxUrl: 'users/ajax_available_hide_on_site/',
								buyAbilityAjaxUrl: 'users/ajax_activate_hide_on_site/',
								buyAbilityFormId: 'ability_form',
								buyAbilitySubmitId: 'ability_form_submit',
								success_request: function(message) {error_object.show_error_block(message, 'success'); locationHref('');},
								fail_request: function(message) {error_object.show_error_block(message, 'error');}
							});

							user_avatar = new avatar({site_url: site_url});
						},
						['highlight_in_search_available_view', 'up_in_search_available_view', 'hide_on_site_available_view', 'user_avatar'],
						{async: false}
					);
				});
			</script>{/literal}
		</div>
	</div>
</div>

{if $data.approved && $data.confirm && !$data.activity && $data.available_activation.status}
	<div class="bg-highlight_bg mtb10 p10">
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
							fail_request: function(message) {error_object.show_error_block(message, 'error');}
						});
					},
					'activate_available_view',
					{async: false}
				);
			});
		</script>{/literal}
		<div>
			<a href="{$site_url}users/account/services"><button type="button" class="inline-btn">{l i='link_activate_profile' gid='users'}</button></a>
			<!--input type="button" class="inline-btn" onclick="activate_available_view.check_available();" value="{l i='link_activate_profile' gid='users'}" /-->
			<span class="ml10">{l i='text_activate_profile' gid='users'}</span>
		</div>
	</div>
{/if}

<div class="edit_block" id="profile_tab_sections">
	{assign var='action' value='view'}
	{include file="profile_menu.tpl" module="users"}
	<div class="users-user_form">
		{$user_form_block}
	</div>
</div>
</div>