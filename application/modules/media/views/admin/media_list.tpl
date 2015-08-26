{include file="header.tpl"}
<div class="actions">
	<ul>
		<li><div class="l"><a id="mark_adult_select_block" href="" onclick="return false;">{l i='btn_mark_adult' gid='media'}</a></div></li>
		<li><div class="l"><a id="unmark_adult_select_block" href="javascript:void(0)">{l i='btn_unmark_adult' gid='media'}</a></div></li>
		<li><div class="l"><a id="delete_select_block" href="javascript:void(0)">{l i='btn_link_delete' gid='media'}</a></div></li>
	</ul>
	&nbsp;
</div>

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='media_menu_item'}
{strip}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w50"><input type="checkbox" id="grouping_all"></th>
		<th class="w110">{l i='field_files' gid='media'}</th>
		<th>{l i='media_info' gid='media'}</th>
		<th>{l i='media_owner' gid='media'}</th>
		<th class="w100">&nbsp;</th>
	</tr>
	{foreach item=item from=$media}
	{counter print=false assign=counter}
	<tr class="{if $counter is div by 2}zebra{/if}{if $item.is_adult} adult{/if}">
		<td class="first w20 center"><input type="checkbox" class="grouping" value="{$item.id}" id="media-{$item.id}"></td>
		<td>
			{if $item.upload_gid eq 'gallery_audio'}
				 <audio src="{$item.media.mediafile.file_url}" controls></audio>
			{elseif $item.media}
				<a href="{$item.media.mediafile.file_url}" target="_blank"><img src="{$item.media.mediafile.thumbs.small}"/></a>
			{/if}

			{if $item.video_content}
				<span onclick="vpreview = new loadingContent({literal}{'closeBtnClass': 'w'}{/literal}); vpreview.show_load_block('{$item.video_content.embed|escape}');"><img class="pointer" src="{$item.video_content.thumbs.small}"/></span>
				{*<img src="{$item.video_content.thumbs.small}"/>*}
			{/if}
		</td>
		<td>
			<b>{l i='media_user' gid='media'}</b>: {$item.user_info.output_name}<br>
			<b>{l i='field_permitted_for' gid='media'}</b>: {ld_option i='permissions' gid='media' option=$item.permissions}
		</td>
		<td>
                    {if empty($item.owner_info.is_user_deleted)}<a href="{$site_url}admin/users/edit/personal/{$item.id_owner}" target="_blank">{$item.owner_info.output_name}</a>{else}{$item.owner_info.output_name}{/if}
		</td>
		<td class="icons">
			{if $item.is_adult eq 0}
				<a href="{$site_url}admin/media/mark_adult_media/{$item.id}" class="adult_icon green"><div title="{l i='mark_adult' gid='media'}">18+</div></a>
			{else}
				<a href="{$site_url}admin/media/unmark_adult_media/{$item.id}" class="adult_icon red"><div title="{l i='unmark_adult' gid='media'}">18+</div></a>
			{/if}
			{*<a class="delete_select_file" href="{$site_url}admin/media/delete_media/{$item.id}"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>*}
			<a class="delete_select_file" data-id="{$item.id}" href="javascript:void(0)"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='packages'}" title="{l i='link_delete_service' gid='packages'}"></a>
		</td>
	</tr>
	{foreachelse}
	<tr><td colspan="4" class="center">{l i='no_media' gid='media'}</td></tr>
	{/foreach}
</table>
{include file="pagination.tpl"}
<script type="text/javascript">
var reload_link = "{$site_url}admin/media/";
var param = "{$param}";

	{literal}
		$(function(){
			$('#grouping_all').bind('click', function(){
				var checked = $(this).is(':checked');
				if(checked){
					$('input.grouping').prop('checked', true);
				}else{
					$('input.grouping').prop('checked', false);
				}
			});
			
			$('#grouping_all').bind('click', function(){
				var checked = $(this).is(':checked');
				if(checked){
					$('input[type=checkbox].grouping').prop('checked', true);
				}else{
					$('input[type=checkbox].grouping').prop('checked', false);
				}
			});
		});
		
		delete_select_block = new loadingContent({
		loadBlockWidth: '620px',
		loadBlockLeftType: 'center',
		loadBlockTopType: 'center',
		loadBlockTopPoint: 100,
		closeBtnClass: 'w'
		}).update_css_styles({'z-index': 2000}).update_css_styles({'z-index': 2000}, 'bg');
		
		$('.delete_select_file').unbind('click').click(function(){
			var id_media = $(this).attr('data-id');
			var data = new Array();
			
			var checked = $('input#media-'+id_media).is(':checked');
			if(checked){
				$('input#media-'+id_media).prop('checked', false);
				$('input#media-'+id_media).prop('checked', true);
			}else{
				$('input#media-'+id_media).prop('checked', true);
			}
			
			data[0] = id_media;
			
			if(data.length > 0){
				$.ajax({
					url: site_url + 'admin/media/ajax_confirm_select/delete_select_block',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block('{/literal}{l i="no_media" gid="media" type="js"}{literal}', 'error');
			}
			
		});
		
		$('#delete_select_block').unbind('click').click(function(){
			var data = new Array();
			
			$('.grouping:checked').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + 'admin/media/ajax_confirm_select/delete_select_block',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block('{/literal}{l i="no_media" gid="media" type="js"}{literal}', 'error');
			}
		});
		
		$('#mark_adult_select_block').unbind('click').click(function(){
			var data = new Array();
			
			$('.grouping:checked').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + 'admin/media/ajax_mark_adult_select',
					cache: false,
					type: "POST",
					data: {file_ids : data},
					success: function(data){
						reload_this_page('index/'+param);
					}
				});
			}else{
				error_object.show_error_block('{/literal}{l i="no_media" gid="media" type="js"}{literal}', 'error');
			}
		});
		
		$('#unmark_adult_select_block').unbind('click').click(function(){
			var data = new Array();
			
			$('.grouping:checked').each(function(i){
				data[i] = $(this).val();
			});
			if(data.length > 0){
				$.ajax({
					url: site_url + 'admin/media/ajax_unmark_adult_select',
					cache: false,
					type: "POST",
					data: {file_ids : data},
					success: function(data){
						reload_this_page('index/'+param);
					}
				});
			}else{
				error_object.show_error_block('{/literal}{l i="no_media" gid="media" type="js"}{literal}', 'error');
			}
		});
		
		function reload_this_page(value){
			var link = reload_link + value;
			location.href=link;
		}
	{/literal}
</script>

{/strip}

{include file="footer.tpl"}
