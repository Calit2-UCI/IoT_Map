{strip}
<div class="checkBox" id="{$cb_id}_cbox" iname="{$cb_input}">
	{foreach item=item key=key from=$cb_value}
		<div class="input">
			<div class="box{if $item.checked} checked{/if}" gid="{$key}"></div>{if 1}<div class="label" gid="{$key}">{$item.name}</div>{/if}
			{if $item.checked}<input type="hidden" name="{$cb_input}{if $cb_count > 1}[]{/if}" value="{$key}" />{/if}
		</div>
	{/foreach}
	{if $cb_display_group_methods}
		<div class="clr"></div>
		<span id="{$cb_id}_cbox_check_all" class="a" onclick="$(this).parent().find('input[type=checkbox]').prop('checked', true);">{l i='select_all' gid='start'}</span>&nbsp;|&nbsp;
		<span id="{$cb_id}_cbox_uncheck_all" class="a" onclick="$(this).parent().find('input[type=checkbox]').prop('checked', false);">{l i='unselect_all' gid='start'}</span> 
	{/if}
</div>
{/strip}
<script>checkboxes.push('{$cb_id}');</script>