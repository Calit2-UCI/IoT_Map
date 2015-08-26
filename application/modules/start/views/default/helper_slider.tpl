{strip}
<div id="{$slider_data.id}_wrapper" class="table-div wp100">
	{if !$slider_data.active_always}
		<div class="w20">
			<input type="checkbox" value="1"{if $slider_data.use} checked{/if} />
		</div>
	{/if}
	<div>
		<div id="{$slider_data.id}"></div>
		{if $slider_data.single}
			<input type="hidden" name="{$slider_data.field_name}" id="{$slider_data.field_name}" class="short" value="{if $slider_data.use || $slider_data.active_always}{$slider_data.value}{/if}">
			<span id="{$slider_data.id}_value" class="fleft">{$slider_data.value}</span>
		{else}
			<input type="hidden" name="{$slider_data.field_name_min}" id="{$slider_data.field_name_min}" class="short" value="{if $slider_data.use || $slider_data.active_always}{$slider_data.value_min}{/if}">
			<input type="hidden" name="{$slider_data.field_name_max}" id="{$slider_data.field_name_max}" class="short" value="{if $slider_data.use || $slider_data.active_always}{$slider_data.value_max}{/if}">
			<span id="{$slider_data.id}_min_value" class="fleft">{$slider_data.value_min}</span>
			<span id="{$slider_data.id}_max_value" class="fright">{$slider_data.value_max}</span>
		{/if}
	</div>
</div>
{/strip}

<script>{literal}
	$(function(){
		var slider_data = {/literal}{json_encode data=$slider_data}{literal};
		var use_checkbox = $('#'+slider_data.id+'_wrapper').find('input[type="checkbox"]');
		$('#'+slider_data.id).slider({
			range: (slider_data.single ? 'min' : true),
			min: slider_data.min,
			max: slider_data.max,
			values: (slider_data.single ? null : [slider_data.value_min, slider_data.value_max]),
			value: (slider_data.single ? slider_data.value : null),
			disabled: !(slider_data.use || slider_data.active_always),
			slide: function(e, ui){
				if(slider_data.single){
					if(use_checkbox.is(':checked') || slider_data.active_always){
						$('#'+slider_data.field_name).val(ui.value);
					}
					$('#'+slider_data.id+'_value').html(ui.value);
				}else{
					if(use_checkbox.is(':checked') || slider_data.active_always){
						$('#'+slider_data.field_name_min).val(ui.values[0]);
						$('#'+slider_data.field_name_max).val(ui.values[1]);
					}
					$('#'+slider_data.id+'_min_value').html(ui.values[0]);
					$('#'+slider_data.id+'_max_value').html(ui.values[1]);
				}
			},
			create: function(e, ui){
				if($('#'+slider_data.id).slider( "option", "disabled" )){
					$('#'+slider_data.id).slider('widget').find('.ui-slider-handle').unbind('click').bind('click', function(){return false;});
				}
			}
		});
		
		use_checkbox.unbind('click').bind('click', function(){
			if($(this).is(':checked')){
				$('#'+slider_data.id).slider('enable');
				if(slider_data.single){
					$('#'+slider_data.field_name).val($('#'+slider_data.id).slider('value'));
				}else{
					var slider_values = $('#'+slider_data.id).slider('values');
					$('#'+slider_data.field_name_min).val(slider_values[0]);
					$('#'+slider_data.field_name_max).val(slider_values[1]);
				}
			}else{
				$('#'+slider_data.id).slider('disable');
				if(slider_data.single){
					$('#'+slider_data.field_name).val('');
				}else{
					$('#'+slider_data.field_name_min).val('');
					$('#'+slider_data.field_name_max).val('');
				}
			}
		});
	});
</script>{/literal}
