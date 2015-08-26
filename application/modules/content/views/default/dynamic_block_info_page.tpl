{capture assign='info_page_block'}	
	{block name='content_info_page' module='content' 
		keyword=$block_keyword view=$block_view width=$block_width}
{/capture}
{if $info_page_block|strip_tags|trim}
<div class="dynamic_block_content">
	{$info_page_block}
</div>
{/if}
