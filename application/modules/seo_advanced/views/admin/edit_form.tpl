{include file="header.tpl" load_type='ui|editable'}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_seo_settings_editing' gid='seo_advanced'}</div>
		<div class="row">
			<div class="h">{l i='field_default_link' gid='seo_advanced'}: </div>
			<div class="v">
				{strip}
				{$site_url}{$module_gid}/{$method}
				{foreach item=item key=key from=$default_settings.url_vars}/[{$key}]{/foreach}
				{foreach item=item key=key from=$default_settings.url_postfix}/[{$key}]{/foreach}
				{/strip}
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='url_manager' gid='seo_advanced'}: </div>
			<div class="v">
				<input type="hidden" name="url_template_data" id="url_data" value="">
				<ul class="url-creator" id="url_block"></ul>
				<div class="clr"></div>
				<div id="url_text_edit" class="url-form hide">
					<b>{l i='action_edit_url_block' gid='seo_advanced'}:</b>
					<div class="row zebra">
						<div class="h">{l i='field_url_block_type' gid='seo_advanced'}:</div>
						<div class="v">{l i='field_url_block_type_text' gid='seo_advanced'}</div>
					</div>
					<div class="row zebra">
						<div class="h">{l i='field_url_block_value' gid='seo_advanced'}</div>
						<div class="v"><input type="text" value="" id="text_block_value_edit"></div>
					</div>
					<div class="row zebra">
						<div class="h">&nbsp;</div>
						<div class="v">
							<input type="button" id="text_block_save" name="add-block" value="{l i='btn_save' gid='start' type='button'}">
							<input type="button" id="text_block_delete" name="delete-block" value="{l i='btn_delete' gid='start' type='button'}">
						</div>
					</div>
				</div>
				<br>
				<div id="url_tpl_edit" class="url-form hide">
					<b>{l i='action_edit_url_block' gid='seo_advanced'}:</b>
					<div class="row zebra">
						<div class="h">{l i='field_url_block_type' gid='seo_advanced'}:</div>
						<div class="v">{l i='field_url_block_type_tpl' gid='seo_advanced'}</div>
					</div>
					<div class="row zebra">
						<div class="h">{l i='field_url_block_replacement' gid='seo_advanced'}:</div>
						<div class="v" id="tpl_block_var_name">&nbsp;</div>
					</div>
					<div class="row zebra">
						<div class="h">{l i='field_url_block_default' gid='seo_advanced'}:</div>
						<div class="v"><input type="text" value="" id="tpl_block_var_default"></div>
					</div>
					<div class="row zebra">
						<div class="h">&nbsp;</div>
						<div class="v">
							<input type="button" id="tpl_block_save" name="add-block" value="{l i='btn_save' gid='start' type='button'}">
							<input type="button" id="tpl_block_delete" name="delete-block" value="{l i='btn_delete' gid='start' type='button'}">
						</div>
					</div>
				</div>

				<div class="url-form">
					<div class="row">
						<div class="h">{l i='link_add_url_block_text' gid='seo_advanced'}:</div>
						<div class="v"><input type="text" id="text_block_value"> <input type="button" name="add-block" value="{l i='btn_add' gid='start' type='button'}"  onclick="javascript: urlCreator.save_block('', 'text', $('#text_block_value').val(), '', '', '');"></div>
					</div>
				</div>
				{*if $default_settings.url_vars}
				<div class="url-form">
					<div class="row">
						<div class="h">{l i='link_add_url_block_tpl' gid='seo_advanced'}:</div>
						<div class="v">
						{foreach item=item key=key from=$default_settings.url_vars}{if $defcounter > 0}<br><span class="or">{l i='link_or' gid='seo_advanced'}</span><br>{/if}
						{counter id='mandatory' print=false assign=defcounter}
						[<select id="var-{$key}">{foreach item=type key=varname from=$item}<option value="{$type}">{$varname}</option>{/foreach}</select>|<input type="text" class="short" id="vardef-{$key}">]
						<input type="button" name="add-block" value="{l i='btn_add' gid='start' type='button'}" onclick="javascript: urlCreator.save_block('', 'tpl', $('#vardef-{$key}').val(), '{$defcounter}', $('#var-{$key}').val(), $('#var-{$key} > option:selected').text());">
						{/foreach}
						</div>
					</div>
				</div>
				{/if*}
				{if $default_settings.optional}
				<div class="url-form">
					<div class="row">
						<div class="h">{l i='link_add_url_block_opt' gid='seo_advanced'}:</div>
						<div class="v">
						{foreach item=item key=key from=$default_settings.optional}{if $optcounter > 0}<br><span class="or">{l i='link_or' gid='seo_advanced'}</span><br>{/if}
						{counter id='optional' print=false assign=optcounter}
						[<select id="opt-{$key}">{foreach item=type key=varname from=$item}<option value="{$type}">{$varname}</option>{/foreach}</select>|<input type="text" class="short" id="optdef-{$key}">]
						<input type="button" name="add-block" value="{l i='btn_add' gid='start' type='button'}" onclick="javascript: addOptBlock('{$key}', '{$optcounter}');">
						{/foreach}
						</div>
					</div>
				</div>
				{/if}
			</div>
			<div class="clr"></div>
		</div>

		<div class="row">
			<div class="h">&nbsp;</div>
			<div class="v">{l i='url_manager_text' gid='seo_advanced'}</div>
		</div>

	</div>
	<br>

	<div class="edit-form n150">
		{if $default_settings.templates}
		<div class="row zebra">
			<div class="h">{l i='field_templates' gid='seo_advanced'}: </div>
			<div class="v">
				{foreach item=item from=$default_settings.templates}<b>[{$item}<span class="hide_text">|{l i='default_value' gid='seo_advanced'}</span>]</b> {/foreach}<br><br><i>{l i='field_templates_text' gid='seo_advanced'}</i>
			</div>
		</div>
		<br>
		{/if}
		<div class="row zebra">
			<div class="h"><b>{l i='field_title_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_title' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}:</div>
			<div class="v"><input type="text" value="{$user_settings[$section_gid].title|escape}" name="title[{$key}]" class="long"></div>
		</div>
		{/foreach}
		<br>

		<div class="row zebra">
			<div class="h"><b>{l i='field_keyword_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_keyword' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><textarea name="keyword[{$key}]" rows="5" cols="80">{$user_settings[$section_gid].keyword|escape}</textarea></div>
		</div>
		{/foreach}
		<br>
	
		<div class="row zebra">
			<div class="h"><b>{l i='field_description_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_description' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{l i='field_description' gid='seo_advanced'}({$item.name}): </div>
			<div class="v"><textarea name="description[{$key}]" rows="5" cols="80">{$user_settings[$section_gid].description|escape}</textarea></div>
		</div>
		{/foreach}
		<br>
	
		<div class="row zebra">
			<div class="h"><b>{l i='field_header_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_header' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" name="header[{$key}]" value="{$user_settings[$section_gid].header|escape}" class="long"></div>
		</div>
		{/foreach}
		<br>
		
		<div class="row zebra">
			<div class="h"><b>{l i='field_noindex_use' gid='seo_advanced'}</b>: </div>
			<div class="v"><input type="checkbox" value="1" name="noindex" {if $user_settings.noindex}checked{/if} id="default_noindex" class="checked-tags" checked-param="noindex"></div>
		</div>
		<br>	
		
		<div class="row zebra">
			<div class="h"><b>{l i='header_section_og' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_og' gid='seo_advanced'}</div>
		</div>
		<br>
		
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_title_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_og_title' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" name="og_title[{$key}]" value="{$user_settings[$section_gid].og_title|escape}" class="long"></div>
		</div>
		{/foreach}
		<br>
			
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_type_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_og_type' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{$item.name}:</div>
			<div class="v"><input type="text" name="og_type[{$key}]" value="{$user_settings[$section_gid].og_type|escape}" class="long"></div>
		</div>
		{/foreach}
		<br>
			
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_description_default' gid='seo_advanced'}</b></div>
			<div class="v">{l i='text_help_og_description' gid='seo_advanced'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{$item.name}:</div>
			<div class="v"><textarea name="og_description[{$key}]" rows="5" cols="80">{$user_settings[$section_gid].og_description|escape}</textarea></div>
		</div>
		{/foreach}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/seo_advanced/listing/{$module_gid}">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>
{literal}
	var urlCreator;
	function addOptBlock(key, optcounter){
		var value = $('#opt-'+key).val();
		if(value){
			urlCreator.save_block('', 'opt', $('#optdef-'+key).val(), optcounter, value, $('#opt-'+key+' > option:selected').text());
		}else{
			urlCreator.save_block('', 'text',  $('#optdef-'+key).val(), '', '', '');
		}
	}
	$(function(){
		urlCreator = new seoUrlCreator({
			siteUrl: '{/literal}{$site_root}{literal}', 
			data: {/literal}{json_encode data=$user_settings.url_template_data}{literal},
			options: {/literal}{json_encode data=$user_settings.url_template_options}{literal},
		});
	});
{/literal}</script>

{include file="footer.tpl"}
