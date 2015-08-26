{strip}
	{assign var="field_gid" value=$field.field.gid}
	{if !$field_name}{assign var="field_name" value=$field.field.gid}{/if}
	{if $field.field.type eq 'select'}
		{l i='select_default' gid='start' assign='default_select_lang'}
		{if $field.settings.search_type eq 'one'}
			{selectbox input=$field_name id=$field_name+'_select' value=$field.field_content.options.option selected=$data[$field_name] default=$default_select_lang}
		{else}
			{checkbox input=$field_name id=$field_name+'_select' value=$field.field_content.options.option selected=$data[$field_name]}
		{/if}
	{elseif $field.field.type eq 'multiselect'}
		{if $field.field_content.settings_data_array.view_type eq 'mselect'}
			{selectbox input=$field_name id=$field_name+'_select' value=$field.field_content.options.option selected=$data[$field_name] default=$default_select_lang}
		{else}
			{checkbox input=$field_name id=$field_name+'_select' value=$field.field_content.options.option selected=$field.field_content.value group_methods=1}
		{/if}
	{elseif $field.field.type eq 'text'}
		{if $field.settings.search_type eq 'number' && $field.settings.view_type eq 'range'}
			{assign var="field_gid_min" value=$field_name'_min'}
			{assign var="field_gid_max" value=$field_name'_max'}
			<input type="text" name="{$field_name}_min" class="short" value="{$data[$field_gid_min]}">&nbsp;-&nbsp;
			<input type="text" name="{$field_name}_max" class="short" value="{$data[$field_gid_max]}">
		{elseif $field.settings.search_type eq 'number'}
			<input type="text" name="{$field_name}" class="short" value="{$data[$field_name]}">
		{else}
			<input type="text" name="{$field_name}" value="{$data[$field_name]}">
		{/if}
	{elseif $field.field.type eq 'range'}
		<div class="w200">
			{if $field.settings.search_type eq 'range'}
				{assign var="field_gid_min" value=$field_name'_min'}
				{assign var="field_gid_max" value=$field_name'_max'}
				{slider id=$field_name+'_slider' min=$field.field_content.settings_data_array.min_val max=$field.field_content.settings_data_array.max_val value_min=$data[$field_gid_min] value_max=$data[$field_gid_max] field_name_min=$field_name+'_min' field_name_max=$field_name+'_max'}
			{else $field.settings.search_type eq 'number'}
				<input type="text" name="{$field_name}" class="short" value="{$data[$field_name]}">
			{/if}
		</div>
	{elseif $field.field.type eq 'textarea'}
		<input type="text" name="{$field_name}" value="{$data[$field_name]}">
	{elseif $field.field.type eq 'checkbox'}
		{if $field.field_content.value}{assign var='chbx_field_value' value=1}{else}{assign var='chbx_field_value' value=0}{/if}
		{checkbox input=$field_name id=$field_name+'_select' value=$chbx_field_value selected=$data[$field_name]}
	{/if}
{/strip}