<div id="im_chat">
	<div id="im_chat_btn" data-status-class="{$im_data.user_status.current_site_status_text}" class="bg-input_color hide side-btn im-chat-btn hover-icon {$im_data.user_status.current_site_status_text}{if $im_data.user_status.current_site_status_text == 'offline'} bg-delimiter_color{/if}">
		<div class="overlay">
			<ins><i data-flash="0" class="fa-comment w icon-big edge hover"></i></ins>
			<span>{l i='im_chat' gid='im'}</span>
		</div>
	</div>

	<div id="im_panel" class="hide im">
		<div id="im_messages_window" class="im-msg-window">
			<div class="im-header box-sizing">
				<ins class="a fa-remove icon-big"></ins>&nbsp;
				<span></span>
			</div>
			<div class="im-content box-sizing">
				<div class="im-scroller box-sizing im-scroll"></div>
			</div>
			<div class="im-bottom box-sizing">
				<div class="ptb10"><textarea class="box-sizing" placeholder="{l i='text_placeholder' gid='im'}"></textarea></div>
				<div id="im_msg_btns" class="table-div vmiddle wp100">
					<div><input type="button" name="sendbtn" value="{l i='btn_send' gid='start'}" />&nbsp;(Ctrl+Enter)</div>
					<div class="righted">
						<ins data-button="profile" class="fa-user edge hover icon-big zoom30" title="{l i='title_view_profile' gid='im'}"></ins>
						<ins data-button="clear" class="fa-file-text-alt edge hover icon-big zoom30" title="{l i='title_clear_history' gid='im'}"><i class="fa-mini-stack icon-remove"></i></ins>
					</div>
				</div>
			</div>
		</div>
		
		<div id="im_contact_list" class="im-contact-list">
			<div class="im-header box-sizing">
				<ins id="im_chat_contact_list_btn" class="a icon-remove icon-big"></ins>
				<div class="fright">
					<select name="site_status" autocomplete="off">
						{foreach from=$im_data.statuses item=status key=key}
							<option value="{$status.val}"{if $im_data.user_status.site_status == $status.val} selected{/if}>{$status.lang}</option>
						{/foreach}
					</select>
				</div>
			</div>
			<div class="im-content box-sizing">
				<div class="im-scroller box-sizing im-scroll"></div>
			</div>
			<div class="im-bottom box-sizing">
				<input id="im_contact_list_search" type="text" class="box-sizing" placeholder="{l i='search_placeholder' gid='im'}" />
			</div>
		</div>
	</div>
	<div id="im_info_popup" class="im-info-popup hide"></div>
</div>


<script>{literal}
	loadScripts(
		"{/literal}{js module='im' file='im.js' return='path'}{literal}",
		function(){
			var data = {/literal}{$im_json_data}{literal};
			im = new Im({
				site_url: site_url,
				new_msgs: data.new_msgs,
				statuses: data.statuses,
				id_user: parseInt(data.id_user),
				user_name: data.user_name,
				site_status: parseInt(typeof data.user_status !== 'undefined' ? data.user_status.site_status : 0),
				age_lng: data.age_lang,
				history_lng: data.history_lang,
				clear_confirm_lng: data.clear_confirm_lang,
				available_view_url: "{/literal}{js file='available_view.js' return='path'}{literal}"
			});
		},
		'',
		{async: true}
	);
</script>{/literal}