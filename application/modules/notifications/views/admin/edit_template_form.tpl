{include file="header.tpl"}

<div class="menu-level3">
	<ul id="edit_tabs">
		<li class="active" onclick="javascript: openTab('template_settings', this); return false;"><a href="#" onclick="return false;">{l i='template_settings' gid='notifications'}</a></li>
		{foreach item=item key=key from=$langs}
			<li onclick="javascript: openTab('lang{$item.id}', this); return false;"><a href="#" onclick="return false;">{$item.name}</a></li>
		{/foreach}
	</ul>
	&nbsp;
</div>
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150" id="edit_divs">
		<div id="template_settings"class="tab">
			<div class="row header">{l i='admin_header_template_edit' gid='notifications'}</div>
			<div class="row zebra">
				<div class="h">{l i='field_template_gid' gid='notifications'}: </div>
				<div class="v">{if $allow_edit}<input type="text" value="{$data.gid}" name="gid" class="middle">{else}{$data.gid}{/if}</div>
			</div>
			<div class="row">
				<div class="h">{l i='field_template_name' gid='notifications'}: </div>
				<div class="v">{if $allow_edit}<input type="text" value="{$data.name}" name="name" class="middle">{else}{$data.name}{/if}</div>
			</div>
			<div class="row zebra">
				<div class="h">{l i='field_content_type' gid='notifications'}: </div>
				<div class="v">
					{if $allow_edit}
						<select name="content_type" class="middle">
							<option value="text" {if $data.content_type eq 'text'}selected{/if}>{l i='field_content_type_text' gid='notifications'}</option>
							<option value="html" {if $data.content_type eq 'html'}selected{/if}>{l i='field_content_type_html' gid='notifications'}</option>
						</select>
				{else}{$data.content_type}{/if}
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_template_vars' gid='notifications'}: </div>
			<div class="v">
				{if $allow_edit && $allow_var_edit}
					<input type="text" value="{$data.vars_str}" name="vars" class="long">
			{else}{$data.vars_str}{/if}
			<br><i>{l i='field_template_vars_text' gid='notifications'}</i></div>
	</div>
</div>

{foreach item=item key=key from=$langs }
	{assign var='content' value=$data_content[$key]}
	<div id="lang{$item.id}" class="tab hide">
		<div class="row header">{l i='admin_header_template_content' gid='notifications'}: {$item.name}</div>
		<div class="row zebra">
			<div class="h">{l i='field_available_global_variables' gid='notifications'}: </div>
			<div class="v">{foreach item=var from=$global_vars}[{$var}] {foreachelse}<i>{l i='empty_variables' gid='notifications'}</i>{/foreach}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_available_variables' gid='notifications'}: </div>
			<div class="v">{foreach item=var from=$data.vars}[{$var}] {foreachelse}<i>{l i='empty_variables' gid='notifications'}</i>{/foreach}</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_subject' gid='notifications'}: </div>
			<div class="v"><input type="text" value="{$content.subject}" name="subject[{$item.id}]" class="long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_content' gid='notifications'}: </div>
			<div class="v">{if $data.content_type eq 'html'}{$content.content_fck}{else}<textarea name="content[{$item.id}]" class="mail-content">{$content.content}</textarea>{/if}</div>
		</div>
	</div>
{/foreach}

<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
<a class="cancel" href="{$site_url}admin/notifications/templates">{l i='btn_cancel' gid='start'}</a>
</div>
</form>
<script>
	{literal}
function openTab(id, object){
	$('#edit_divs > div.tab').hide();
	$('#'+id).show();
	$('#edit_tabs > li').removeClass('active');
	$(object).addClass('active');
}

	{/literal}
</script>
<div class="clr"></div>
{include file="footer.tpl"}