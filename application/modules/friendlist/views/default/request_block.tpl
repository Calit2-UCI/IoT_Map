<div class="load_content" id="friendlist_links_request_{$id_dest_user}">
	<h1>{l i='header_request' gid='friendlist'}</h1>
	<div class="popup-form">
		<div class="text">
			<textarea name="comment" class="box-sizing" 
					  placeholder="{l i='message' gid='friendlist'}" 
					  maxcount="{$request_max_chars}"></textarea>
		</div>
		<div class="button">
			<span class="char-counter fleft">{$request_max_chars}</span>
			<input type="button" value="{l i='btn_send' gid='start'}" />
		</div>
	</div>
</div>