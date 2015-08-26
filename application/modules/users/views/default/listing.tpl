{include file="header.tpl"}

<div class="content-block">

	<h1>{seotag tag='header_text'}</h1>

	<p class="header-comment">{l i='text_listing_edit' gid='users'}</p>

	<div class="sorter" id="sorter_block">
		<ul>
			<li>{l i='filter_header' gid='start'}:</li>
			<li{if $filter eq 'all'} class="active"{/if}><a href="{seolink module='users' method='listing' data='all'}">{l i='filter_all' gid='users'}{if $filter ne 'all'}({$filter_data.all}){/if}</a></li>
			<li{if $filter eq 'icon'} class="active"{/if}><a href="{seolink module='users' method='listing' data='icon'}">{l i='filter_icon' gid='users'}{if $filter ne 'icon'}({$filter_data.icon}){/if}</a></li>
		</ul>
		<div class="clr"></div>
	</div>

	<div>
		{include file="pagination.tpl"}
		{foreach item=item from=$users}
		<div class="user-block">
			{l i='text_user_logo' gid='users' type='button' assign='text_user_logo' user_name=$item.output_name}
			{if $item.media.user_logo.thumbs.middle}<div class="logo-img"><a href="{seolink module='users' method='view' data=$item}"><img src="{$item.media.user_logo.thumbs.middle}" alt="{$text_user_logo}" title="{$text_user_logo}" /></a></div><div class="clr"></div>{/if}
			<a href="{seolink module='users' method='view' data=$item}" class="nick" alt="{$item.nickname|escape}" title="{$item.nickname|escape}">{$item.nickname|truncate:10:"...":true}</a><br>
		</div>
		{/foreach}
		<div class="clr"></div>
		{include file="pagination.tpl"}
	</div>

</div>
<div class="clr"></div>
{include file="footer.tpl"}
