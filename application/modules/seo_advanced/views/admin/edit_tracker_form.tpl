{include file="header.tpl"}
<form method="post" action="{$data.action}" name="save_form">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_seo_advanced_tracker_editing' gid='seo_advanced'}</div>
		<div class="row">
			<div class="h">{l i='field_use_ga_tracker' gid='seo_advanced'}: </div>
			<div class="v">
				<input type="radio" value="1" {if $data.seo_ga_default_activate}checked{/if} name="seo_ga_default_activate" id="ga_active_yes" onclick="javascript: check_tag('ga_active', this);"> 
				<label for="ga_active_yes">{l i='use_ga_tracker_yes' gid='seo_advanced'}</label>&nbsp;
				<input type="radio" value="0" {if !$data.seo_ga_default_activate}checked{/if} name="seo_ga_default_activate" id="ga_active_no" onclick="javascript: check_tag('ga_active', this);"> 
				<label for="ga_active_no">{l i='use_ga_tracker_no' gid='seo_advanced'}</label>
			</div>
		</div>

		<div class="row zebra">
			<div class="h">{l i='field_ga_account_id' gid='seo_advanced'}: </div>
			<div class="v"><input type="text" value="{$data.seo_ga_default_account_id}" name="seo_ga_default_account_id" id="input_ga_active" {if !$data.seo_ga_default_activate}disabled{/if}></div>
		</div>

		<div class="row zebra">
			<div class="h">&nbsp;</div>
			<div class="v">{l i='ga_tracker_hint' gid='seo_advanced'}</div>
		</div>

		<!-- other trackers -->
		<div class="row">
			<div class="h">{l i='field_use_other_tracker' gid='seo_advanced'}: </div>
			<div class="v">
				<input type="radio" value="1" {if $data.seo_ga_manual_activate}checked{/if} name="seo_ga_manual_activate" id="other_active_yes" onclick="javascript: check_tag('other_active', this);"> 
				<label for="other_active_yes">{l i='use_ga_tracker_yes' gid='seo_advanced'}</label>&nbsp;
				<input type="radio" value="0" {if !$data.seo_ga_manual_activate}checked{/if} name="seo_ga_manual_activate" id="other_active_no" onclick="javascript: check_tag('other_active', this);"> 
				<label for="other_active_no">{l i='use_ga_tracker_no' gid='seo_advanced'}</label>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_code_placement' gid='seo_advanced'}: </div>
			<div class="v">
				<input type="radio" value="top" {if $data.seo_ga_manual_placement eq 'top'}checked{/if} name="seo_ga_manual_placement" id="other_top"> 
				<label for="other_top">{l i='tracker_top' gid='seo_advanced'}</label>&nbsp;
				<input type="radio" value="footer" {if $data.seo_ga_manual_placement eq 'footer'}checked{/if} name="seo_ga_manual_placement" id="other_footer"> 
				<label for="other_footer">{l i='tracker_footer' gid='seo_advanced'}</label>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_other_code' gid='seo_advanced'}: </div>
			<div class="v"><textarea name="seo_ga_manual_tracker_code" id="input_other_active" style="height: 170px" {if !$data.seo_ga_manual_activate}disabled{/if}>{$data.seo_ga_manual_tracker_code}</textarea></div>
		</div>
		<!-- other trackers -->

		<div class="row zebra">
			<div class="h">&nbsp;</div>
			<div class="v">{l i='other_tracker_hint' gid='seo_advanced'}</div>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/seo_advanced">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>
{literal}
	function check_tag(gid, object){
		if(object.checked){
			$('#input_'+gid).removeAttr("disabled");
		}else{
			$('#input_'+gid).attr('disabled', 'disabled');
		}
	}
{/literal}
</script>

{include file="footer.tpl"}
