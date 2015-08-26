{strip}
{if $page_type eq 'cute'}
	<div class="pages">
		<div class="inside">
			<ins class="current">{l i='text_pages' gid='start'} {$page_data.cur_page} {l i='text_of' gid='start'} {$page_data.total_pages}</ins>
			<ins class="prev{if $page_data.prev_page eq $page_data.cur_page} gray{/if}"><a href="{$page_data.base_url}{$page_data.prev_page}">&nbsp;</a></ins>
			<ins class="next{if $page_data.next_page eq $page_data.cur_page} gray{/if}"><a href="{$page_data.base_url}{$page_data.next_page}">&nbsp;</a></ins>
		</div>
	</div>
{elseif $page_type eq 'full' && $page_data.nav}
	<div class="line pages">
		<div class="inside">{$page_data.nav}</div>
	</div>
{/if}
{/strip}
