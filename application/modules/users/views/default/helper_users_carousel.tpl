{strip}
{if $users_carousel_data.users}
	{if $users_carousel_data.header}<h2>{$users_carousel_data.header}</h2>{/if}

	<script type="text/javascript">{literal}
		$(function(){
			loadScripts(
				["{/literal}{js file='jquery.jcarousel.min.js' return='path'}{literal}", "{/literal}{js file='init_carousel_controls.js' return='path'}{literal}"],
				function(){
					var data = {/literal}{json_encode data=$users_carousel_data.carousel}{literal};
					$('#users_carousel_'+data.rand).removeClass('hide');
					
					carousel{/literal}{$users_carousel_data.rand}{literal} = $('#users_carousel_'+data.rand).find('.jcarousel').jcarousel({
						animation: 250
					});

					carousel_controls{/literal}{$users_carousel_data.rand}{literal} = new init_carousel_controls({
						carousel: carousel{/literal}{$users_carousel_data.rand}{literal},
						carousel_images_count: data.visible,
						carousel_total_images: data.users_count,
						btnNext: '#directionright_'+data.rand,
						btnPrev: '#directionleft_'+data.rand,
						scroll: data.scroll
					});
				},
				['carousel_controls{/literal}{$users_carousel_data.rand}{literal}', 'carousel{/literal}{$users_carousel_data.rand}{literal}']
			);
		});
	</script>{/literal}

	{assign var=users_carousel_thumb_name value=$users_carousel_data.carousel.thumb_name}
	<div id="users_carousel_{$users_carousel_data.rand}" class="user-gallery carousel-wrapper hide{if $users_carousel_data.carousel.class} {$users_carousel_data.carousel.class}{/if}">
		<div id="directionleft_{$users_carousel_data.rand}" class="op direction left hover-icon">
			<div class="fa-chevron-left icon-big edge hover"></div>
		</div>
		<div class="dimp100 box-sizing plr50">
			<div class="jcarousel" dir="{$_LANG.rtl}">
				<ul>
					{foreach from=$users_carousel_data.users item=item}
						<li{if !empty($item.carousel_data.class)} class="{$item.carousel_data.class}"{/if}{if !empty($item.carousel_data.id)} id="{$item.carousel_data.id}"{/if}>
							<div class="user">
								<div class="photo">
									<a href="{seolink module='users' method='view' data=$item}"><img src="{$item.media.user_logo.thumbs[$users_carousel_thumb_name]}"/></a>
									{if !empty($item.carousel_data.icon_class)}
										<ins><i class="{$item.carousel_data.icon_class}"></i></ins>
									{else}
										<div class="info">
											<div class="text-overflow"><a href="{seolink module='users' method='view' data=$item}" title="{$item.output_name|escape}">{$item.output_name}</a>, {$item.age}</div>
											{if !empty($item.location)}<div class="text-overflow" title="{$item.location|escape}">{$item.location}</div>{/if}
										</div>
									{/if}
								</div>
							</div>
							<div class="descr hide">
								<div><a href="{seolink module='users' method='view' data=$item}">{$item.output_name}</a>, {$item.age}</div>
								{if $item.location}<div>{$item.location}</div>{/if}
							</div>
						</li>
					{/foreach}
				</ul>
			</div>
		</div>
		<div id="directionright_{$users_carousel_data.rand}" class="op direction right hover-icon">
			<div class="fa-chevron-right icon-big edge hover"></div>
		</div>
	</div>
{/if}
{/strip}
<script>{literal}
	$('#users_carousel_{/literal}{$users_carousel_data.rand}{literal}').not('.w-descr')
		.off('mouseenter', '.photo').on('mouseenter', '.photo', function(){
			$(this).find('.info').stop().slideDown(100);
		}).off('mouseleave', '.photo').on('mouseleave', '.photo', function(){
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
	$('.info', '#users_carousel_{/literal}{$users_carousel_data.rand}{literal}').hide();
</script>{/literal}
