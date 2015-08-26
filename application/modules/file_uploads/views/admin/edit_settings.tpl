{include file="header.tpl" load_type='ui'}
{js file='easyTooltip.min.js'}

<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{if $data.id}{l i='admin_header_config_change' gid='file_uploads'}{else}{l i='admin_header_config_add' gid='file_uploads'}{/if}</div>
		<div class="row zebra">
			<div class="h">{l i='field_name' gid='file_uploads'}: </div>
			<div class="v"><input type="text" value="{$data.name}" name="name" class="long"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_gid' gid='file_uploads'}: </div>
			<div class="v"><input type="text" value="{$data.gid}" name="gid" class="long"></div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_max_size' gid='file_uploads'}: </div>
			<div class="v"><input type="text" value="{$data.max_size}" name="max_size" class="short"> b <i>{l i='int_unlimit_condition' gid='file_uploads'}</i></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_name_format' gid='file_uploads'}: </div>
			<div class="v">
				<select name="name_format" id="name_format" class="long">{foreach item=item key=key from=$lang_name_format.option}<option value="{$key}" {if $key eq $data.name_format}selected{/if}>{$item}</option>{/foreach}</select>
			</div>
		</div>
		<div class="row zebra">
			<div class="h">{l i='field_file_formats' gid='file_uploads'}: </div>
			<div class="v">
				{foreach item=category_data key=category_key from=$formats}
				<div class="column">
					<input type="checkbox" class="category" id="cat-{$category_key}">
					<label for="cat-{$category_key}">{l i=$category_key gid='file_uploads'}</label><br />
					<ul>
						{foreach item=item key=key from=$category_data}
						<li>
						<input type="checkbox" name="file_formats[]" value="{$item}" {if $data.enable_formats[$item]}checked{/if} id="frm_{$item}">
						<label for="frm_{$item}">{$item}</label>
						</li>
						{/foreach}
					</ul>
				</div>
				{/foreach}
				<div class="clr"></div>
			</div>
		</div>
		<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
		<a class="cancel" href="{$site_url}admin/file_uploads/configs">{l i='btn_cancel' gid='start'}</a>
	</div>
</form>
<script type='text/javascript'>
{literal}
	$(function(){
		$(".tooltip").each(function(){
			$(this).easyTooltip({
				useElement: 'tt_'+$(this).attr('id')
			});
		});

		$('input.category').bind('click', function(){
			var checked = $(this).is(':checked');
			if(checked){
				$(this).parent().find('ul > li > input[type=checkbox]').prop('checked', true);
			}else{
				$(this).parent().find('ul > li > input[type=checkbox]').prop('checked', false);
			}
		});
	});
{/literal}
</script>
{include file="footer.tpl"}