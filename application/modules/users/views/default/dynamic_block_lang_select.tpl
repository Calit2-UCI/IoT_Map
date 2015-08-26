{strip}
{if $dynamic_block_lang_select_data.count_active > 1}
	<div id="lang_select_wrapper_{$dynamic_block_lang_select_data.rand}"{if $dynamic_block_lang_select_data.params.right_align} class="righted"{/if}>
		{*<span class="mr5">{l i='text_language' gid='users'}</span>*}
		<div class="ib vmiddle">
			{selectbox input='language' id='lang_select_'$dynamic_block_lang_select_data.rand value=$dynamic_block_lang_select_data.languages selected=$dynamic_block_lang_select_data.lang_id class='cute'}
		</div>
	</div>
				
	<script type="text/javascript">{literal}
		$(function(){
			loadScripts(
				"{/literal}{js module='start' file='selectbox.js' return='path'}{literal}",
				function(){
					var data = {/literal}{json_encode data=$dynamic_block_lang_select_data}{literal};
					lang_select{/literal}{$dynamic_block_lang_select_data.rand}{literal} = new selectBox({
						elementsIDs: ['lang_select_'+data.rand],
						force: true,
						dropdownClass: 'dropdown cute',
						dropdownAutosize: true,
						dropdownRight: data.params.right_align ? true : false
					});
					$('#lang_select_wrapper_'+data.rand).off('change', 'input[name="language"]').on('change', 'input[name="language"]', function(){
						location.href = site_url+'users/change_language/'+$(this).val();
					});
				},
				'lang_select{/literal}{$dynamic_block_lang_select_data.rand}{literal}',
				{async: false}
			);
		});
	</script>{/literal}
{/if}
{/strip}