{l i='sort_by' gid=$sort_module}&nbsp;
<select id="sorter-select-{$sort_rand}">
	{foreach item=item key=key from=$sort_links}
		<option value="{$key}"{if $key eq $sort_order} selected{/if}>{$item}</option>
	{/foreach}
</select>
<i id="sorter-dir-{$sort_rand}" data-role="sorter-dir" class="fa-long-arrow {if $sort_direction eq 'ASC'}up{else}down{/if} icon-big pointer plr5"></i>
<a href="{$sort_url}" id="sorter-link-{$sort_rand}" class="hide">Go!</a>
<script type='text/javascript'>{literal}
	$('#sorter-select-{/literal}{$sort_rand}{literal}').unbind('change').bind('change', function(){
		var url = $('#sorter-link-{/literal}{$sort_rand}{literal}').attr('href') + '/' + $(this).find('option:selected').val() + '/ASC';
		$('#sorter-link-{/literal}{$sort_rand}{literal}').attr('href', url).trigger('click');
	});
	$('#sorter-dir-{/literal}{$sort_rand}{literal}').unbind('click').bind('click', function(){
		var url = $('#sorter-link-{/literal}{$sort_rand}{literal}').attr('href') + '/' + $('#sorter-select-{/literal}{$sort_rand}{literal} option:selected').val() + '/{/literal}{if $sort_direction eq 'ASC'}DESC{else}ASC{/if}{literal}';
		$('#sorter-link-{/literal}{$sort_rand}{literal}').attr('href', url).trigger('click');
	});
</script>{/literal}