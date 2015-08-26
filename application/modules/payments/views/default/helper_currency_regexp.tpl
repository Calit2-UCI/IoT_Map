{literal}
function(value){
	return '{/literal}{$template}{literal}'.replace({/literal}{$pattern_value|replace:'^]':'^\\]'}{literal}g, {/literal}{$value}{literal});
}
{/literal}
