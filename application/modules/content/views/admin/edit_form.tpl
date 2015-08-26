{include file="header.tpl"}

{if $data.id}
{depends module=seo_advanced}
<div class="menu-level3">
	<ul>
		<li class="{if $section_gid eq 'text'}active{/if}"><a href="{$site_url}admin/content/edit/{$current_lang}/{$parent_id}/{$data.id}/text">{l i='filter_section_text' gid='content'}</a></li>
		<li class="{if $section_gid eq 'seo'}active{/if}"><a href="{$site_url}admin/content/edit/{$current_lang}/{$parent_id}/{$data.id}/seo">{l i='filter_section_seo' gid='seo'}</a></li>
	</ul>
	&nbsp;
</div>
{/depends}
{/if}

{switch from=$section_gid}
	{case value='text'}
		<form method="post" action="" name="save_form" enctype="multipart/form-data">
			<div class="edit-form n150">
				<div class="row header">{if $data.id}{l i='admin_header_page_change' gid='content'}{else}{l i='admin_header_page_add' gid='content'}{/if}</div>
				{if $data.id}
				<div class="row">
					<div class="h">{l i='field_view_link' gid='content'}: </div>
					<div class="v">
						<a href="{seolink module='content' method='view' data=$data}">
							{seolink module='content' method='view' data=$data}
						</a>&nbsp;
					</div>
				</div>
				{/if}
				<div class="row">
					<div class="h">{l i='field_lang' gid='content'}: </div>
					<div class="v">
						<select name="lang_id">
							{foreach item=item from=$languages}
							<option value="{$item.id}" {if $item.id eq $data.lang_id || $item.id eq $current_lang && !$data.lang_id}selected{/if}>{$item.name}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_gid' gid='content'}: </div>
					<div class="v"><input type="text" value="{$data.gid}" name="gid" class="long"></div>
				</div>
				<div class="row">
					<div class="h">{l i='field_icon' gid='content'}: </div>
					<div class="v">
						<input type="file" name="page_icon">
						{if $data.img}
						<br><img src="{$data.media.img.thumbs.small}"  hspace="2" vspace="2"><br>
						<input type="checkbox" name="page_icon_delete" value="1" id="uichb">
						<label for="uichb">{l i='field_icon_delete' gid='content'}</label>
						{/if}
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_title' gid='content'}:&nbsp;* </div>
					<div class="v">
						{foreach item=lang_item key=lang_id from=$languages}
						{assign var='title' value='title_'+$lang_id}
						<input type="{if $lang_id eq $current_lang}text{else}hidden{/if}" name="title_{$lang_id}" value="{$data[$title]|escape}" lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}" class="long" />
						{/foreach}
						<a href="#" lang-editor="button" lang-editor-type="data-name"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_annotation' gid='content'}:&nbsp;* </div>
					<div class="v">
						{foreach item=lang_item key=lang_id from=$languages}
						{assign var='annotation' value='annotation_'+$lang_id}
						{if $lang_id eq $current_lang}
						<textarea name="annotation_{$lang_id}" rows="10" cols="80" class="long" 
							lang-editor="value" lang-editor-type="data-annotation" 
							lang-editor-lid="{$lang_id}">{$data[$annotation]|escape}</textarea>
						{else}
						<input type="hidden" name="annotation_{$lang_id}" value="{$data[$annotation]|escape}" 
							lang-editor="value" lang-editor-type="data-annotation" lang-editor-lid="{$lang_id}">
						{/if}
						{/foreach}
						<a href="#" lang-editor="button" lang-editor-type="data-annotation" lang-field-type="textarea">
							<img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16">
						</a>
					</div>
				</div>
			</div>
			<br>
			<div class="menu-level3">
				<ul id="info_lang">
					{foreach item=item key=lang_id from=$languages}
					<li{if $lang_id eq $current_lang} class="active"{/if} id="info_lang_{$lang_id}"><div class="l"><a href="{$site_url}admin/content/edit/{$lang_id}/{$data.id}" data-id="{$lang_id}">{$item.name}</a></div></li>
					{/foreach}
				</ul>
			</div>
			<div class="edit-form n150">
				<div class="row header">{l i='field_content' gid='content'}</div>
				<div class="row" id="info_content">
					{foreach item=lang_item key=lang_id from=$languages}
					<div id="info_content_{$lang_id}" class="info_content {if $lang_id ne $current_lang}hide{/if}">
						{$data.content_fck[$lang_id]}
					</div>
					{/foreach}
				</div>
			</div>
			{block name='lang_inline_editor' module='start'}
			<div class="clr"></div>
			<script>{literal}
				$(function(){
					$('#info_lang').find('li a').bind('click', function(){
						var lang_id = $(this).data('id');
						$('#info_lang').find('li').removeClass('active');
						$('#info_content').find('.info_content').hide();
						$('#info_lang_'+lang_id).addClass('active');
						$('#info_content_'+lang_id).show();
						return false;
					});
				});
			{/literal}</script>
			<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<a class="cancel" href="{$site_url}admin/content/index/{$current_lang}">{l i='btn_cancel' gid='start'}</a>
		</form>
		
	{case value='seo'}
		{depends module=seo_advanced}
		{foreach item=section key=key from=$seo_fields}
		<form method="post" action="{$data.action|escape}" name="seo_{$section.gid}_form">
		<div class="edit-form n150">
			<div class="row header">{$section.name}</div>		
			{if $section.tooltip}
			<div class="row">
				<div class="h">&nbsp;</div>
				<div class="v">{$section.tooltip}</div>
			</div>
			{/if}
			{foreach item=field from=$section.fields}
			<div class="row">
				<div class="h">{$field.name}: </div>
				<div class="v">
					{assign var='field_gid' value=$field.gid}
					{switch from=$field.type}
						{case value='checkbox'}
							<input type="hidden" name="{$section.gid}[{$field_gid}]" value="0">
							<input type="checkbox" name="{$section.gid}[{$field_gid}]" value="1" {if $seo_settings[$field_gid]}checked{/if}>
						{case value='text'}
							{foreach item=lang_item key=lang_id from=$languages}
							{assign var='section_gid' value=$section.gid+'_'+$lang_id}
							<input type="{if $lang_id eq $current_lang_id}text{else}hidden{/if}" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid]|escape}" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field_gid}"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='start' type='button'}" title="{l i='note_types_translate' gid='start' type='button'}"></a>
						{case value='textarea'}
							{foreach item=lang_item key=lang_id from=$languages}
								{assign var='section_gid' value=$section.gid+'_'+$lang_id}
								{if $lang_id eq $current_lang_id}
								<textarea name="{$section.gid}[{$field_gid}][{$lang_id}]" rows="5" cols="80" class="long" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">{$seo_settings[$section_gid][$field_gid]|escape}</textarea>
								{else}
								<input type="hidden" name="{$section.gid}[{$field_gid}][{$lang_id}]" value="{$seo_settings[$section_gid][$field_gid]|escape}" lang-editor="value" lang-editor-type="{$section.gid}_{$field_gid}" lang-editor-lid="{$lang_id}">
								{/if}
							{/foreach}
							<a href="#" lang-editor="button" lang-editor-type="{$section.gid}_{$field.gid}" lang-field-type="textarea"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16" alt="{l i='note_types_translate' gid='start' type='button'}" title="{l i='note_types_translate' gid='start' type='button'}"></a>					
					{/switch}<br>{$field.tooltip}					
				</div>
			</div>
			{/foreach}	
		</div>			
		<br>
		<div class="menu-level3">
			<ul id="info_lang">
				{foreach item=item key=lang_id from=$languages}
				<li{if $lang_id eq $current_lang} class="active"{/if} id="info_lang_{$lang_id}"><div class="l"><a href="{$site_url}admin/content/edit/{$lang_id}/{$data.id}" data-id="{$lang_id}">{$item.name}</a></div></li>
				{/foreach}
			</ul>
		</div>
		<div class="edit-form n150">
			<div class="row header">{l i='field_content' gid='content'}</div>
			<div class="row" id="info_content">
				{foreach item=lang_item key=lang_id from=$languages}
				<div id="info_content_{$lang_id}" class="info_content {if $lang_id ne $current_lang}hide{/if}">
					{$data.content_fck[$lang_id]}
				</div>
				{/foreach}
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_{$section.gid}" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/content/index/{$current_lang}">{l i='btn_cancel' gid='start'}</a>	
		<input type="hidden" name="btn_save" value="1">
		</form>
		<div class="clr"></div>
		{/foreach}
		{block name='lang_inline_editor' module='start'}
		{/depends}
{/switch}

<script>{literal}
	$(function(){
		$("div.row:visible:odd").addClass("zebra");
	});
{/literal}</script>

{include file="footer.tpl"}
