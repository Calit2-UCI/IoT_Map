{foreach item=item from=$fields_data}
	<div class="row">
		<div class="h">{$item.name}: </div>
		<div class="v" id="field-{$item.gid}">
			{if $item.field_type eq 'select'}
				{if $item.settings_data_array.view_type eq 'select'}
					<select name="{$item.field_name}" class="middle_long">
						{if $item.settings_data_array.empty_option}<option value="0"{if $value eq 0} selected{/if}>...</option>{/if}
						{foreach item=option key=value from=$item.options.option}<option value="{$value}"{if $value eq $item.value} selected{/if}>{$option}</option>{/foreach}
					</select>
				{else}
					{if $item.settings_data_array.empty_option}<input type="radio" name="{$item.field_name}" value="0"{if $value eq 0} checked{/if} id="{$item.field_name}_0"><label for="{$item.field_name}_0">No select</label><br>{/if}
					{foreach item=option key=value from=$item.options.option}<input type="radio" name="{$item.field_name}" value="{$value}" {if $value eq $item.value} checked{/if} id="{$item.field_name}_{$value}"><label for="{$item.field_name}_{$value}">{$option}</label><br>{/foreach}
				{/if}
			{elseif $item.field_type eq 'multiselect'}
				{if $item.settings_data_array.view_type eq 'mselect'}
					<select name="{$item.field_name}[]" multiple>
						{foreach item=option key=value from=$item.options.option}<option value="{$value}" {in_array match=$value array=$item.value returnvalue="selected"}>{$option}</option>{/foreach}
					</select>
				{else}
					{foreach item=option key=value from=$item.options.option}
						<div class="chbx">
							<input type="checkbox" name="{$item.field_name}[]" value="{$value}" {in_array match=$value array=$item.value returnvalue="checked"} id="{$item.field_name}_{$value}"><label for="{$item.field_name}_{$value}">{$option}</label>
						</div>
					{/foreach}
					<div class="clr"></div>
					<a href="#" class="select-link">{l i='select_all' gid='start'}</a> &nbsp;|&nbsp;<a href="#" class="unselect-link">{l i='unselect_all' gid='start'}</a> 
				{/if}
			{elseif $item.field_type eq 'text'}
				<input type="text" name="{$item.field_name}" value="{$item.value|escape}" maxlength="{$item.settings_data_array.max_char}" {if $item.settings_data_array.max_char < 11}class="short"{elseif $item.settings_data_array.max_char > 1100}class="long"{/if}>
			{elseif $item.field_type eq 'textarea'}
				<textarea name="{$item.field_name}" class="long">{$item.value|escape}</textarea>
			{elseif $item.field_type eq 'checkbox'}
				<input type="checkbox" name="{$item.field_name}" value="1"{if $item.value eq '1'} checked{/if}>
			{elseif $item.field_type eq 'range'}
				<input type="text" name="{$item.field_name}" value="{$item.value}" />
				({l i='min' gid='start'} - {l i='max' gid='start'}: {$item.settings_data_array.min_val} - {$item.settings_data_array.max_val})
			{/if}
		</div>
	</div>
{/foreach}
<script type="text/javascript">{literal}
	function setchbx(fid, status){
		if(status){
			$('#'+fid).find('input[type=checkbox]').prop('checked', true);
		}else{
			$('#'+fid).find('input[type=checkbox]').prop('checked', false);
		}
	}
	$(function(){
		$('.select-link').bind('click', function(){
			setchbx($(this).parent().attr('id'), 1); return false;
		});
		$('.unselect-link').bind('click', function(){
			setchbx($(this).parent().attr('id'), 0); return false;
		});
	});
{/literal}</script>