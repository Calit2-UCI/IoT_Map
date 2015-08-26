{strip}
{foreach from=$comments.comments item=comment}
	{assign var=comment_id_user value=$comment.id_user}
	<div id="comment_id_{$comment.id}" class="comment_block item b user-content">
		<div class="image">
			{l i='text_user_logo' gid='comments' type='button' assign='text_user_logo' replace_array=$data}
			<a{if !$comments.users[$comment_id_user].is_guest} href="{seolink module='users' method='view' data=$comments.users[$comment_id_user]}"{/if}><img src="{$comments.users[$comment_id_user].user_logo}" alt="{$text_user_logo}" title="{$text_user_logo}" /></a>
		</div>
		<div class="content">
			<h3>
				<span>{if $comments.users[$comment_id_user].is_guest && $comment.user_name}{$comment.user_name}{else}<a href="{seolink module='users' method='view' data=$comments.users[$comment_id_user]}">{$comments.users[$comment_id_user].output_name}</a>{/if}</span>{if !$comment.is_author}<span class="ml10">{block name='mark_as_spam_block' module='spam' object_id=$comment.id type_gid='comments_object' template='minibutton'}</span>{/if}
				&nbsp;&nbsp;<span class="h5 fright">{$comment.date|date_format:$date_format}</span>
			</h3>
			<div>{$comment.text|nl2br}</div>
			<div class="pt10">
				<span>
					{if $comments_type.settings.use_likes}
						<span class="fright mr20">{block name='like_block' module='likes' gid='cmnt'.$comment.id type='button'}</span>
					{/if}
					{if $comment.can_edit}
						<a href="#" onclick="comments.deleteComment('{$comment.id}'); event.preventDefault();">{l i='btn_delete' gid='start'}</a>
					{/if}
					{*if $comments_type.settings.use_likes}
						{if $comment.can_like}
							<a href="#" onclick="comments.like('{$comment.id}'); event.preventDefault();" title="{if $comment.is_liked}{l i='unlike' gid='comments'}{else}{l i='like' gid='comments'}{/if}">{l i='likes' gid='comments'}:&nbsp;<span class="likes_counter">{$comment.likes}</span></a>
						{else}
							{l i='likes' gid='comments'}:&nbsp;<span class="likes_counter">{$comment.likes}</span>
						{/if}
					{/if*}
				</span>
			</div>
		</div>
	</div>
{/foreach}
{/strip}
