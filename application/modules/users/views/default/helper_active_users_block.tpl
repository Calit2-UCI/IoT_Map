{assign var=thumb_name value=$recent_thumb.name}
<div class="highlight p10 mb20 fltl">
    <h1>
		<span class="maxw230 ib text-overflow">{l i='header_active_users' gid='users'}</span>
		<span class="fright" id="refresh_active_users">
			<i class="fa-refresh icon-big edge hover"></i>
		</span>
	</h1>
    {foreach from=$active_users_block_data.users item=item}
       <div class="fleft small ml5 photo">
			{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' replace_array=$item}
            <a href="{seolink module='users' method='view' data=$item}"><img class="small" src="{$item.media.user_logo.thumbs[$thumb_name]}" width="{$recent_thumb.width}" alt="{$text_user_logo}" title="{$text_user_logo}" /></a>
		</div>
    {/foreach}
</div>
<script>{literal}
	$(function(){
		$('#refresh_active_users').unbind('click').click(function(){
			$.ajax({
				url: site_url + 'users/ajax_refresh_active_users',
				type: 'POST',
				data: {count: 16},
				dataType : "html",
				cache: false,
				success: function(data){
					$('#active_users').html(data);
				}
			});
			return false;
		});
	});
</script>{/literal}
