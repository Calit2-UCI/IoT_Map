{include file="header.tpl"}
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_rtl_changer' gid='themes'}</div>
		<div class="row zebra">
			<div class="h">{l i='field_theme' gid='themes'}: </div>
			<div class="v">
				<select name="id_theme" id="id_theme">
				<option value="">...</option>
				{foreach item=item from=$themes}	
				<option value="{$item.id}" {if $item.id eq $id_theme}selected{/if}>{$item.theme_name}({$item.theme_type})</option>
				{/foreach}			
				</select>
			</div>
		</div>
		{if $id_theme}
		<div class="row">
			<div class="h">{l i='field_css' gid='themes'}: </div>
			<div class="v">
				<select name="id_css" id="id_css">
				<option value="">...</option>
				{foreach item=item key=key from=$theme_data.css}	
				<option value="{$key}" {if $key eq $css_gid}selected{/if}>{$key}</option>
				{/foreach}			
				</select>
			</div>
		</div>
		
		{if $css_gid}
		<div class="row zebra">
			<div class="h">{l i='field_css_content' gid='themes'}: </div>
			<div class="v">
				<textarea readonly style="width: 570px; height: 400px">{$css_data}</textarea>
			</div>
		</div>
		
		{/if}
		{/if}
	</div>

<div class="clr"></div>
<script >{literal}
$(function(){
	$('#id_theme').bind('change', function(){
		location.href='{/literal}{$site_url}admin/themes/rtl_parser/{literal}'+$(this).val();
	});
	$('#id_css').bind('change', function(){
		location.href='{/literal}{$site_url}admin/themes/rtl_parser/{literal}'+$('#id_theme').val()+'/'+$(this).val();
	});

});
{/literal}</script>
{include file="footer.tpl"}