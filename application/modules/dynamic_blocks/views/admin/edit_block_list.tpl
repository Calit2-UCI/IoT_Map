{include file="header.tpl" load_type='ui'}
{strip}
<script src="{$editor_params.script_src}"></script>
<script src="{$editor_params.jquery_adapter_script_src}"></script>
<div class="actions">
	<ul>
		<li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false;">{l i='link_save_block_sorting' gid='dynamic_blocks'}</a></div></li>
	</ul>
	&nbsp;
</div>

<div class="menu-level3">
	<ul>
		<li class="active"><a href="{$site_url}admin/dynamic_blocks/area_blocks/{$area.id}">{l i='filter_area_blocks' gid='dynamic_blocks'}</a></li>
		<li><a href="{$site_url}admin/dynamic_blocks/area_layout/{$area.id}">{l i='filter_area_layout' gid='dynamic_blocks'}</a></li>
		{if $presets_count>1}<li><a href="{$site_url}admin/dynamic_blocks/area_preset/{$area.id}">{l i='filter_area_preset' gid='dynamic_blocks'}</a></li>{/if}
	</ul>
	&nbsp;
</div>

<div class="filter-form">
	<form action="" method="post">
		<h3>{l i='add_area_block_header' gid='dynamic_blocks'}:</h3>
		<select name="id_block">
			<option value="0">...</option>
			{foreach item=item key=block_id from=$blocks}
				<option value="{$block_id}">{l i=$item.name_i gid=$item.lang_gid}</option>
			{/foreach}
		</select>
		<input type="submit" name="add_block" value="{l i='link_add_block' gid='dynamic_blocks' type='button'}">
	</form>
</div>

<div id="area_blocks">
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach item=item from=$area_blocks}
			<li id="item_{$item.id}">
				<div class="icons">
					<a href='#' onclick="if (confirm('{l i='note_delete_area_block' gid='dynamic_blocks' type='js'}')) mlSorter.deleteItem({$item.id});return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" alt="{l i='btn_delete' gid='start'}" title="{l i='btn_delete' gid='start'}"></a>
				</div>

				<form id="form_item_{$item.id}">
					<h3>{l i=$item.block_data.name_i gid=$item.block_data.lang_gid}</h3>
					<div class="edit-form n150">
						{foreach item=param from=$item.block_data.params_data}
							{assign var="param_gid" value=$param.gid}
							<div class="row" id="row_{$item.id}_{$param.gid}">
								<div class="h">{l i=$param.i gid=$item.block_data.lang_gid}: </div>
								<div class="v">
									{switch from=$param.type}
									{case value='textarea'}
										<textarea name="params[{$param_gid}]" rows="5" cols="80">{$item.params[$param_gid]|escape}</textarea>
									{case value='text'}
										{foreach item=lang_item key=lang_id from=$langs}
											{*assign var=value_id value=$param_gid+'_'+$lang_id*}
											{assign var=value_code value=$param_gid+'_'+$lang_item.id}
											<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" 
												   name="params[{$param_gid}_{$lang_id}]" 
												   value="{$item.params[$value_code]|strval|escape}" 
												   lang-editor="value" lang-editor-type="params-{$param_gid}" 
												   lang-editor-lid="{$lang_id}" class="middle">
										{/foreach}
										<a href="#" lang-editor="button" lang-editor-type="params-{$param_gid}"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>

									{case value='int'}
										<input type="text" class="middle" value="{$item.params[$param_gid]|intval|escape}" name="params[{$param_gid}]">
									{case value='checkbox'}
										<input type="hidden" value="0" name="params[{$param_gid}]" />
										<label><input type="checkbox" value="1" name="params[{$param_gid}]"{if $item.params[$param_gid]} checked{/if} /></label>
									{case value='wysiwyg'}
										<div class="wysiwyg-wrapper">
											<dl>
												{foreach from=$langs item=lng}
													<dt	onclick="$(this).parent().find('dt').removeClass('active'); 
																$(this).addClass('active'); 
																$(this).parent().find('dd').hide().filter('[data-lang={$lng.id}]').show();"
														{if $lng.id == $current_lang_id} class="active"{/if}>
														{$lng.name}
													</dt>
												{/foreach}
												{foreach from=$langs item=lng}
													{assign var=lang_code value=$param_gid+'_'+$lng.id}
													<dd data-lang="{$lng.id}"{if $lng.id != $current_lang_id} class="hide"{/if}>
														<textarea name="params[{$param_gid}_{$lng.id}]" data-id-block="{$item.id}">{$item.params[$lang_code]}</textarea>
													</dd>
												{/foreach}
											</dl>
											
										</div>
									{case}
										<input type="text" value="{$item.params[$param_gid]|escape}" name="params[{$param_gid}]">
									{/switch}
								</div>
							</div>
						{/foreach}
						<div class="row">
							<div class="h">{l i='field_view' gid='dynamic_blocks'}: </div>
							<div class="v">
								<select name="view_str" class="middle">
								{foreach item=view from=$item.block_data.views_data}
									{assign var="view_gid" value=$view.gid}
									<option value="{$view_gid}" {if $view_gid eq $item.view_str}selected{/if}>{l i=$view.i gid=$item.block_data.lang_gid}</option>
								{/foreach}
								</select>
							</div>
						</div>
						<div class="row">
							<div class="h">{l i='field_cache_time' gid='dynamic_blocks'}: </div>
							<div class="v"><input type="text" value="{$item.cache_time}" name="cache_time" class="short"> <i>{l i='field_cache_time_text' gid='dynamic_blocks'}</i></div>
						</div>
						<div class="row">
							<div class="h">&nbsp;</div>
							<div class="v"><input type="button" name="save_data" value="{l i='btn_save' gid='start' type='button'}" onclick="javascript: saveBlock('{$item.id}');"></div>
						</div>
						<div class="clr"></div>
					</div>
				</form>
			</li>
		{/foreach}
	</ul>
</div>
{/strip}
{block name='lang_inline_editor' module='start' multiple='1'}
<script >
	var save_url = '{$site_root}admin/dynamic_blocks/ajax_save_area_block/';
{literal}
	cke_params = {{/literal}
		language: '{$editor_params.language}',
		filebrowserImageUploadUrl: '{$editor_params.upload_url}',
		filebrowserFlashUploadUrl: '{$editor_params.upload_url}',
		resize_enabled: false,
		toolbar: {$editor_params.toolbars.Middle}
	{literal}}

	$('#area_blocks').find('.wysiwyg-wrapper textarea').each(function(){
		var params = $.extend(true, {}, cke_params);
		params.filebrowserImageUploadUrl += $(this).data('id-block');
		params.filebrowserFlashUploadUrl += $(this).data('id-block');
		$(this).ckeditor(params);
	});
	
	var mlSorter;
	$(function(){
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}', 
			urlSaveSort: 'admin/dynamic_blocks/save_area_block_sorter/{/literal}{$area.id}{literal}',
			urlDeleteItem: 'admin/dynamic_blocks/ajax_delete_area_block/',
			onStart: function(event, ui){
				ui.item.find('.wysiwyg-wrapper textarea').each(function(){
					$(this).ckeditorGet().destroy();
                });
			},
			onStop: function(event, ui){
				ui.item.find('.wysiwyg-wrapper textarea').each(function(){
					var params = $.extend(true, {}, cke_params);
					params.filebrowserImageUploadUrl += $(this).data('id-block');
					params.filebrowserFlashUploadUrl += $(this).data('id-block');
					$(this).ckeditor(params);
				});
			}
		});
	});

	function saveBlock(id){
		$.ajax({
			url: save_url + id, 
			type: 'POST',
			data: $('#form_item_'+id).serialize(), 
			cache: false,
			success: function(data){
				error_object.show_error_block('{/literal}{l i="success_update_area_block" gid="dynamic_blocks" type="js"}{literal}', 'success');
			}
		});
	}
{/literal}</script>
{include file="footer.tpl"}
