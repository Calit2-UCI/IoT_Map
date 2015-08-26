{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_moderation_menu'}
<div class="actions">&nbsp;</div>

<div id='link' class='menu-level3'>
	<ul>
		<li id="base_link" {if !$type || $type eq 'add'} class="active"{/if}><a href='#' onclick="javascript: openBW('base'); return false;">{l i='header_badwords_base' gid='moderation'}</a></li>
		<li id="check_link"{if $type eq 'check_text'} class="active"{/if}><a href='#' onclick="javascript: openBW('check'); return false;">{l i='header_check_text' gid='moderation'}</a></li>
	</ul>
	<div class="clr"></div>
</div>

<div id="content" class="filter-form">
	<div id="base_content" {if $type eq 'check_text'}style="display: none"{/if}>
		<br>
		<form action="{$site_url}admin/moderation/badwords/add" method="POST">
			{l i='header_add_badword' gid='moderation'}: <input type="text" value="" name="word" class="middle">
			<div class="btn inline">
				<div class="l">
					<input type="submit" name="submit" value="{l i='btn_save' gid='start' type='button'}">
				</div>
			</div>
			<br><i>{l i='add_badword_hint' gid='moderation'}</i>
		</form>
		<div>
			{foreach item=item from=$badwords}
			<div class="badw">
				<div><a href="{$site_url}admin/moderation/delete_badword/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_object' gid='moderation' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='delete_word' gid='moderation'}" title="{l i='delete_word'  gid='moderation'}"></a>{$item.original}</div>
			</div>
			{/foreach}
			<div class="clr"></div>
		</div>
		<div class="clr"></div>
		<br><br><br>
	</div>

	<div class="edit-form" id="check_content" {if $type ne 'check_text'}style="display: none"{/if}>
		<br>
		<form action="{$site_url}admin/moderation/badwords/check_text" method="POST">
			<textarea name="text" class="long">{$check_data.text}</textarea><br>
			<div class="btn">
				<div class="l">
					<input type="submit" name="submit" value="{l i='header_check_text' gid='moderation' type='button'}"><br>
				</div>
			</div>
		</form>
		<br><br><br>
		{if $check_data.modified}
		{l i='header_badword_found' gid='moderation'}: <b>{$check_data.modified.count}</b>
		<div class="bwresult">{$check_data.modified.text}</div>
		{/if}
	</div>
</div>

<script>{literal}
function openBW(type){
	$('#link li').removeClass('active');
	$('#'+type+'_link').addClass('active');
	$('#content > div').hide();
	$('#'+type+'_content').show();
}
{/literal}</script>
{include file="footer.tpl"}
