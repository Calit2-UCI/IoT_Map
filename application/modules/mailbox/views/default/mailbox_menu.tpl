{strip}
<div class="tabs tab-size-15 noPrint">
	<ul>
		<li{if $write_message} class="active"{/if}><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='write'}">{l i='write_message' gid='mailbox'}</a></li>
		<li{if $folder eq 'inbox'} class="active"{/if} id="inbox"><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='inbox'}">{l i='inbox' gid='mailbox'} <span class="ind_inbox_new_message {if !$inbox_new_message}hide{/if}">({$inbox_new_message})</span></a></li>
		<li{if $folder eq 'outbox'} class="active"{/if} id="outbox"><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='outbox'}">{l i='outbox' gid='mailbox'}</a></li>
		<li{if $folder eq 'drafts'} class="active"{/if} id="drafts"><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='drafts'}">{l i='drafts' gid='mailbox'}</a></li>
		<li{if $folder eq 'trash'} class="active"{/if} id="trash"><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='trash'}">{l i='trash' gid='mailbox'} {if $trash_new_message > 0}({$trash_new_message}){/if}</a></li>
		<li{if $folder eq 'spam'} class="active"{/if} id="spam"><a data-pjax-no-scroll="1" href="{seolink module='mailbox' method='spam'}">{l i='spam' gid='mailbox'} {if $spam_new_message > 0}({$spam_new_message}){/if}</a></li>
	</ul>
</div>
{/strip}
