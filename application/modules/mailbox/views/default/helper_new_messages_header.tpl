<li{if !$messages_count} class="hide-always"{/if}>
	<a href="{seolink module='mailbox' method='inbox'}">
		{l i='header_new_messages' gid='mailbox'}: <b class="summand fright inbox_new_message">{$messages_count}</b>
	</a>
</li>
