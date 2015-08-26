{strip}
{foreach item=field key=gid from=$fields_data}
	<div class="r">
		<div class="f">{$field.name}:</div>
		<div class="v">
		{if $field.field_type eq 'select'}
			{if !$field.value}{else}{$field.value|nl2br}{/if}
		{elseif $field.field_type eq 'textarea'}
			{if !$field.value}{else}{$field.value|nl2br}{/if}
		{elseif $field.field_type eq 'text'}
			{if !$field.value}{else}{$field.value|nl2br}{/if}
		{elseif $field.field_type eq 'range'}
			{if !$field.value}{else}{$field.value}{/if}
		{elseif $field.field_type eq 'multiselect'}
			{if !$field.value}{else}{$field.value_str}{/if}
		{elseif $field.field_type eq 'checkbox'}
			{if $field.value}{l i='option_checkbox_yes' gid='start'}{else}{l i='option_checkbox_no' gid='start'}{/if}
		{/if}
		</div>
	</div>
{/foreach}
{/strip}
