<a href="javascript:void(0);" data-chat="{$chat_gid}" data-user="{$user_id}" class="link-r-margin chat_btn" title="{l i=chat' gid='chats'}"><i class="fa-comments icon-big edge hover"></i></a>
<script>
{literal}
	$(function(){
		$('.chat_btn').on('click', function(){
			switch ($(this).data('chat')) {
			  case 'flashchat':
				document.location.href = site_url + 'chats/';
				break
			  case 'cometchat':
				$('#cometchat_userstab').click();
				$('#cometchat_userlist_' + $(this).data('user')).click();
				break
			}
		});
	});
{/literal}
</script>