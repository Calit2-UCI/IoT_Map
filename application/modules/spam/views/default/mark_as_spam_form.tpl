<div class="content-block load_content">
	<h1>{l i='header_spam_form' gid='spam'}</h1>
	<div class="inside edit_block">
		<form id="spam_form" action="" method="POST">
			<div class="r">
				{if $data.form_type == 'select_text'}
				<div class="f">{l i='field_spam_reason' gid='spam'}&nbsp;*</div>
				<div class="v">
					<select name="data[id_reason]">
						{foreach item=item key=key from=$reasons.option}<option value="{$key|escape}">{$item}</option>{/foreach}
					</select>
				</div>
			</div>
			<div class="r">
				<div class="f">{l i='field_spam_message' gid='spam'}&nbsp;({l i='field_spam_optional' gid='spam'})</div>
				<div class="v"><textarea name="data[message]" rows="5" cols="23" style="resize:none;"></textarea></div>
				{/if}
			</div>
			<div class="r"><input type="button" value="{l i='btn_send' gid='start' type='button'}" id="close_btn" /></div>
			<input type="hidden" name="type_gid" value="{$data.gid}" />
			<input type="hidden" name="object_id" value="{$object_id}" />
			{if $is_spam_owner}<input type="hidden" name="is_owner" value="1">{/if}
		</form>
	</div>
</div>

