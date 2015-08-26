{foreach item=item from=$lds_data}
		<div class="row">
			<div class="h">{ld_header i=$item.ds gid=$item.module}: </div>
			<div class="v">
				<select name="lds[{$item.ds}]">
					<option value="0"></option>
					{foreach item=ds_item key=ds_key from=$item.reference.option}<option value="{$ds_key}"{if $ds_key eq $item.value} selected{/if}>{$ds_item}</option>{/foreach}
				
				</select>
			</div>
		</div>
{/foreach}