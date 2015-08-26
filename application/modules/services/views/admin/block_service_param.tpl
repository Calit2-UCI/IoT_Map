{if $template_data.price_type eq 1}
		<div class="row">
			<div class="h">{l i='field_price' gid='services'}: </div>
			<div class="v"><input type="text" value="{$price}" name="price" class="short"> {block name='currency_format_output' module='start'}</div>
		</div>
{/if}
{foreach item=item from=$template_data.data_admin_array}
		<div class="row">
			<div class="h">{$item.name}: </div>
			{if $item.type eq 'string'}
			<div class="v"><input type="text" value="{$item.value}" name="data_admin[{$item.gid}]"></div>
			{elseif $item.type eq 'int'}
			<div class="v"><input type="text" value="{$item.value}" name="data_admin[{$item.gid}]" class="short"></div>
			{elseif $item.type eq 'price'}
			<div class="v"><input type="text" value="{$item.value}" name="data_admin[{$item.gid}]" class="short"> {block name='currency_format_output' module='start'}</div>
			{elseif $item.type eq 'text'}
			<div class="v"><textarea name="data_admin[{$item.gid}]">{$item.value}</textarea></div>
			{elseif $item.type eq 'checkbox'}
			<div class="v"><input type="checkbox" value="1" name="data_admin[{$item.gid}]" {if $item.value eq '1'}checked{/if}></div>
			{/if}
		</div>
{/foreach}