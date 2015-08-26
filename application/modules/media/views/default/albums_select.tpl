{strip}
{if $albums_list}
	<select name="album_id" id="album_id">
		<option value="0">{l i='please_select' gid='media'}</option>
		{foreach item=item key=key from=$albums_list}
			<option value="{$item.id}">{$item.name|truncate:21:'...':true}({$item[$albums_count_field]})</option>
		{/foreach}
	</select>
{/if}
{/strip}