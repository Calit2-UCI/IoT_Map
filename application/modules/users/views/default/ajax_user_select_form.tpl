<div class="load_content_controller">
	<h1>{l i='header_user_select' gid='users'}</h1>
	<div class="inside">	
		{if $select_data.max_select ne 1}
		<b>{l i='header_users_selected' gid='users'}:</b><br>
		<ul id="user_selected_items" class="user-items-selected">
		{foreach item=item from=$select_data.selected}
		<li><div class="user-block"><input type="checkbox" name="remove_users[]" value="{$item.id}" checked>{$item.nickname}</div></li>
		{/foreach}
		</ul>
		<div class="clr"></div><br>
		{/if}
		<b>{l i='header_user_find' gid='users'}:</b><br>
		<input type="text" id="user_search" class="controller-search">
		<ul class="controller-items" id="user_select_items"></ul>
	
		<div class="controller-actions">
			<div class="fleft"><a href="#" id="user_close_link">{l i='btn_close' gid='start'}</a></div>
			<div id="user_page" class="pages fright"></div>
		</div>
	</div>
</div>
