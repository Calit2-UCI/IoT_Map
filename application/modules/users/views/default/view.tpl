{include file="header.tpl"}
{strip}
<div class="content">
<div class="content-block">
	<div class="view small">
		<div class="image">
			<!--<div id="user_photo" class="pos-rel dimp100{if $data.user_logo} pointer{/if}">-->
			<div>
				{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$data}
				<a href={$data.fe_field22} target="_blank"><img src="{$data.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" alt="Company logo"></a>
				<!--<img src="{$data.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" />-->
			</div>
		</div>
		<div class="info">
			{*
			<div class="body">
				<h1>
				<span class="users-profile-h1">{seotag tag='header_text'}</span>
				<!-- hide online status, by JL
				<span data-role="online_status" class="fright online-status">
					<s class="{$data.statuses.online_status_text}">{$data.statuses.online_status_lang}</s></span></h1>
				<div>
				-->
					<!--div class="fright">{l i='views' gid='users'}: {$data.views_count}</div-->
					
					<!--remove age-->
					<!--{l i='field_age' gid='users'}: {$data.age}-->
					<!--website hyper link-->
					
					{if $data.fe_field22}
						<a href={$data.fe_field22} target="_blank" class="target_blank">Website</a>
					{/if} 
					
					
					{if $data.address}
						<i class="delim-alone"></i><span class="">{$data.location}</span>
						{depends module=geomap}
							<i class="delim-alone"></i>
							<a href="javascript:void(0);" id="view_map_link" class="target_blank">{l i='link_view_map' gid='geomap'}</a>
						{/depends}
					{/if}
				</div>
			</div>
			*}
			<div class="body">
				<h1>
					<span class="users-profile-h1">{seotag tag='header_text'}</span>
					<!-- hide online status, by JL
					<span data-role="online_status" class="fright online-status"><s class="{$data.statuses.online_status_text}">{$data.statuses.online_status_lang}</s></span>
					-->
				</h1>
				<div>
					<!--div class="fright">{l i='views' gid='users'}: {$data.views_count}</div-->
					<div class="fleft clearfix">
						<div class="fleft">
							<!--website hyper link-->
							{if $data.fe_field22}
								<a href={$data.fe_field22} target="_blank" class="target_blank">Website</a>
							{/if} 
							
							{if $data.address}
								<i class="delim-alone"></i><span class="">{$data.location}</span>
								{depends module=geomap}
									<i class="delim-alone"></i>
									<a href="javascript:void(0);" id="view_map_link" class="target_blank">{l i='link_view_map' gid='geomap'}</a>
								{/depends}
								  
							{/if}
							
						</div>
						<div class="fleft">
							{block name='send_rating_block' module='ratings' 
							object_id=$data.id 
							type_gid='users_object' 
							responder_id=$data.id 
							success=$rating_callback 
							is_owner=$is_user_owner
							template='form'}
						</div>
					</div>
				</div>
			</div>			
			
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
				<!--{block name='send_message_button' module='mailbox' id_user=$data.id}-->
				<!--{*helper func_name='lists_links' module='users_lists' func_param=$data.id*}-->
				<a href=# !--target="_blank"-- class="target_blank" style="display:inline; text-decoration:none;">
					<img src={$site_root}application\modules\menu\views\default\mail-circle.png alt="Email" style="width:3%; height:80%; border-style:none;">
				</a>
				
				{if $data.fe_field25}
				<a href={$data.fe_field25} target="_blank" class="target_blank" style="display:inline; text-decoration:none;">
					<img src={$site_root}application\modules\menu\views\default\facebook-circle.png alt="Facebook" title="Facebook" style="width:3%; height:80%; border-style:none;">
				</a>{/if}
				{if $data.fe_field24}
				<a href={$data.fe_field24} target="_blank" class="target_blank" style="display:inline; text-decoration:none;">
					<img src={$site_root}application\modules\menu\views\default\twitter-circle.png alt="Twitter" title="Twitter" style="width:3%; height:80%; border-style:none;">
				</a>{/if}
				{if $data.fe_field26}
				<a href={$data.fe_field26} target="_blank" class="target_blank" style="display:inline; text-decoration:none;">
					<img src={$site_root}application\modules\menu\views\default\linkedin-circle.png alt="Linkedin" title="Linkedin" style="width:3%; height:80%; border-style:none;">
				</a>{/if}
				{if $data.fe_field23}
				<a href={$data.fe_field23} target="_blank" class="target_blank" style="display:inline; text-decoration:none;">
					<img src={$site_root}application\modules\menu\views\default\instagram-circle.png alt="Instagram" title="Instagram" style="width:3%; height:80%; border-style:none;">
				</a>{/if}
				
				<!--{block name='friendlist_links' module='friendlist' id_user=$data.id}-->
				<!--{block name='blacklist_button' module='blacklist' id_user=$data.id}-->
				<!--{block name='favourites_button' module='favourites' id_user=$data.id}-->
				<!--{helper func_name='im_chat_add_button' module='im' func_param=$data.id}-->
				<!--{block name='video_chat_button' module='video_chat' user_id=$data.id}-->
				<!--{block name='wink' module='winks' user_id=$data.id}-->
				<!--{block name='kisses_list' module='kisses' user_id=$data.id}-->
				<!--{block name='button' module='associations' id_user=$data.id}-->
				<!--{block name='mark_as_spam_block' module='spam' object_id=$data.id type_gid='users_object' template='button'}-->
			</div>
		</div>
	</div>

	<div class="edit_block" id="profile_tab_sections">
		{include file="view_profile_menu.tpl" module="users"}
		<div class="view-user">
			{if !$profile_section || $profile_section eq 'profile'}
				{include file="view_users_profile.tpl" module="users"}
			<!--remove wall-->

			{elseif $profile_section eq 'gallery'}
				{block name='media_block' module='media' param=$subsection page='1' user_id=$user_id location_base_url=$location_base_url}
			{/if}
		</div>
	</div>
</div>
<div class="clr"></div>
{/strip}
{if $data.user_logo}
	<script type='text/javascript'>{literal}
		$(function(){
			loadScripts(
				["{/literal}{js file='users-avatar.js' module='users' return='path'}{literal}"],
				function(){
					user_avatar = new avatar({
						site_url: site_url,
						id_user: {/literal}{$user_id}{literal}
					});
				},
				['user_avatar'],
				{async: true}
			);
		});
	</script>{/literal}
{/if}
</div>
{include file="footer.tpl"}
