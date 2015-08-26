{* Layout is processing by the pre_view-get_likes hook. Be careful changing it. *}
{strip}
<span class="color-link_color like_block{$likes_helper_block_class}" title="[{$likes_helper_gid}_title]" data-gid="{$likes_helper_gid}" data-action="[{$likes_helper_gid}_action]">
	<span id="like_btn_{$likes_helper_gid}" class="like_btn [{$likes_helper_gid}_class]{$likes_helper_btn_class}" href="javascript:void(0)"></span>
	<span class="like_num{$likes_helper_num_class}">[{$likes_helper_gid}]</span>
</span>
{/strip}