{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_subscription_edit' gid='subscriptions'}</div>

		<div class="row zebra">
            <div class="h">{l i='field_subscription_name' gid='subscriptions'}: </div>
            <div class="v">
				{assign var="lang_gid" value=$data.lang_gid}
				{assign var="name_i" value=$data.name_i}
				<input type="text" value="{if $validate_lang_var}{$validate_lang_var[$cur_lang]}{else}{l i=$data.name_i gid='subscriptions' lang=$cur_lang}{/if}" name="langs[{$cur_lang}]" class="middle">
				{if $languages_count > 1}
                    &nbsp;&nbsp;<a href="#" onclick="showLangs('name_langs'); return false;">{l i='others_languages' gid='subscriptions'}</a><br>
                    <div id="name_langs" class="hide p-top2">
						{foreach item=item key=lang_id from=$languages}{if $lang_id ne $cur_lang}
								<input type="text" value="{if $validate_lang}{$validate_lang[$lang_id]}{else}{l i=$data.name_i gid='subscriptions' lang=$lang_id}{/if}" name="langs[{$lang_id}]">&nbsp;|&nbsp;{$item.name}<br>
						{/if}{/foreach}
                    </div>
				{/if}
            </div>
		</div>

		<div class="row">
            <div class="h">{l i='field_subscribe_type' gid='subscriptions'}: </div>
            <div class="v">
				<select name="subscribe_type" class="middle">
                    <option value="auto" {if $data.subscribe_type eq 'auto'}selected{/if}>{l i='field_subscribe_type_auto' gid='subscriptions'}</option>
                    <option value="user" {if $data.subscribe_type eq 'user'}selected{/if}>{l i='field_subscribe_type_user' gid='subscriptions'}</option>
				</select>
            </div>
		</div>

		<div class="row zebra">
            <div class="h">{l i='field_id_template' gid='subscriptions'}: </div>
            <div class="v">
				<select name="id_template" class="long">
			{foreach item=item from=$templates}<option value="{$item.id}" {if $data.id_template eq $item.id}selected{/if}>{$item.name}</option>{/foreach}
		</select>
	</div>
</div>

<div class="row">
	<div class="h">{l i='field_id_content_type' gid='subscriptions'}: </div>
	<div class="v">
		<select name="id_content_type" class="long">
	{foreach item=item from=$content_types}<option value="{$item.id}" {if $data.id_content_type eq $item.id}selected{/if}>{$item.name}</option>{/foreach}
</select>
</div>
</div>
{js file='jquery-ui.custom.min.js'}
<LINK href='{$site_root}{$js_folder}jquery-ui/jquery-ui.custom.css' rel='stylesheet' type='text/css' media='screen'>

<script type='text/javascript'>{literal}

   $(function(){
	   $( "#datepicker1, #datepicker2" ).datepicker({dateFormat :'yy-mm-dd'});
   });
	{/literal}</script>
    <div class="row zebra">
		<div class="h">{l i='field_scheduler' gid='subscriptions'}: </div>
		<div class="v">
			<p><input type="radio" name="scheduler_type" value="1" {if $data.scheduler.type eq 1}checked{/if}>{l i='manual' gid='subscriptions'}</p>
			<p class="pb2">
				<input type="radio" name="scheduler_type" value="2" {if $data.scheduler.type eq 2}checked{/if}>{l i='in_time' gid='subscriptions'}
				<input type='text' value='{if $data.scheduler.type eq 2}{$data.scheduler.date}{/if}' name="scheduler_date2" id="datepicker1" maxlength="10" class="short_long">
				<select name="scheduler_hours2" class="short">
			{foreach item=item from=$hours}<option value="{$item}" {if $data.scheduler.hours eq $item AND $data.scheduler.type eq 2}selected{/if}>{$item}</option>{/foreach}
		</select>
		<select name="scheduler_minutes2" class="short">
	{foreach item=item from=$minutes}<option value="{$item}" {if $data.scheduler.minutes eq $item AND $data.scheduler.type eq 2}selected{/if}>{$item}</option>{/foreach}
</select>
</p>
<p>
	<input type="radio" name="scheduler_type" value="3" {if $data.scheduler.type eq 3}checked{/if}>{l i='every_time' gid='subscriptions'}
	<select name="scheduler_period" class="short_long">
		<option value="day" {if $data.scheduler.period eq 'day'}selected{/if}>{l i='day' gid='subscriptions'} </option>
		<option value="week" {if $data.scheduler.period eq 'week'}selected{/if}>{l i='week' gid='subscriptions'} </option>
		<option value="month" {if $data.scheduler.period eq 'month'}selected{/if}>{l i='month' gid='subscriptions'} </option>
	</select>
	{l i='since' gid='subscriptions'}
	<input type='text' value='{if $data.scheduler.type eq 3}{$data.scheduler.date}{/if}' name="scheduler_date3" id="datepicker2" maxlength="10" class="short_long">
	<select name="scheduler_hours3" class="short">
{foreach item=item from=$hours}<option value="{$item}" {if $data.scheduler.hours eq $item AND $data.scheduler.type eq 3}selected{/if}>{$item}</option>{/foreach}
</select>
<select name="scheduler_minutes3" class="short">
{foreach item=item from=$minutes}<option value="{$item}" {if $data.scheduler.minutes eq $item AND $data.scheduler.type eq 3}selected{/if}>{$item}</option>{/foreach}
</select>
</p>
</div>
</div>

<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
<a class="cancel" href="{$site_url}admin/subscriptions/index">{l i='btn_cancel' gid='start'}</a>
</div>
</form>
<script>
	{literal}
function showLangs(divId){
	$('#'+divId).slideToggle();
}

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