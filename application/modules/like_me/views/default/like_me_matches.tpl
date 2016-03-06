<div class="view-user">
	<div class="short-line"></div>
	<div class="user-gallery big w-actions like_me-item">
		{foreach item=item from=$user_data.content}
			<div class="item fleft">
				<div class="user">
					<div class="photo">
						<div class="actions">
                            <div class="pl10 fleft">
                                <div class="btns black">
                                    <span>
                                        {foreach item='set' key='key' from=$settings}
                                            {if !empty($set.helper)}
                                                {block name=$set.helper module=$key id_user=$item.id user_id=$item.id id_contact=$item.id}
                                            {/if}
                                        {/foreach}
                                    </span>
                                </div>
                            </div>
                            <div class="fright fright pr20 mr20">
                                <div class="btns black">
                                    <span>
                                        <a href="{seolink module='like_me' method='remove' profile_id=$item.id}" class="link-r-margin" title="{l i='link_action_remove' gid='like_me'}">
                                            <i class="icon-trash icon-big edge hover"></i>
                                        </a>
                                    </span>
                                </div>
                            </div>
						</div>
						<a href="{seolink module='users' method=view data=$item}">
							<img alt="" src="{$item.media.user_logo.thumbs.great}" />
						</a>
						<div class="info">
							<!--div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a>, {$item.age}</div--> <!--remove age-->
							<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a></div>
							{if $item.address}<div class="text-overflow">{$item.address}</div>{/if}
						</div>
					</div>
				</div>
			</div>
		{foreachelse}
            <div class="m10">
                <div class="mt20"><h2>{l i='empty_list' gid='like_me'}</h2></div>
                <div class="mt20">
                    <span class="mr20"><input type="button" value="{l i='field_play_more_perfect' gid='like_me'}" id="go-perfect"></span>
                </div>
            </div>
		{/foreach}
	</div>
</div>
{if !empty($user_data.have_more)}
<div class="clr"></div>
<div class="match-button-content">
	<input id="show_more" type="button" value="{l i='button_show_more' gid='like_me'}">
</div>
{/if}