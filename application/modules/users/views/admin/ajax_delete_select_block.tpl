<div class="load_content_controller">
	<h1>{l i='admin_header_delete_user' gid='users'}</h1>
	<div class="inside">
		
		<form name="delete_user" class="edit-form n100" action="{$data.action}" method="post" enctype="multipart/form-data"  >
			<div class="row zebra">
				<div class="plr10" id="nickname_list">
				{section loop=$data.user_names name=s}
					{$data.user_names[s]}{if !$templatelite.section.s.last},&nbsp;{/if}
				{/section}
				</div>
			</div>
			{foreach item=item from=$data.user_ids}
				<input type="hidden" name="user_ids[]" value="{$item}">
			{/foreach}
			{if !$data.deleted}
			<div class="row">
				<div class="h">
					<input type="radio" name="action_user" value="block_user" id="block_user">
				</div>
				<div class="v"><label>{l i='link_deactivate_user' gid='users'}</label></div>
			</div>
			{/if}
			<div class="row">
				<div class="h">
					<input type="radio" name="action_user" value="delete_user" id="delete_user">
				</div>
				<div class="v"><label>{l i='delete' gid='users'}</label>:</div> 
			</div>
			<div>

				{foreach item=item key=key from=$callbacks_data}
				{counter print=false assign=counter}
					<div class="row{if $counter is div by 2} zebra{/if}">
						<div class="h">
							<input type="checkbox" name="module[]" value="{$item.callback_gid}" {$item.disabled_attr}>
						</div>
						<div class="v"><label>{$item.name}</label></div>
					</div>
				{/foreach}
			</div>
			<div class="btn"><div class="l"><input type="submit" id="lie_delete" name="btn_confirm_del" value="{l i='btn_confirm_del' gid='users'}" disabled></div></div>
		</form>
	</div>
</div>
<script type="text/javascript">
var user_deleted = '{$data.deleted}';
{literal}
$(function(){
	if(user_deleted == 0){
		$('#block_user').bind('click', function(){
			$('input[name^=module]').prop("disabled", true);
			$('#lie_delete').prop("disabled", false);
		});
		$('#delete_user').bind('click', function(){
			$('input[name^=module]').prop("disabled", false);
			$('input[value=users_delete]').prop('checked',true);
			if($('input[name^=module]:checked').val()){
				$('#lie_delete').prop("disabled", false);
			}else{
				$('#lie_delete').prop("disabled", true);
			}
		});
	}
	$('input[name^=module]').bind('click', function(){
		$('#delete_user').prop('checked', true);
		if($('input[name^=module]:checked').val()){
			$('#lie_delete').prop("disabled", false);
		}else{
			$('#lie_delete').prop("disabled", true);
		}
	});
	var nickname_list  =$('#nickname_list').text();
	var crop_list = nickname_list.substr(0,100)+' ...';
	$('#nickname_list').text(crop_list);
	$('#nickname_list').hover(
		function(){$(this).text(nickname_list);},
		function(){$(this).text(crop_list);
	});
});
{/literal}
</script>