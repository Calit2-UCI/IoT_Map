{include file='header.tpl' load_type='ui'}
{strip}
	<div class="content-block">
		<h1>{seotag tag='header_text'}</h1>
		<div class="edit_block">
			<div class="view-user">
				<div class="sorter short-line" id="sorter_block">
					<div class="search-line fleft">
						<form method="POST" action="">
							<input type="text" placeholder="{l i='search' gid='blacklist'}" name="search" value="{$search}" />&nbsp;
							<input type="submit" value="{l i='btn_search' gid='start'}" />
						</form>
					</div>
					{if $list}
						<div class="fright">{pagination data=$page_data type='cute'}</div>
					{/if}
				</div>
				<div class="user-gallery big w-actions">
					{foreach item='item' from=$list}
						<div id="block_user_{$item.user.id}" class="item">
							<div class="user">
								<div class="photo">
									<div class="actions">
										<div class="text fright">
											<span>{$item.date_update|date_format:$page_data.date_time_format}</span>
										</div>
										<div class="btns black">
											{if $item.user.id}
												<a id="blacklist_remove_{$item.user.id}" data-pjax="0"
												   data-userid="{$item.user.id}"
												   {*href="{seolink module='blacklist' method='remove' destination_user_id=$item.user.id}"*}
												   class="remove_from_blacklist btn-link link-r-margin" 
												   onclick="event.preventDefault();"
												   title="{l i='action_remove' gid='blacklist'}">
													<i class="icon-user icon-big edge hover w zoom20">
														<i class="icon-mini-stack icon-lock"></i>
													</i>
												</a>
											{/if}
										</div>
									</div>
									<a href="{seolink module='users' method='view' data=$item.user}">
										<img alt="" src="{$item.user.media.user_logo.thumbs.great}" />
									</a>
									<div class="info">
										<div class="text-overflow">
											<a href="{seolink module='users' method='view' data=$item.user}">
												{$item.user.output_name}
											</a>, {$item.user.age}
										</div>
										{if $item.user.location}
											<div class="text-overflow">{$item.user.location}</div>
										{/if}
									</div>
								</div>
							</div>
						</div>
					{foreachelse}
						<div class="item empty">{l i='empty_result' gid='blacklist'}</div>
					{/foreach}
				</div>
				{if $list}<div id="pages_block_2">{pagination data=$page_data type='full'}</div>{/if}
			</div>
		</div>
	</div>
	<div class="clr"></div>
{/strip}

<script>{literal}
	$('.user-gallery').not('.w-descr').find('.photo')
		.off('mouseenter').on('mouseenter', function() {
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave').on('mouseleave', function() {
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});

	$(function() {
		loadScripts(
				["{/literal}{js file='blacklist.js' module='blacklist' return='path'}{literal}"],
				function() {
					blacklistObj = new blacklist({
						siteUrl: site_url,
						toggle: false
					});
				},
				'blacklistObj'
				);
	});
</script>{/literal}
{include file='footer.tpl'}
