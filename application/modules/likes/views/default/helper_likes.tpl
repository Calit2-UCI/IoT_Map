<script>{literal}
	$(function(){
		var data = {/literal}{json_encode data=$likes_helper_data}{literal};
		loadScripts(
			'{/literal}{js module="likes" file='likes.js' return='path'}{literal}',
			function(){
				likes = new Likes({
					siteUrl: site_url,
					likeTitle: data.like_title,
					canLike: data.can_like
				});
			},
			'',
			{async: true}
		);
	});
</script>{/literal}