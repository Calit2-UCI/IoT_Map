{strip}
<div>
	<div class="fleft">
		{if $data.media}
			<a href="{$data.media.mediafile.file_url}" target="_blank"><img src="{$data.media.mediafile.thumbs.small}" /></a>
		{/if}
		{if $data.video_content}
			<span onclick="vpreview = new loadingContent({literal}{'closeBtnClass': 'w'}{/literal}); vpreview.show_load_block('{$data.video_content.embed|escape}');"><img class="pointer" src="{$data.video_content.thumbs.small}"/></span>
		{/if}
		<br>
		{$data.fname}
	</div>
	<div class="fleft">
		<b>{l i='field_permitted_for' gid='media'}</b>: {ld_option i='permissions' gid='media' option=$data.permissions}
	</div>
</div>
{/strip}