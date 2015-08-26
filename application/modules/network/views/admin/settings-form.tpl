<form id="network-form" method="post" action="{$site_url}admin/network/index" name="save-form">
	<div class="edit-form">
		<h2 class="row header">
			{l i='admin_header_data' gid='network'}
		</h2>
		{* Domain *}
		<div class="row">
			<div class="h">
				{l i='admin_field_domain' gid='network'}
			</div>
			<div class="v">
				<input id="network-domain"  type="text" value="{$data.domain}" name="domain" class="long">
			</div>
		</div>
		{* Key *}
		<div class="row">
			<div class="h">
				{l i='admin_field_key' gid='network'}:
			</div>
			<div class="v">
				<input id="network-key" required class="long" type="text" value="{$data.key}" name="key">
			</div>
		</div>
        {* Is upload photos *}
        <div class="row">
			<div class="h">
				{l i='admin_field_is_upload_photos' gid='network'}:
			</div>
			<div class="v">
                <input type="hidden" name="is_upload_photos" value="0">
				<input id="network-is-upload-photos" type="checkbox" value="1" name="is_upload_photos" {if $data.is_upload_photos}checked{/if}>
			</div>
		</div>
		<h2 class="row header">
			{l i='admin_header_filter' gid='network'}:
		</h2>
		{* Multiselect fields *}
		{foreach item=fields from=$form_fields}
			<div class="row">
				{block name='multiselect' module='start' 
					fields=$fields 
					selected=$selected_options 
					limits=$form_limits 
					all_value='all'}
			</div>
		{/foreach}
		{* Age *}
		<div class="row">
			<div class="h">
				{l i='admin_field_age' gid='network'}:
			</div>
			<div class="v">
				{l i='admin_field_age_min' gid='network'}
				<input class="short" type="number" min="{$age_min}" max="{$age_max}" name="min_age" value="{$data.min_age}">
				{l i='admin_field_age_max' gid='network'}
				<input class="short" type="number" min="{$age_min}" max="{$age_max}" name="max_age" value="{$data.max_age}">
			</div>
		</div>
	</div>
	<div class="btn">
		<div class="l">
			<input type="submit" name="btn-save" value="{l i='btn_save' gid='start' type='button'}">
		</div>
	</div>
	<a class="cancel" href="{$back_url}">{l i='btn_cancel' gid='start'}</a>
</form>
