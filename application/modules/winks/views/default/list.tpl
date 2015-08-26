{include file="header.tpl"}
<h1>{seotag tag='header_text'}</h1>
{include file="list_top_panel.tpl" module="winks" theme=user}
{strip}
	{if $page_data.total_rows > $page_data.per_page}
		<div class="sorter short-line" id="sorter_block">
			<div class="fright">{pagination data=$page_data type='cute'}</div>
		</div>
	{/if}
	<div id="winks-list" class="winks-list table-div wp100 list">
		<dl id="wink-_user-id_" data-user-id="_user-id_" class="wink hide-always">
			<dt class="text-overflow w100 user">
				<a title="_user-name_" href="_user-link_">
					<img src="_user-img_" alt="_user-name_">
				</a>
			</dt>
			<dt class="text-overflow w200 user">
			</dt>
			<dt class="text-overflow w300 user">
				<a class="btn-wink" data-user-id="_user-id_" data-is-new="true"
				   href="javascript:void(0);" >{l i='wink' gid='winks'}</a>
			</dt>
			<dt></dt>
			<dt></dt>
		</dl>
		{foreach item='item' from=$winks}
			<dl id="wink-{$item.from.id}" data-user-id="{$item.from.id}" class="wink">
				<dt class="text-overflow w100 user">
					<a title="{$item.from.output_name}" href="{$item.from.link}">
						<img src="{$item.from.media.user_logo.thumbs.small}" 
							 alt="{$item.from.output_name}">
					</a>
				</dt>
				<dt class="text-overflow w200 user">
					{l i='winked_at_you' gid='winks'}
				</dt>
				<dt class="text-overflow w300 user">
					<a class="btn-wink-back" data-user-id="{$item.from.id}" 
					   href="javascript:void(0);" >{l i='wink_back' gid='winks'}</a>
				</dt>
				<dt>{$item.date|date_format:$page_data.date_time_format}</dt>
				<dt class="icons">
					<a class="btn-wink-ignore" data-user-id="{$item.from.id}" 
					   title="{l i='wink_ignore' gid='winks'}" href="javascript:void(0);">
						<i class="icon-medium icon-trash"></i>
					</a>
				</dt>
			</dl>
		{/foreach}
	</div>
	{if $page_data.total_rows > $page_data.per_page}
		<div>{pagination data=$page_data type='full'}</div>
	{/if}
	<div id="no-winks"{if $winks}class="hide"{/if}>
		<div class="line center">{l i='welcome_text' gid='winks'}:</div>
		<div class="center btn-block">
			<button id="winks-search-button" type="button">
				{l i='btn_search' gid='winks'}
			</button>
		</div>
	</div>
<script>{literal}
	$(function(){
		loadScripts(
			["{/literal}{js file='winks.js' module='winks' return='path'}{literal}"],
			function(){
				winksObj = new winks({
					siteUrl: site_url,
					titleWink: '{/literal}{l i='wink' gid='winks'}{literal}',
					titleWinkBack: '{/literal}{l i='wink_back' gid='winks'}{literal}',
					errIsPending: '{/literal}{l i='error_is_pending' gid='winks'}{literal}',
					errIsOnList: '{/literal}{l i='error_is_on_list' gid='winks'}{literal}',
					succIgnored: '{/literal}{l i='msg_ignored' gid='winks'}{literal}',
					succWinked: '{/literal}{l i='msg_winked' gid='winks'}{literal}',
					succResponded: '{/literal}{l i='msg_responded' gid='winks'}{literal}'
				});
			},
			'winksObj'
		);
	});
</script>{/literal}
{/strip}
{include file="footer.tpl"}