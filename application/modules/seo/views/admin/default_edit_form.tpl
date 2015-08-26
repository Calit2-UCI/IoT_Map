{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_global_seo_settings_editing' gid='seo'} : {if $controller eq 'admin'}{l i='default_seo_admin_field' gid='seo'}{else}{l i='default_seo_user_field' gid='seo'}{/if}</div>
		<div class="row zebra">
			<div class="h"><b>{l i='field_title_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_title' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" name="title[{$key}]" value="{$user_settings[$section_gid].title|escape}" class="long"></div>
		</div>
		{/foreach}
		<br>

		<div class="row zebra">
			<div class="h"><b>{l i='field_keyword_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_keyword' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><textarea name="keyword[{$key}]" rows="5" cols="80" class="long pb2">{$user_settings[$section_gid].keyword|escape}</textarea></div>
		</div>
		{/foreach}
		<br>
	
		<div class="row zebra">
			<div class="h"><b>{l i='field_description_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_description' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><textarea name="description[{$key}]" rows="5" cols="80" class="long pb2">{$user_settings[$section_gid].description|escape}</textarea></div>
		</div>
		{/foreach}
		<br>	
		
		<div class="row zebra">
			<div class="h"><b>{l i='header_section_og' gid='seo'}</b></div>
			<div class="v">{l i='text_help_og' gid='seo'}</div>
		</div>
		<br>
		
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_title_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_og_title' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{l i='field_og_title' gid='seo'}({$item.name}): </div>
			<div class="v"><input type="text" name="og_title[{$key}]" class="long" value="{$user_settings[$section_gid].og_title|escape}"></div>
		</div>
		{/foreach}
		<br>
			
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_type_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_og_type' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" name="og_type[{$key}]" class="long" value="{$user_settings[$section_gid].og_type|escape}"></div>
		</div>
		{/foreach}
		<br>
			
		<div class="row zebra">
			<div class="h"><b>{l i='field_og_description_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_og_description' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='og_'+$key}
		<div class="row">
			<div class="h">{$item.name}:</div>
			<div class="v"><textarea name="og_description[{$key}]" rows="5" cols="80" class="long pb2">{$user_settings[$section_gid].og_description|escape}</textarea></div>
		</div>
		{/foreach}
		<br>
		
		{if $controller eq 'user'}
		<div class="row zebra">
			<div class="h"><b>{l i='field_header_default' gid='seo'}</b></div>
			<div class="v">{l i='text_help_header' gid='seo'}</div>
		</div>
		{foreach item=item key=key from=$languages}
		{assign var='section_gid' value='meta_'+$key}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v"><input type="text" name="header[{$key}]" class="long" value="{$user_settings[$section_gid].header|escape}"></div>
		</div>
		{/foreach}
		<br>
		{/if}
		
		<div class="row zebra">
			<div class="h">{l i='field_lang_in_url' gid='seo'}: </div>
			<div class="v">
				<input type="checkbox" value="1" name="lang_in_url" {if $user_settings.lang_in_url}checked{/if} id="lang_in_url">
			</div>
		</div>
		{if $controller eq 'user'}
		<div class="row">
			<div class="h">{l i='field_lang_canonical' gid='seo'}: </div>
			<div class="v">
				<input type="hidden" name="lang_canonical" value="0">
				<input type="checkbox" name="lang_canonical" value="1" {if $user_settings.lang_canonical}checked{/if}>
			</div>
		</div>
		{/if}
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/seo/index">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>

{include file="footer.tpl"}
