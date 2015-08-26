{include file="header.tpl" load_type='ui'}
{js file='date.js'}
{js module='start' file='date_formats.js'}
{include file="numerics_menu.tpl" module="start"}

<form id="date_format" method="post" action="" name="save_form" enctype="multipart/form-data">
	<input type="hidden" id="format_id" name="format_id" value="{$format.gid}" />
	<div class="edit-form n150">
		<div class="row header">{$settings_name}</div>		
		{foreach item=values key=field from=$format.available}
			{if $values|@count > 1}
			{counter print=false assign='counter'}
				<div class="row {if $counter is div by 2}zebra{/if}">
					<div class="h">{l i='date_format_'$field gid='start'}</div>
					<div class="v format">
						{foreach item=item from=$values}
							<div class="middle_short fl">
							<input type="radio" name="{$field}" id="{$item}" value="{$item}"{if $format.current[$field] eq $item} checked="checked"{/if}><label for="{$item}">{l i='date_format_'$item gid='start'}</label>								
							</div>

						{/foreach}
					</div>
				</div>
			{else}
				<div class="format">
					<input type="hidden" name="{$field}" id="{$values[0]}" value="{$values[0]}">
				</div>
			{/if}
		{/foreach}
		<div class="row">
			<div class="h">{l i='template' gid='start'}</div>
			<div class="v tpl">
				<input autocomplete="off" class="w200 long" type="text" name="tpl" id="tpl" value="{$format.current.tpl}"><br/>
				<i>{foreach item=field_data key=field from=$format.available}
						<span class="sample">[{$field}]</span>
					{/foreach}</i>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='example' gid='start'}</div>
			<div id="example" class="v"></div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/start/settings/date_formats">{l i='btn_cancel' gid='start'}</a>
</form>

{literal}
<script type="text/javascript">
	$(function(){
		new date_formats({
			siteUrl: '{/literal}{$site_url}{literal}'
		});
	});
</script>
{/literal}

<div class="clr"></div>

{include file="footer.tpl"}