{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_currency_change' gid='payments'}{else}{l i='admin_header_currency_add' gid='payments'}{/if}</div>
		<div class="row">
			<div class="h">{l i='field_currency_gid' gid='payments'}:&nbsp;* </div>
			<div class="v"><input type="text" id="gid" name="gid" value="{$data.gid}" class="short"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_currency_name' gid='payments'}:&nbsp;* </div>
			<div class="v"><input type="text" id="name" name="name" value="{$data.name}" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_currency_abbr' gid='payments'}:&nbsp;* </div>
			<div class="v"><input type="text" id="abbr" name="abbr" value="{$data.abbr}" class="short"></div>
		</div>
		{if $data.id && $data.gid ne $base_currency}
		<div class="row">
			<div class="h">{l i='field_per_base' gid='payments'} {$base_currency}:</div>
			<div class="v"><input type="text" id="per_base" name="per_base" value="{$data.per_base|escape}"></div>
		</div>
		{/if}
		<div class="row">
			<div class="h">{l i='field_decimal_separator' gid='payments'}:&nbsp;*</div>
			<div class="v">
				{foreach item=dec_sep key=key from=$format.dec_sep}
					<span><input id="dec_sep_{$key}" value="{$dec_sep}" name="dec_sep" type="radio" {if !$format.used.dec_sep || $format.used.dec_sep eq $dec_sep}checked="checked"{/if} /><label for="dec_sep_{$key}">{l i='field_decimal_separator_'$key gid='payments'}</label></span>
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_groups_separator' gid='payments'}:&nbsp;*</div>
			<div class="v">
				{foreach item=gr_sep key=key from=$format.gr_sep}
					<span><input id="gr_sep_{$key}" value="{$gr_sep}" name="gr_sep" type="radio" {if !$format.used.gr_sep || $format.used.gr_sep eq $gr_sep}checked="checked"{/if} /><label for="gr_sep_{$key}">{l i='field_groups_separator_'$key gid='payments'}</label></span>
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_decimal_part' gid='payments'}:&nbsp;*</div>
			<div class="v">
				{foreach item=dec_part key=key from=$format.dec_part}
					<span><input id="dec_part_{$key}" value="{$dec_part}" name="dec_part" type="radio" {if !$format.used.dec_part || $format.used.dec_part eq $dec_part}checked="checked"{/if} /><label for="dec_part_{$key}">{l i='field_decimal_part_'$key gid='payments'}</label></span>
				{/foreach}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_currency_format' gid='payments'}:&nbsp;*</div>
			<div class="v" id="templates">
				{foreach item=template_cur key=key from=$format.template}
					<span><input type="radio" name="template" id="template_{$key}" value="{$template_cur}" {if !$format.used.template || $format.used.template eq $template_cur}checked="checked"{/if} /><label for="template_{$key}"></label></span>
				{/foreach}
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/payments/settings">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
$(function(){
	new adminPayments();
});
{/literal}</script>

{include file="footer.tpl"}