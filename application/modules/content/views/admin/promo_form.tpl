{include file="header.tpl" load_type='ui'}
<div class="menu-level2" id="menu-bookmark-level2">
	<ul>
		{foreach item=item key=lang_id from=$languages}
		<li{if $lang_id eq $current_lang} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$lang_id}">{$item.name}</a></div></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
{js module=menu file='menu-bookmark.js'}
<script>{literal}
	$(function(){
		new menuBookmark({'bmID': 'menu-bookmark-level2', bmElement: 'li', padding: 0,});
	});
{/literal}</script>


<div class="actions">&nbsp;</div>

<form method="post" action="" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_promo_block_main' gid='content'}</div>
		<div class="row">
			<div class="h">{l i='field_promo_type' gid='content'}: </div>
			<div class="v">
				<select name="content_type" id="content_type" class="middle">
				<option value="t"{if $promo_data.content_type eq 't'} selected{/if}>{l i='field_promo_type_text' gid='content'}</option>	
				<option value="f"{if $promo_data.content_type eq 'f'} selected{/if}>{l i='field_promo_type_flash' gid='content'}</option>	
				{*<option value="v"{if $promo_data.content_type eq 'v'} selected{/if}>{l i='field_promo_type_video' gid='content'}</option>	*}
				</select>
			</div>
		</div>
		<div id="content_block_t" class="content_block_box {*if $promo_data.content_type ne 't'}hide{/if*}">
			<div class="row zebra">
				<div class="h">{l i='field_block_width' gid='content'}: </div>
				<div class="v">
					<select name="t[block_width_unit]" class="units">
						<option value="auto"{if $promo_data.block_width_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_width_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
						<option value="%"{if $promo_data.block_width_unit eq '%'} selected{/if}>{l i='field_block_unit_percent' gid='content'}</option>	
					</select>
					<input type="text" name="t[block_width]" value="{$promo_data.block_width}" class="short unit_val" {if $promo_data.block_width_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_block_height' gid='content'}: </div>
				<div class="v">
					<select name="t[block_height_unit]" class="units">
						<option value="auto"{if $promo_data.block_height_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_height_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
					</select>
					<input type="text" name="t[block_height]" value="{$promo_data.block_height}" class="short unit_val" {if $promo_data.block_height_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
		</div>
		<div id="content_block_s" class="content_block_box {if $promo_data.content_type ne 's'}hide{/if}">
			<div class="row zebra">
				<div class="h">{l i='field_block_width' gid='content'}: </div>
				<div class="v">
					<select name="s[block_width_unit]" class="units">
						<option value="auto"{if $promo_data.block_width_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_width_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
						<option value="%"{if $promo_data.block_width_unit eq '%'} selected{/if}>{l i='field_block_unit_percent' gid='content'}</option>	
					</select>
					<input type="text" name="block_width" value="{$promo_data.block_width}" class="short unit_val" {if $promo_data.block_width_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_block_height' gid='content'}: </div>
				<div class="v">
					<select name="s[block_height_unit]" class="units">
						<option value="auto"{if $promo_data.block_height_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_height_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
					</select>
					<input type="text" name="s[block_height]" value="{$promo_data.block_height}" class="short unit_val" {if $promo_data.block_height_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
		</div>
		<div id="content_block_f" class="content_block_box {if $promo_data.content_type ne 'f'}hide{/if}">
			<div class="row zebra">
				<div class="h">{l i='field_block_width' gid='content'}: </div>
				<div class="v">
					<select name="f[block_width_unit]" class="units">
						<option value="auto"{if $promo_data.block_width_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_width_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
						<option value="%"{if $promo_data.block_width_unit eq '%'} selected{/if}>{l i='field_block_unit_percent' gid='content'}</option>	
					</select>
					<input type="text" name="f[block_width]" value="{$promo_data.block_width}" class="short unit_val" {if $promo_data.block_width_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_block_height' gid='content'}: </div>
				<div class="v">
					<select name="f[block_height_unit]" class="units">
						<option value="auto"{if $promo_data.block_height_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_height_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
					</select>
					<input type="text" name="f[block_height]" value="{$promo_data.block_height}" class="short unit_val" {if $promo_data.block_height_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
		</div>
		<div id="content_block_v" class="content_block_box {if $promo_data.content_type ne 'v'}hide{/if}">
			<div class="row zebra">
				<div class="h">{l i='field_block_width' gid='content'}: </div>
				<div class="v">
					<select name="v[block_width_unit]" class="units">
						<option value="auto"{if $promo_data.block_width_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_width_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
						<option value="%"{if $promo_data.block_width_unit eq '%'} selected{/if}>{l i='field_block_unit_percent' gid='content'}</option>	
					</select>
					<input type="text" name="v[block_width]" value="{$promo_data.block_width}" class="short unit_val" {if $promo_data.block_width_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_block_height' gid='content'}: </div>
				<div class="v">
					<select name="v[block_height_unit]" class="units">
						<option value="auto"{if $promo_data.block_height_unit eq 'auto'} selected{/if}>{l i='field_block_unit_auto' gid='content'}</option>	
						<option value="px"{if $promo_data.block_height_unit eq 'px'} selected{/if}>{l i='field_block_unit_px' gid='content'}</option>	
					</select>
					<input type="text" name="v[block_height]" value="{$promo_data.block_height}" class="short unit_val" {if $promo_data.block_height_unit eq 'auto'} disabled{/if}>
				</div>
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save_settings" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<div class="clr"></div>
</form>
<script>{literal}
	$('#content_type').bind('change', function(){
		$('.content_block_box').hide();
		$('#content_block_'+$(this).val()).show();
	});
{/literal}</script>

<div class="menu-level3">
	<ul>
		<li{if $content_type eq 't'} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$current_lang}/t">{l i='field_promo_type_text' gid='content'}</a></div></li>
		<li{if $content_type eq 'f'} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$current_lang}/f">{l i='field_promo_type_flash' gid='content'}</a></div></li>
		{depends module=video_uploads}<li{if $content_type eq 'v'} class="active"{/if}><div class="l"><a href="{$site_url}admin/content/promo/{$current_lang}/v">{l i='field_promo_type_video' gid='content'}</a></div></li>{/depends}
	</ul>
	&nbsp;
</div>

{switch from=$content_type}
	{case value='t'}
		<form method="post" action="{$site_url}admin/content/promo/{$current_lang}/{$content_type}" name="save_form"  enctype="multipart/form-data">
			<div class="edit-form n150">
				<div class="row header">&nbsp;</div>
				<div class="row">
					<div class="h">{l i='field_promo_text' gid='content'}: </div>
					<div class="v">
						{$promo_data.promo_text_fck}
					</div>
				</div>
				<div class="row zebra">
					<div class="h">{l i='field_block_img_align_hor' gid='content'}: </div>
					<div class="v">
						<select name="block_align_hor">
							<option value="center"{if $promo_data.block_align_hor eq 'center'} selected{/if}>{l i='field_block_img_align_center' gid='content'}</option>
							<option value="left"{if $promo_data.block_align_hor eq 'left'} selected{/if}>{l i='field_block_img_align_left' gid='content'}</option>
							<option value="right"{if $promo_data.block_align_hor eq 'right'} selected{/if}>{l i='field_block_img_align_right' gid='content'}</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_block_img_align_ver' gid='content'}: </div>
					<div class="v">
						<select name="block_align_ver">
							<option value="center"{if $promo_data.block_align_ver eq 'center'} selected{/if}>{l i='field_block_img_align_center' gid='content'}</option>
							<option value="top"{if $promo_data.block_align_ver eq 'top'} selected{/if}>{l i='field_block_img_align_top' gid='content'}</option>
							<option value="bottom"{if $promo_data.block_align_ver eq 'bottom'} selected{/if}>{l i='field_block_img_align_bottom' gid='content'}</option>
						</select>
					</div>
				</div>
				<div class="row zebra">
					<div class="h">{l i='field_block_img_repeating' gid='content'}: </div>
					<div class="v">
						<select name="block_image_repeat">
						<option value="repeat"{if $promo_data.block_image_repeat eq 'repeat'} selected{/if}>{l i='field_block_img_repeat' gid='content'}</option>
						<option value="no-repeat"{if $promo_data.block_image_repeat eq 'no-repeat'} selected{/if}>{l i='field_block_img_no_repeat' gid='content'}</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="h">{l i='field_promo_img' gid='content'}: </div>
					<div class="v">
						<input type="file" name="promo_image">
						{if $promo_data.promo_image}<br><img src="{$promo_data.media.promo_image.file_url}" width="500">{/if}
					</div>
				</div>
				{if $promo_data.promo_image}
				<div class="row zebra">
					<div class="h">{l i='field_promo_image_delete' gid='content'}: </div>
					<div class="v"><input type="checkbox" name="promo_image_delete" value="1"></div>
				</div>
				{/if}
			</div>
			<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="{l i='btn_save' gid='start' type='button'}"></div></div>
			<div class="clr"></div>
		</form>
	{case value='f'}
	<form method="post" action="{$site_url}admin/content/promo/{$current_lang}/{$content_type}" name="save_form"  enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">&nbsp;</div>
			<div class="row">
				<div class="h">{l i='field_promo_flash' gid='content'}: </div>
				<div class="v">
					<input type="file" name="promo_flash"><br>
					{if $promo_data.promo_flash}
					<i>{l i='field_promo_flash_uploaded' gid='content'}</i>
					<object width="100%" height="100%" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
						<param value="Always" name="allowScriptAccess">
						<param value="{$promo_data.media.promo_flash.file_url}" name="movie">
						<param value="false" name="menu">
						<param value="high" name="quality">
						<param value="opaque" name="wmode">
						<param value="" name="flashvars">
						<embed width="100%" height="100%" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" swliveconnect="FALSE" menu="false" wmode="opaque" allowscriptaccess="Always" quality="high" flashvars="" src="{$promo_data.media.promo_flash.file_url}"> 
					</object>
					{/if}
				</div>
			</div>
			{if $promo_data.promo_flash}
			<div class="row zebra">
				<div class="h">{l i='field_promo_flash_delete' gid='content'}: </div>
				<div class="v"><input type="checkbox" name="promo_flash_delete" value="1"></div>
			</div>
			{/if}
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<div class="clr"></div>
	</form>
	{case value='v'}
	<form method="post" action="{$site_url}admin/content/promo/{$current_lang}/{$content_type}" name="save_form"  enctype="multipart/form-data">
		<div class="edit-form n150">
			<div class="row header">&nbsp;</div>
			<div class="row">
				<div class="h">{l i='field_promo_video' gid='content'}:&nbsp;* </div>
				<div class="v">
					<p>
						{l i='max_video_header' gid='content'}: <b>1</b><br>
						{if $video_settings.max_size_str}{l i='max_video_size_header' gid='content'}: <b>{$video_settings.max_size_str}</b><br>{/if}
						{if $video_settings.file_formats}{l i='text_accepted_file_types' gid='content'}: <b>{', '|implode:$video_settings.file_formats}</b><br>{/if}
					</p>
					{if $promo_data.promo_video}
						{if $promo_data.promo_video_data.data.upload_type ne 'embed'}
							<br><br>{l i='field_video_status' gid='content'}:
							{if $promo_data.promo_video_data.status eq 'end' && $promo_data.promo_video_data.errors}<font color="red">{foreach item=item from=$promo_data.promo_video_data.errors}{$item}<br>{/foreach}</font>
							{elseif $promo_data.promo_video_data.status eq 'end'}<font color="green">{l i='field_video_status_end' gid='content'}</font><br>
							{elseif $promo_data.promo_video_data.status eq 'images'}<font color="yellow">{l i='field_video_status_images' gid='content'}</font><br>
							{elseif $promo_data.promo_video_data.status eq 'waiting'}<font color="yellow">{l i='field_video_status_waiting' gid='content'}</font><br>
							{elseif $promo_data.promo_video_data.status eq 'start'}<font color="yellow">{l i='field_video_status_start' gid='content'}</font><br>
							{/if}
						{/if}
						{if $promo_data.promo_video_content.embed}<br>{$promo_data.promo_video_content.embed}{/if}
						<br><input type="checkbox" name="promo_video_delete" value="1" id="uvchb"><label for="uvchb">{l i='field_promo_video_delete' gid='content'}</label>
					{else}
						<input type="file" name="promo_video"><br><br>
						{l i='text_video_embed' gid='content'}<br>
						<textarea name="promo_video_embed" rows="10" cols="80">{if $promo_data.promo_video && $promo_data.promo_video_data.data.upload_type eq 'embed'}{$promo_data.promo_video_content.embed|escape}{/if}</textarea>
					{/if}
				</div>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save_content" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<div class="clr"></div>
	</form>
{/switch}
<script type="text/javascript">{literal}
$(function(){
	$('.units').bind('change', function(){
		if($(this).val() == 'auto'){
			$(this).parent().find('input.unit_val').attr('disabled', 'disabled');
		}else{
			$(this).parent().find('input.unit_val').removeAttr('disabled');
		}	
	});
});
{/literal}</script>
<div class="clr"></div>
{include file="footer.tpl"}
