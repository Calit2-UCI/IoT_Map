{include file="header.tpl"}


{if $data.id}
	{capture assign='user_menu'}{strip}
		{if $sections}
			{foreach item=item key=key from=$sections}
			<li{if $item.gid eq $section} class="active"{/if}><div class="l"><a href="{$site_url}admin/users/edit/{$item.gid}/{$data.id}">{$item.name}</a></div></li>
			{/foreach}
		{/if}
		{depends module=seo}
			<li class="{if $section eq 'seo'}active{/if}"><a href="{$site_url}admin/users/edit/seo/{$data.id}">{l i='filter_section_seo' gid='seo'}</a></li>
		{/depends}
		{depends module=media}
			<li><a href="{$site_url}admin/media/user_media/{$data.id}">{l i='filter_section_uploads' gid='media'}</a></li>
		{/depends}
	{/strip}{/capture}
	{if $user_menu}
	<div class="menu-level2">
		<ul>
			<li{if $section == 'personal'} class="active"{/if}><div class="l"><a href="{$site_url}admin/users/edit/personal/{$data.id}">{l i='table_header_personal' gid='users'}</a></div></li>
			{$user_menu}
		</ul>
		&nbsp;
	</div>
	{/if}
{/if}
{switch from=$section}
	{case value='personal'}
		<form action="{$data.action}" method="post" enctype="multipart/form-data" name="save_form">
		<div class="edit-form n150">
			<div class="row header">{if $data.id}{l i='admin_header_users_change' gid='users'}{else}{l i='admin_header_users_add' gid='users'}{/if}</div>
			{if !$data.id}
			<div class="row">
				<div class="h">{l i='field_user_type' gid='users'}: </div>
				<div class="v">
					<select name="user_type" class="middle">
					{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.user_type} selected{/if}>{$item}</option>{/foreach}
					</select>
				</div>
			</div>
			{/if}
			<div class="row">
				<div class="h">{l i='field_looking_user_type' gid='users'}: </div>
				<div class="v">
					<select name="looking_user_type" class="middle" >
						{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.looking_user_type} selected{/if}>{$item}</option>{/foreach}
					</select>
				</div>
			</div>
			<div class="row hide">
				<div class="h hide">{l i='field_partner_age' gid='users'}: </div>
				<div class="v hide">
					{l i='from' gid='users'}
					<select name="age_min" class="short">
						{foreach from=$age_range item=age}
						<option value="{$age}"{if $age == $data.age_min} selected{/if}>{$age}</option>
						{/foreach}
					</select>&nbsp;
					{l i='to' gid='users'}
					<select name="age_max" class="short">
						{foreach from=$age_range item=age}
						<option value="{$age}"{if $age == $data.age_max} selected{/if}>{$age}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_email' gid='users'}: </div>
				<div class="v"><input type="text" name="email" value="{$data.email|escape}" class="middle"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_nickname' gid='users'}: </div>
				<div class="v"><input type="text" name="nickname" value="{$data.nickname|escape}" class="middle"></div>
			</div>
			{if $data.id}
			<div class="row">
				<div class="h">{l i='field_change_password' gid='users'}: </div>
				<div class="v"><input type="checkbox" value="1" name="update_password" id="pass_change_field"></div>
			</div>
			{/if}
			<div class="row">
				<div class="h">{l i='field_password' gid='users'}: </div>
				<div class="v"><input type="password" name="password" id="pass_field" class="middle" {if $data.id}disabled{/if}></div>
			</div>
			{if !empty($use_repassword)}
				<div class="row">
					<div class="h">{l i='field_repassword' gid='users'}: </div>
					<div class="v"><input type="password" name="repassword" id="repass_field" class="middle" {if $data.id}disabled{/if}></div>
				</div>
			{/if}
			<div class="row">
				<div class="h">{l i='field_fname' gid='users'}: </div>
				<div class="v"><input type="text" name="fname" value="{$data.fname|escape}" class="middle"></div>
			</div>
			<div class="row hide">
				<div class="h">{l i='field_sname' gid='users'}: </div>
				<div class="v"><input type="text" name="sname" value="{$data.sname|escape}" class="middle"></div>
			</div>
			<div class="row">
				<div class="h">{l i='field_icon' gid='users'}: </div>
				<div class="v">
					<input type="file" name="user_icon">
					{if $data.user_logo || $data.user_logo_moderation}
					<br><input type="checkbox" name="user_icon_delete" value="1" id="uichb"><label for="uichb">{l i='field_icon_delete' gid='users'}</label><br>
					{if $data.user_logo_moderation}<img src="{$data.media.user_logo_moderation.thumbs.middle}">{else}<img src="{$data.media.user_logo.thumbs.middle}">{/if}
					{/if}
				</div>
			</div>
			<div class="row hide">
				<div class="h">{l i='birth_date' gid='users'}: </div>
				<div class="v"><input type='text' value='0' name="birth_date" id="datepicker" maxlength="10" class="middle"></div>
				{js file='jquery-ui.custom.min.js'}
				<link href='{$site_root}{$js_folder}jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen' />
				<script>{literal}
					$(function(){
						var date_now = new Date();
						var date_min = new Date(date_now.getYear() - {/literal}{$age_max}{literal}, 0, 1);
						var date_max = new Date(date_now.getYear() - {/literal}{$age_min}{literal}, 0, 1);
						var yr =  (date_min.getFullYear()) + ':' + (date_max.getFullYear());
						$( "#datepicker" ).datepicker({
							dateFormat :'yy-mm-dd',
							changeYear: true,
							changeMonth: true,
							yearRange: yr,
							defaultDate: date_max
						});
					});
				{/literal}</script>
			</div>
			<div class="row">
				<div class="h">{l i='field_region' gid='users'}: </div>
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
			{depends module=geomap}
				{block name=geomap_load_geocoder module='geomap'}
			{/depends}	
			<script>{literal}
				$(function(){
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
			{/literal}</script>
			{*<div class="row">
				<div class="h">{l i='field_phone' gid='users'}: </div>
				<div class="v"><input type="text" name="phone" value="{$data.phone|escape}"></div>
			</div>*}
			{if $data.id}
			{if $data.confirm}
			<input type="hidden" name="confirm" value="1">
			{else}
			<div class="row">
				<div class="h">{l i='field_confirm' gid='users'}: </div>
				<div class="v"><input type="checkbox" name="confirm" value="1"></div>
			</div>
			{/if}
			{/if}		
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$back_url}">{l i='btn_cancel' gid='start'}</a>
		</form>
		<div class="clr"></div>
	{case value='seo'}
		{depends module=seo}
		{foreach item=section key=key from=$seo_fields}
		<form method="post" action="{$data.action|escape}" name="seo_{$section.gid}_form">
		<div class="edit-form n150">
			<div class="row header">{$section.name}</div>
			{if $section.tooltip}
			<div class="row">
				<div class="h">&nbsp;</div>
				<div class="v">{$section.tooltip}</div>
			</div>
			{/if}		
			{foreach item=field from=$section.fields}
			<div class="row">
				<div class="h">{$field.name}: </div>
				<div class="v">
					{assign var='field_gid' value=$field.gid}
					{switch from=$field.type}
						{case value='checkbox'}
							<input type="hidden" name="{$section.gid}[{$field_gid}]" value="0">
							<input type="checkbox" name="{$section.gid}[{$field_gid}]" value="1" {if $seo_settings[$field_gid]}checked{/if}>
						{case value='text'}
							{foreach item=lang_item key=lang_id from=$languages}
							{assign var='section_gid' value=$section.gid+'_'+$lang_id}
							<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid]|escape}" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field_gid}"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='start' type='button'}" title="{l i='note_types_translate' gid='start' type='button'}"></a>
						{case value='textarea'}
							{foreach item=lang_item key=lang_id from=$languages}
							{assign var='section_gid' value=$section.gid+'_'+$lang_id}
							{if $lang_id eq $current_lang_id}
							<textarea name="{$section.gid}[{$field_gid}][{$lang_id}]" rows="5" cols="80" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">{$seo_settings[$section_gid][$field_gid]|escape}</textarea>
							{else}
							<input type="hidden" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid]|escape}" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">
							{/if}
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field.gid}" lang-field-type="textarea"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='start' type='button'}" title="{l i='note_types_translate' gid='start' type='button'}"></a>					
					{/switch}<br>{$field.tooltip}
				</div>
			</div>
			{/foreach}	
		</div>	
		<div class="btn"><div class="l"><input type="submit" name="btn_save_{$section.gid}" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$back_url}">{l i='btn_cancel' gid='start'}</a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		{/foreach}
		{block name='lang_inline_editor' module='start'}
		{/depends}
	{case}
		<form action="{$data.action}" method="post" enctype="multipart/form-data" name="save_form">
		<div class="edit-form n150">
			<div class="row header">{if $data.id}{l i='admin_header_users_change' gid='users'}{else}{l i='admin_header_users_add' gid='users'}{/if}</div>
			{include file="custom_form_fields.tpl" module="users"}
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$back_url}">{l i='btn_cancel' gid='start'}</a>
		</form>
		<div class="clr"></div>
{/switch}

<script>{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
		$("#pass_change_field").click(function(){
			if(this.checked){
				$("#pass_field").removeAttr("disabled");
				$("#repass_field").removeAttr("disabled");
			}else{
				$("#pass_field").attr('disabled', 'disabled'); 
				$("#repass_field").attr('disabled', 'disabled');
			}
		});
	});
{/literal}</script>
{include file="footer.tpl"}
