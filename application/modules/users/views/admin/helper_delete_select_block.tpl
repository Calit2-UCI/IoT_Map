<a title="{$params.title_text}" id='delete_select_block_{$params.id_user}' href="{$site_url}admin/users/ajax_delete_select/{$params.id_user}/{$params.deleted}"><i class="fa fa-trash"></i></a>
<script>
{literal}
$(function(){
	$('#delete_select_block_{/literal}{$params.id_user}{literal}').unbind('click').click(function(){
		$.ajax({
			url: site_url + 'admin/users/ajax_delete_select/{/literal}{$params.id_user}/{$params.deleted}{literal}',
			cache: false,
			success: function(data){
				delete_select_block.show_load_block(data);
			}
		});
		return false;
	});
});
{/literal}
</script>