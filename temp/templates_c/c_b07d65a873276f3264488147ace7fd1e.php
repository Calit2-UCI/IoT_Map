<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:00:53 Pacific Daylight Time */ ?>

<div id="im_chat">
	<div id="im_chat_btn" data-status-class="<?php echo $this->_vars['im_data']['user_status']['current_site_status_text']; ?>
" class="bg-input_color hide side-btn im-chat-btn hover-icon <?php echo $this->_vars['im_data']['user_status']['current_site_status_text'];  if ($this->_vars['im_data']['user_status']['current_site_status_text'] == 'offline'): ?> bg-delimiter_color<?php endif; ?>">
		<div class="overlay">
			<ins><i data-flash="0" class="fa-comment w icon-big edge hover"></i></ins>
			<span><?php echo l('im_chat', 'im', '', 'text', array()); ?></span>
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
				<div class="ptb10"><textarea class="box-sizing" placeholder="<?php echo l('text_placeholder', 'im', '', 'text', array()); ?>"></textarea></div>
				<div id="im_msg_btns" class="table-div vmiddle wp100">
					<div><input type="button" name="sendbtn" value="<?php echo l('btn_send', 'start', '', 'text', array()); ?>" />&nbsp;(Ctrl+Enter)</div>
					<div class="righted">
						<ins data-button="profile" class="fa-user edge hover icon-big zoom30" title="<?php echo l('title_view_profile', 'im', '', 'text', array()); ?>"></ins>
						<ins data-button="clear" class="fa-file-text-alt edge hover icon-big zoom30" title="<?php echo l('title_clear_history', 'im', '', 'text', array()); ?>"><i class="fa-mini-stack icon-remove"></i></ins>
					</div>
				</div>
			</div>
		</div>
		
		<div id="im_contact_list" class="im-contact-list">
			<div class="im-header box-sizing">
				<ins id="im_chat_contact_list_btn" class="a icon-remove icon-big"></ins>
				<div class="fright">
					<select name="site_status" autocomplete="off">
						<?php if (is_array($this->_vars['im_data']['statuses']) and count((array)$this->_vars['im_data']['statuses'])): foreach ((array)$this->_vars['im_data']['statuses'] as $this->_vars['key'] => $this->_vars['status']): ?>
							<option value="<?php echo $this->_vars['status']['val']; ?>
"<?php if ($this->_vars['im_data']['user_status']['site_status'] == $this->_vars['status']['val']): ?> selected<?php endif; ?>><?php echo $this->_vars['status']['lang']; ?>
</option>
						<?php endforeach; endif; ?>
					</select>
				</div>
			</div>
			<div class="im-content box-sizing">
				<div class="im-scroller box-sizing im-scroll"></div>
			</div>
			<div class="im-bottom box-sizing">
				<input id="im_contact_list_search" type="text" class="box-sizing" placeholder="<?php echo l('search_placeholder', 'im', '', 'text', array()); ?>" />
			</div>
		</div>
	</div>
	<div id="im_info_popup" class="im-info-popup hide"></div>
</div>


<script><?php echo '
	loadScripts(
		"';  echo tpl_function_js(array('module' => 'im','file' => 'im.js','return' => 'path'), $this); echo '",
		function(){
			var data = ';  echo $this->_vars['im_json_data'];  echo ';
			im = new Im({
				site_url: site_url,
				new_msgs: data.new_msgs,
				statuses: data.statuses,
				id_user: parseInt(data.id_user),
				user_name: data.user_name,
				site_status: parseInt(typeof data.user_status !== \'undefined\' ? data.user_status.site_status : 0),
				age_lng: data.age_lang,
				history_lng: data.history_lang,
				clear_confirm_lng: data.clear_confirm_lang,
				available_view_url: "';  echo tpl_function_js(array('file' => 'available_view.js','return' => 'path'), $this); echo '"
			});
		},
		\'\',
		{async: true}
	);
</script>'; ?>
