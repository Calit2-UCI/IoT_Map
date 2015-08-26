{strip}
<input type="hidden" name="{$sb_input}" id="{$sb_id}" value="{$sb_selected}">
<div class="selectBox{if $sb_class} {$sb_class}{/if}" id="{$sb_id}_box">
	<div class="label">{$sb_default|default:'&nbsp;'}</div><div class="arrow"></div>
	<div class="data">
		<ul>
			{if $sb_default}<li gid="0"><span>{$sb_default}</span></li>{/if}
			{foreach item=item key=key from=$sb_value}<li gid="{$key}"><span>{$item}</span></li>{/foreach}
		</ul>
	</div>
</div>
{/strip}
<script>if(typeof selects === 'undefined') selects = []; selects.push('{$sb_id}');</script>