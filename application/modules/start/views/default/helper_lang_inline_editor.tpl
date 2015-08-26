<div class="content-block load_content">
	<h1>{l i='header_inline_lang_edit' gid='start'}</h1>
	<div class="inside edit_block">
		<form name="lang_edit" class="edit-form n100">
			{foreach item=item key=key from=$langs}
			{counter print=false assign=counter}
				<div class="r">
					<div class="f"><label>{$item.name}</label>: </div>
					<div class="v">
						{if $is_textarea}
						<textarea name="field{$key}" rows="10" cols="80" lang-editor="redactor" lang-id="{$key}"></textarea>
						{else}
						<input type="text" name="field{$key}" value="" lang-editor="redactor" lang-id="{$key}" />
						{/if}
					</div>
				</div>
			{/foreach}
			
			<div class="b"><input type="button" id="lie_save" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div>
			{*<div class="fright">
				<a href="javascript:void(0);" id="lie_close" class="btn-link"><ins class="with-icon i-larr no-hover"></ins>{l i='btn_cancel' gid='start'}</a>
			</div>*}
		</form>
	</div>
</div>
