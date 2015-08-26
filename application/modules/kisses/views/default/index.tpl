{include file="header.tpl"}

{strip}
<div class="content-block kisses">
	<h1>{if $kiss_section eq 'inbox'}{l i='inbox' gid='kisses'}{else}{l i='outbox' gid='kisses'}{/if}</h1>
	<div id="kisses_content">
	
	<div class="tabs tab-size-15 noPrint">
		<ul>
			<li{if $kiss_section eq 'inbox'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='kisses' method='inbox'}">{l i='inbox' gid='kisses'}</a></li>
			<li{if $kiss_section eq 'outbox'} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='kisses' method='outbox'}">{l i='outbox' gid='kisses'}</a></li>
		</ul>
	</div>
	
	{if $kisses|@count gt 0}
		<div class="sorter short-line" id="sorter_block">
			<div class="fright">{pagination data=$page_data type='cute'}</div>
		</div>
	{/if}
	
	<div class="kisses-list table-div wp100 list">
		{foreach item=kiss from=$kisses}
			<dl class="{if $kiss_section eq 'outbox' && $kiss.mark eq '0'}bold{/if} btn_read_kisses" data-id="{$kiss.id}">
				<dt class="photo w100">
					<a href="{seolink module='users' method='view' data=$kiss.user_id}" target="_blank">
						<img alt="{$text_user_logo}" title="{$text_user_logo}" src="{$kiss.user_logo.small}" />
					</a>
				</dt>
				<dt class="text-overflow w50"><ins class="icon-arrow-{if $kiss_section eq 'inbox'}right{else}left{/if}"></ins></dt>
				<dt class="w150"><img src="{$kiss.images.file_url}" alt="{$kiss.id}"></dt>
				<dt>{$kiss.message}</dt>
				<dt class="righted w200">{$kiss.date_created}</dt>
			</dl>
		{foreachelse}
			<div class="line top empty center">{l i='no_kisses' gid='kisses'}</div>
		{/foreach}
	</div>
	
	{if $kisses|@count gt 0}<div>{pagination data=$page_data type='full'}</div>{/if}
	
	</div>
</div>

<div class="clr"></div>
{/strip}

{include file="footer.tpl"}
