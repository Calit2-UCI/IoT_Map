{if !$ajax}
	<script type='text/javascript'>{literal}
		$(function(){
			loadScripts(
				"{/literal}{js module='comments' file='comments.js' return='path'}{literal}", 
				function(){
					comments = new Comments({
						siteUrl: site_url,
						lng: $.parseJSON('{/literal}{$js_lng|escape:'javascript'}{literal}')
					});
				},
				'comments',
				{async: false, cache: false}
			);
		});
	</script>{/literal}

<div class="comments"{if isset($comments.max_height)} style="max-height: {$comments.max_height}; overflow-y: auto;"{/if} id="comments_{$comments.gid}_{$comments.id_obj}">
{/if}
{strip}
	{if $comments.hidden}
		<div>
			{if $comments.show_form || $comments.count_all || !$comments.calc_count}
				<a href="#" onclick="comments.loadComments('{$comments.gid|escape:'javascript'}', '{$comments.id_obj|escape:'javascript'}', $('#comments_{$comments.gid|escape:'javascript'}_{$comments.id_obj|escape:'javascript'}')); event.preventDefault();">{l i='comments' gid='comments'}{if $comments.calc_count}&nbsp;(<span class="counter">{$comments.count_all}</span>){/if}</a>
			{else}
				{l i='comments' gid='comments'}{if $comments.calc_count}&nbsp;(<span class="counter">{$comments.count_all}</span>){/if}
			{/if}
		</div>
	{else}
		<div id="comments_form_cont_{$comments.gid}_{$comments.id_obj}" class="form_wrapper" gid="{$comments.gid}" id_obj="{$comments.id_obj}">
			<div>
				{if $comments.show_form || $comments.count_all}
					<a href="#" onclick="$('#comments_slider_{$comments.gid}_{$comments.id_obj}').slideToggle(150); event.preventDefault();">{l i='comments' gid='comments'}&nbsp;(<span class="counter">{$comments.count_all}</span>)</a>
				{else}
					{l i='comments' gid='comments'}&nbsp;(<span class="counter">{$comments.count_all}</span>)
				{/if}
			</div>
			
			<div id="comments_slider_{$comments.gid}_{$comments.id_obj}" class="comments_slider">
				{if $comments.show_form}
					<div>
						<div class="edit_block post-form wide resize">
							{if $user_session_data.auth_type != 'user'}
								<div class="b" style="height: 0;"><input type="text" value="" name="email" autocomplete="off" /></div>
							{/if}
							<div>
								<div class="form-input">
									<div class="table-div">
										{if $user_session_data.auth_type != 'user'}
											<div class="input"><input type="text" value="" name="user_name" placeholder="{l i='your_name' gid='comments'}" /></div>
										{/if}
										<div class="text"><textarea maxcount="{$comments_type.settings.char_count}" placeholder="{l i='add_comment' gid='comments'}"></textarea></div>
										<div class="char-counter"><span class="char_counter">{$comments_type.settings.char_count}</span></div>
										<div><input type="button" value="{l i='btn_send' gid='start'}" onclick="comments.addComment('{$comments.gid}', '{$comments.id_obj}');" /></div>
									</div>
								</div>
								{*<div class="ptb5">{l i='chars_left' gid='comments'}:&nbsp;<span class="char_counter">{$comments_type.settings.char_count}</span></div>*}
							</div>
						</div>
					</div>
				{/if}
				<div id="comments_cont_{$comments.gid}_{$comments.id_obj}" class="comments_wrapper">
					{include file="comments_block.tpl" module="comments"}
				</div>
				{if $comments.bd_min_id != $comments.min_id}
					<div class="more_button">
						<input type="button" value="{l i='show_more' gid='comments'}" onclick="comments.loadComments('{$comments.gid}', '{$comments.id_obj}');" />
					</div>
				{/if}
			</div>
		</div>
	{/if}
{if !$ajax}
</div>
{/if}
{/strip}