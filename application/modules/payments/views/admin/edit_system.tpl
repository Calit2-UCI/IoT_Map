{include file="header.tpl" load_type="ui"}
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_payment_system_change' gid='payments'}</div>
		<div class="row">
			<div class="h">{l i='field_system_name' gid='payments'}: </div>
			<div class="v">{$data.name}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_system_use' gid='payments'}: </div>
			<div class="v">{if $data.in_use}{l i='system_in_use' gid='payments'}{else}{l i='system_not_in_use' gid='payments'}{/if}</div>
		</div>
		{foreach item=item key=key from=$data.map}
		<div class="row">
			<div class="h">{$item.name}: </div>
			<div class="v">
				{switch from=$item.type}
				{case value='text'}
				<input type="text" name="map[{$key}]" value="{$item.value|escape}" {if $item.size eq 'small'}class="short"{else $item.size eq 'big'}class="long"{/if}>
				{case value='textarea'}
				<textarea name="map[{$key}]" rows="10" cols="80" {if $item.size eq 'small'}class="short"{elseif $item.size eq 'big'}class="long"{/if}>{$item.value}</textarea>
				{/switch}
			</div>
		</div>
		{/foreach}
                {if $data.tarifs_editable || $data.tarifs_type}
                <div class="row">
                    <div class="h">{l i='field_system_tarifs' gid='payments'}: </div>
                    <div class="v">
                        <a href="#" id="add_operator_link">
                            {if $data.tarifs_type eq 2}
                            {l i='link_system_operator_add_new' gid='payments'}
                            {else}
                            {l i='link_system_tarif_add_new' gid='payments'}
                            {/if}
                        </a>
                        <div class="system-operators" id="system_tarifs_block">
                            {$system_tarifs_block}
                        </div>
                    </div>
                </div>
                <script type='text/javascript'>{literal}
                    var PaymentSystemTarifs_obj;
                    loadScripts(
                            "{/literal}{js module='payments' file='payment-system-tarifs.js' return='path'}{literal}",
                    function(){
                        PaymentSystemTarifs_obj = new PaymentSystemTarifs({
                            siteUrl: '{/literal}{$site_root}{literal}',
                            systemGid: '{/literal}{$data.gid}{literal}',
                            useOperators: '{/literal}{if $data.tarifs_type eq 2}true{else}false{/if}{literal}',
                        });
                    },
                    'PaymentSystemTarifs_obj'
                            );
                </script>{/literal}
                {/if}
		<div class="row">
			<div class="h">{l i='field_info_data' gid='payments'}: </div>
			<div class="v">
				{foreach item=lang_item key=lang_id from=$langs}
				{assign var='name' value='info_data_'+$lang_id}
				{if $lang_id eq $current_lang_id}
				<textarea name="info[{$name}]" rows="10" cols="80" lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}">{$data[$name]|escape}</textarea>
				{else}
				<input type="hidden" name="info[{$name}]" value="{$data[$name]|escape}" lang-editor="value" lang-editor-type="data-name" lang-editor-lid="{$lang_id}" />
				{/if}
				{/foreach}
				<a href="#" lang-editor="button" lang-editor-type="data-name"><img src="{$site_root}{$img_folder}icon-translate.png" width="16" height="16"></a>
			</div>
		</div>
		<div class="edit-form n150">
			<div class="row header">{l i='admin_header_logo_upload' gid='payments'}</div>
			<div class="row zebra">
				<div class="h">{l i='field_logo_file' gid='payments'}: </div>
				<div class="v">
					<input type="file" value="" name="logo">
					<br><br>
					<img src="{$data.logo_url}" />
				</div>
				{if $data.logo}
				<div class="row zebra">
					<div class="h">{l i='field_logo_delete' gid='payments'}: </div>
					<div class="v"><input type="checkbox" name="logo_delete" value="1"></div>
				</div>
				{/if}
			</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/payments/systems">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
{block name='lang_inline_editor' module='start' textarea=1}
<script>{literal}
$(function(){
	$("div.row:odd").addClass("zebra");
});
{/literal}</script>

{include file="footer.tpl"}
