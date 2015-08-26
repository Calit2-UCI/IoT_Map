{include file="header.tpl"}
{include file="numerics_menu.tpl" module="start"}
<div class="actions">&nbsp;</div>

{if $section eq 'overview'}
	<div class="right-side">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			{foreach item=module_data key=module from=$settings_data.other}
				<tr>
					<th class="first" colspan=2>{$module_data.name}</th>
				</tr>
				{foreach item=item key=key from=$module_data.vars}
					<tr{if $key is div by 2} class="zebra"{/if}>
						<td class="first">{$item.field_name}</td>
						<td>
							{if $item.field_type == 'checkbox'}
								{if $item.value}{l i='yes_str' gid='start'}{else}{l i='no_str' gid='start'}{/if}
							{elseif $item.field_type == 'select'}
								{$item.value_name}
							{else}
								{$item.value}
							{/if}
						</td>
					</tr>
				{/foreach}
			{/foreach}
		</table>
	</div>

	<div class="left-side">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
			{foreach item=module_data key=module from=$settings_data.numerics}
				<tr>
					<th class="first" colspan=2>{$module_data.name}</th>
				</tr>
				{foreach item=item key=key from=$module_data.vars}
					<tr{if $key is div by 2} class="zebra"{/if}>
						<td class="first">{$item.field_name}</td>
						<td class="w30">
							{if $item.field_type == 'checkbox'}
								{if $item.value}{l i='yes_str' gid='start'}{else}{l i='no_str' gid='start'}{/if}
							{elseif $item.field_type == 'select'}
								{$item.value_name}
							{else}
								{$item.value}
							{/if}
						</td>
					</tr>
				{/foreach}
			{/foreach}
		</table>
	</div>
	<div class="clr"><a class="cancel" href="{$site_url}admin/start/menu/system-items">{l i='btn_cancel' gid='start'}</a></div>
{elseif $section eq 'numerics'}
	<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n250">
			{foreach item=module_data key=module from=$settings_data}
				<div class="row header">{$module_data.name}</div>
				{foreach item=item key=key from=$module_data.vars}
					<div class="row{if $key is div by 2} zebra{/if}">
						<div class="h">{$item.field_name}:</div>
						<div class="v"><input type="text" name="settings[{$module}][{$item.field}]" value="{$item.value|escape}" class="short"></div>
					</div>
					<br>
				{/foreach}
			{/foreach}
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/start/menu/system-items">{l i='btn_cancel' gid='start'}</a>
	</form>
{elseif $section eq 'date_formats'}
	<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="w150 first">{$settings_data.name}</th>
			<th class="w200">{l i='example' gid='start'}</th>
			<th>{l i='date_formats_used_in' gid='start'}</th>
			<th class="w50 center">&nbsp;</th>
		</tr>
		{foreach item=var key=key from=$settings_data.vars}
			{assign var="field" value=$var.field}
			{if $date_formats_pages[$field]}
				<tr>
					<td>{$var.field_name}</td>
					<td>{$var.value}</td>
					<td>
						<span id="{$field}" class="tooltip">
							{l i='date_formats_'$field'_description' gid='start'}
						</span>
						<span id="tt_{$field}" class="hide">
							<div class="tooltip-info">
								{foreach item=page from=$date_formats_pages[$field]}
									{$site_url}{$page}<br/>
								{/foreach}
							</div>
						</span>
					</td>
					<td class="center">
						<a href="{$site_url}admin/start/date_formats/{$field}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_date_format' gid='start'}" title="{l i='link_edit_date_format' gid='start'}"></a>
					</td>
				</tr>
			{/if}
		{foreachelse}
			<tr><td colspan="8" class="center">{l i='no_date_formats' gid='start'}</td></tr>
		{/foreach}
	</table>
	{js file='easyTooltip.min.js'}
	{literal}
	<script type="text/javascript">
		$(function(){
			$(".tooltip").each(function(){
				$(this).easyTooltip({
					useElement: 'tt_'+$(this).attr('id'),
					yOffset: $('#tt_'+$(this).attr('id')).height()/2,
					clickRemove: true
				});
			});
		});
	</script>
	{/literal}
{else}
	<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
		<div class="edit-form n250">
			<div class="row header">{$settings_data.name}</div>
			{foreach item=item key=key from=$settings_data.vars}
				<div class="row{if $key is div by 2} zebra{/if}">
				{if $section eq 'countries'}
					<div class="h">{$item.field_name}:</div>
					<div class="v"><input type="text" name="settings[{$item.field}]" value="{$item.value|escape}" class="middle"><br><i>{l i=$item.field+'_settings_descr' gid='countries'}</i></div>
				{else}
					<div class="h">{$item.field_name}:</div>
					<div class="v">
						{if $item.field_type == 'text' || !$item.field_type}
							<input type="text" name="settings[{$item.field}]" value="{$item.value|escape}" class="short">
						{elseif $item.field_type == 'checkbox'}
							<input type="checkbox" name="settings[{$item.field}]" value="1" {if $item.value}checked{/if} class="short">
						{elseif $item.field_type == 'select'}
							<select name="settings[{$item.field}]" class="short">
								{foreach from=$item.field_values key=key item=field_value}
									<option value="{$key}"{if $item.value == $key} selected{/if}>{l i=$section+'_'+$item.field+'_'+$field_value+'_value' gid='start'}</option>
								{/foreach}
							</select>
						{/if}
					</div>
				{/if}
				</div>
			{/foreach}
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/start/menu/system-items">{l i='btn_cancel' gid='start'}</a>
	</form>
{/if}
<div class="clr"></div>

{include file="footer.tpl"}