{include file="header.tpl"}
<form id="edit_block_form" method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_dynblock_change' gid='dynamic_blocks'}{else}{l i='admin_header_dynblock_add' gid='dynamic_blocks'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_gid' gid='dynamic_blocks'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name' gid='dynamic_blocks'}: </div>
			<div class="v">
				{assign var="lang_gid" value=$data.lang_gid}
				{assign var="name_i" value=$data.name_i}
				{assign var="validate_lang_var" value=$validate_lang[$lang_gid][$name_i]}
				<input type="text" value="{if $validate_lang_var}{$validate_lang_var[$cur_lang]}{else}{$data.name}{/if}" name="langs[name][{$cur_lang}]">
				{if $languages_count > 1}
				&nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='dynamic_blocks'}</a><br>
				<div id="name_langs" class="hide p-top2">
					{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
					<input type="text" value="{if $validate_lang_var}{$validate_lang_var[$lang_id]}{else}{$data.name}{/if}" name="langs[name][{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
					{/if}{/foreach}
				</div>
				{/if}
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_module' gid='dynamic_blocks'}: </div>
			<div class="v"><input type="text" value="{$data.module}" name="module"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_model' gid='dynamic_blocks'}: </div>
			<div class="v"><input type="text" value="{$data.model}" name="model"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_method' gid='dynamic_blocks'}: </div>
			<div class="v"><input type="text" value="{$data.method}" name="method"></div>
		</div>

		<div class="row">
			<div class="h">{l i='field_params' gid='dynamic_blocks'}: </div>
			<div class="v">
				<div class="opt-header">{l i='field_param_name' gid='dynamic_blocks'}:</div>
				<div class="opt-header">{l i='field_param_gid' gid='dynamic_blocks'}:</div>
				<div class="opt-header">{l i='field_param_type' gid='dynamic_blocks'}:</div>
				<div class="opt-header">{l i='field_param_default' gid='dynamic_blocks'}:</div>
				<div class="clr"></div>
				<ol id="params">
				{foreach item=params key=pkey from=$data.params_data}
					{assign var="name_i" value=$params.i}
					{assign var="validate_lang_var" value=$validate_lang[$lang_gid][$name_i]}
					<li id="param_block_{$pkey}">
						<input type="text" name="langs[params][{$pkey}][{$cur_lang}]" value="{if $validate_lang_var}{$validate_lang_var[$cur_lang]}{else}{$params.name}{/if}">
						<input type="text" name="params[{$pkey}][gid]" value="{$params.gid}">
						<select name="params[{$pkey}][type]">
							{foreach item=item key=key from=$block_types.option}
							<option value="{$key}" {if $params.type eq $key}selected{/if}>{$item}</option>
							{/foreach}
						</select>
						<input type="text" name="params[{$pkey}][default]" value="{$params.default}">
						{if $languages_count > 1}
						&nbsp;&nbsp;<a href="#" onclick="showLangs('param_langs{$pkey}'); return false;">{l i='others_languages' gid='dynamic_blocks'}</a><br>
						<div id="param_langs{$pkey}" class="hide p-top2">
							{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
							<input type="text" value="{if $validate_lang_var}{$validate_lang_var[$lang_id]}{else}{l i=$params.i gid=$data.lang_gid lang=$lang_id}{/if}" name="langs[params][{$pkey}][{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
							{/if}{/foreach}
						</div>
						{/if}
					</li>
				{/foreach}
				</ol>
				<a href="#" onclick="javascript: add_new_param(); return false;">{l i='link_add_new_param' gid='dynamic_blocks'}</a>
			</div>
		</div>

		<div class="row zebra">
			<div class="h">{l i='field_views' gid='dynamic_blocks'}: </div>
			<div class="v">
				<div class="opt-header">{l i='field_param_name' gid='dynamic_blocks'}:</div>
				<div class="opt-header">{l i='field_param_gid' gid='dynamic_blocks'}:</div>
				<div class="clr"></div>
				<ol id="views">
				{foreach item=views key=vkey from=$data.views_data}
					{assign var="name_i" value=$views.i}
					{assign var="validate_lang_var" value=$validate_lang[$lang_gid][$name_i]}
					<li id="view_block_{$vkey}">
						<input type="text" name="langs[views][{$vkey}][{$cur_lang}]" value="{if $validate_lang_var}{$validate_lang_var[$cur_lang]}{else}{$views.name}{/if}">
						<input type="text" name="views[{$vkey}][gid]" value="{$views.gid}">
						{if $languages_count > 1}
						&nbsp;&nbsp;<a href="#" onclick="showLangs('view_langs{$vkey}'); return false;">{l i='others_languages' gid='dynamic_blocks'}</a><br>
						<div id="view_langs{$vkey}" class="hide p-top2">
							{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
							<input type="text" value="{if $validate_lang_var}{$validate_lang_var[$lang_id]}{else}{l i=$views.i gid=$data.lang_gid lang=$lang_id}{/if}" name="langs[{$lang_id}][views][{$vkey}]">&nbsp;|&nbsp;{$item.name}<br>
							{/if}{/foreach}
						</div>
						{/if}
					</li>
				{/foreach}
				</ol>
				<a href="#" onclick="javascript: add_new_view(); return false;">{l i='link_add_new_view' gid='dynamic_blocks'}</a>
			</div>
		</div>

	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/dynamic_blocks/blocks">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script >{literal}
function showLangs(divId){
	$('#'+divId).slideToggle();
}

function add_new_param(){
	var new_index = 0;
	$('#params > li').each(function(){
		var t = parseInt($(this).attr('id').substring(12));
		if(new_index < t) new_index=t;
	});
	new_index++;
	var template = 
					'<li id="param_block_'+new_index+'">'+
					'	<input type="text" name="langs[params]['+new_index+'][{/literal}{$cur_lang}{literal}]" value="">'+
					'	<input type="text" name="params['+new_index+'][gid]" value="">'+
					'	<input type="text" name="params['+new_index+'][type]" value="">'+
					'	<input type="text" name="params['+new_index+'][default]" value="">'+
						{/literal}{if $languages_count > 1}{literal}
					'	&nbsp;&nbsp;<a href="#" onclick="showLangs(\'param_langs'+new_index+'\'); return false;">{/literal}{l i='others_languages' gid='dynamic_blocks' type='js'}{literal}</a><br>'+
					'	<div id="param_langs'+new_index+'" class="hide p-top2">'+
							{/literal}{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}{literal}
					'		<input type="text" value="" name="langs[params]['+new_index+'][{/literal}{$lang_id}{literal}]">&nbsp;|&nbsp;{/literal}{$item.name}{literal}<br>'+
							{/literal}{/if}{/foreach}{literal}
					'	</div>'+
						{/literal}{/if}{literal}
					'</li>';
	$('#params').append(template);
}


function add_new_view(){
	var new_index = 0;
	$('#views > li').each(function(){
		var t = parseInt($(this).attr('id').substring(11));
		if(new_index < t) new_index=t;
	});
	new_index++;
	var template = 
					'<li id="view_block_'+new_index+'">'+
					'	<input type="text" name="langs[views]['+new_index+'][{/literal}{$cur_lang}{literal}]" value="">'+
					'	<input type="text" name="views['+new_index+'][gid]" value="">'+
						{/literal}{if $languages_count > 1}{literal}
					'	&nbsp;&nbsp;<a href="#" onclick="showLangs(\'view_langs'+new_index+'\'); return false;">{/literal}{l i='others_languages' gid='dynamic_blocks' type='js'}{literal}</a><br>'+
					'	<div id="view_langs'+new_index+'" class="hide p-top2">'+
							{/literal}{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}{literal}
					'		<input type="text" value="" name="langs[views]['+new_index+'][{/literal}{$lang_id}{literal}]">&nbsp;|&nbsp;{/literal}{$item.name}{literal}<br>'+
							{/literal}{/if}{/foreach}{literal}
					'	</div>'+
						{/literal}{/if}{literal}
					'</li>';
	$('#views').append(template);
}

{/literal}</script>
{include file="footer.tpl"}