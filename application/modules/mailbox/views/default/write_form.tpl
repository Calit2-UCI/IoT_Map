		{l i='input_default' gid='start' type='button' assign='input_default'}
		<form action="" method="post" id="write_form">
			{if $type ne 'short'}
			<div class="r">
				<div class="f">{l i='field_user' gid='mailbox'}</div>
				<div class="v">
					{capture assign='user_callback'}{strip}{literal}
						function(variable, value, data){
							$('#user_hidden').val(variable.toString()).change();
							$('#user_text').val(value);
						}
					{/literal}{/strip}{/capture}
					
					{if $message.recipient.id>0}
					{assign var=id_to_user value=$message.id_to_user}
					{/if}
					<input type="text" name="name_to_user" id="user_text" autocomplete="off" value="{if $message.recipient.id}{$message.recipient.output_name|strip_tags} {if $message.recipient.output_name != $message.recipient.nickname}({$message.recipient.nickname|strip_tags}){/if}{/if}" class="long" placeholder="{l i='input_default' gid='start' type='button'}">&nbsp;
					{*block name='friend_input' module='users_lists' id_user=$id_to_user var_user='id_to_user' values_callback=$user_callback*}
					{block name='friend_input' module='friendlist' id_user=$id_to_user var_user='id_to_user' values_callback=$user_callback}
					<input type="hidden" name="id_to_user" id="user_hidden" value="{$id_to_user|escape}">
					<script>{literal}
						$(function(){
							loadScripts(
								"{/literal}{js file='autocomplete_input.js' return='path'}{literal}",
								function(){
									user_autocomplete = new autocompleteInput({
										siteUrl: '{/literal}{$site_url}{literal}',
										dataUrl: 'users/ajax_get_users_data',
										id_text: 'user_text',
										id_hidden: 'user_hidden',
										rand: '{/literal}{$rand}{literal}',
										format_callback: function(data){
											return data.output_name + (data.nickname != data.output_name ? ' (' + data.nickname + ')' : '');
										}
									});
								},
								'user_autocomplete'
							);
						});
					{/literal}</script>
				</div>
			</div>
			{/if}{/strip}
			{if !$is_reply}
			<div class="r">
				<div class="f">{l i='field_subject' gid='mailbox'}</div>
				<div class="v"><input type="text" name="subject" value="{$message.subject|escape}" placeholder="{l i='input_default' gid='start' type='button'}" autocomplete="false" {if $type ne 'short'}class="long"{/if}></div>
			</div>
			{/if}
			<div class="r">
				<div class="f">{l i='field_message' gid='mailbox'}</div>
				<div class="v"><textarea name="message" row="5" cols="80" {if $type ne 'short'}class="long"{/if} autocomplete="false">{/strip}{$message.message|escape}{strip}</textarea></div>
			</div>{strip}
			{if $type ne 'short' || $is_reply}
			<div class="r">
				<div class="f">{l i='field_attach' gid='mailbox'}</div>
				<div class="v">
					<input type="file" name="attach" class="attach" id="attach" multiple="true" size="40">
					<button id="btn_attach">{l i='btn_upload' gid='start'}</button>
					<script>{literal}
						$(function(){
							var allowed_mimes = {/literal}{json_encode data=$attach_settings.allowed_mimes}{literal};
							loadScripts(
								"{/literal}{js file='uploader.js' return='path'}{literal}", 
								function(){
									au = new uploader({
										siteUrl: site_url,
										zoneId: 'attachbox',
										fileId: 'attach',
										formId: 'write_form',
										sendType: 'file',
										sendId: 'btn_attach',
										messageId: 'attach-input-error',
										maxFileSize: '{/literal}{$attach_settings.max_size}{literal}',
										mimeType: allowed_mimes,
										cbOnSend: function(noFile){
											mb.save_{/literal}{if $is_reply}reply{else}message{/if}{literal}(function(data){
												var options = {uploadUrl: 'mailbox/upload_attach/'+data.{/literal}{if $is_reply}reply{else}message{/if}{literal}_id};
												if(noFile){
													au.sendNoFileApi(options);
												}else{
													au.send(options);
												}
											}, true);
										},
										cbOnUpload: function(name, data){
											var attaches = $('#attaches');
											attaches.find('ul').append('<li><a href="'+data.link+'" target="_blank">'+name+'</a><br>'+data.size+'<div class="act"><a href="javascript:void(0);" class="btn_delete_upload fright" data-id="'+data.id+'"><i class="icon-remove icon-big"></i></a></div></li>');
											attaches.show();
											error_object.show_error_block('{/literal}{l i='success_attach_uploaded' gid='mailbox' type='js'}{literal}', 'success');
										},
										cbOnError: function(data){
											if(data.errors.length){
												error_object.show_error_block(data.errors, 'error');
											}
										},
										cbOnProcessError: function(data){
											error_object.show_error_block(data, 'error');
										}
									});
								},
								['au'],
								{async: false}
							);
						});
					{/literal}</script>
				</div>
			</div>
			<div class="attachbox {if !$message.attaches}hide{/if}" id="attaches">
				<ul>
					{foreach item=item from=$message.attaches}
					<li>
						<a href="{$item.link}" target="_blank">{$item.name}</a><br>{$item.size}
						<div class="act"><a href="javascript:void(0);" class="btn_delete_upload fright" data-id="{$item.id}"><i class="icon-remove icon-big"></i></a></div>
					</li>
					{/foreach}
				</ul>
				<div class="clr"></div>
			</div>
			{/if}
			<div class="b">
				<input type="button" name="btn_send" value="{l i='btn_send' gid='mailbox' type='button'}" id="btn_send_message" class="btn">
				{if $type eq 'short' && !$is_reply}
					<a href="javascript:void(0);" class="fright" id="write_message_full">{l i='link_message_form' gid='mailbox'}</a> 
				{elseif !$write_message}
					<a href="{seolink module='mailbox' method=$folder}" class="btn-link"><i class="icon-arrow-left icon-big edge hover"></i><i>{l i='link_back_to_'+$folder gid='mailbox'}</i></a>
				{/if}
			</div>
			<div class="clr"></div>
			{if $type eq 'short' && !$is_reply}
			<input type="hidden" name="id_to_user" value="{$user_id}">
			{/if}
		</form>
		
