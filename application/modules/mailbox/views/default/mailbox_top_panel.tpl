{strip}
<div class="tab-submenu bg-highlight_bg" id="mailbox_top_menu">
	<div class="fleft delim-space">
		<ul>
			<li class="delim-stroke"><label><input type="checkbox" id="select_all_checkbox">&nbsp;{l i='select_all' gid='start'}</label></li>
			{if $folder == 'spam'}<li><s id="unmark_spam" class="fa-exclamation icon-big edge hover zoom30" title="{l i='link_message_unmark_spam' gid='mailbox' type='button'}"><del></del></s></li>{/if}
			{if $folder == 'trash'}<li><s id="mailbox_untrash" class="fa-trash icon-big edge hover zoom30" title="{l i='link_message_untrash' gid='mailbox' type='button'}"><del></del></s></li>{/if}
			{if $folder == 'inbox'}<li><s id="mark_spam" class="fa-exclamation icon-big edge hover zoom30" title="{l i='link_message_mark_spam' gid='mailbox' type='button'}"></s></li>{/if}
			{if $folder != 'trash'}<li><s id="mailbox_delete" class="fa-trash icon-big edge hover zoom30" title="{l i='link_message_delete' gid='mailbox' type='button'}"></s></li>{/if}
			{if $folder == 'trash'}<li><s id="mailbox_delete_forever" class="fa-remove icon-big edge hover" title="{l i='link_message_forever' gid='mailbox' type='button'}"></s></li>{/if}
		</ul>
	</div>
	<div class="fright">
		<ul>
			<li><input type="text" name="mail_keywords" id="mail_keywords" value="{$keywords|escape}" autocomplete="off" class="small_input_text"></li>&nbsp;
			<li><input type="button" value="{l i='btn_search' gid='start'}" name="btn_search" id="btn_search_messages" class="btn_search_messages small_button"></li>
		</ul>
	</div>
</div>
{/strip}
