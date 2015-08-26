<div class="load_content_controller">
	<div class="inside">
		
		<form id="delete_kisses" class="edit-form n100" action="{$data.action}" method="post" enctype="multipart/form-data"  >
			
			<h1>{l i='note_delete_kisses' gid='kisses'}</h1>
			
			<div class="btn">
				<div class="l">
					<input type="submit" id="lie_delete" name="btn_confirm" value="{l i='btn_confirm' gid='kisses'}" >
				</div>
			</div>
			
		</form>
		
	</div>
</div>

<script type="text/javascript">
var reload_link = "{$site_url}admin/kisses/";
	{literal}
		$(function(){
			
			$('#delete_kisses').unbind('submit').on('submit', function(e){
				e.preventDefault();
				var data = new Array();
				$('.grouping:checked').each(function(i){
					data[i] = $(this).val();
				});
				if(data.length > 0){
					$.ajax({
						url: site_url + 'admin/kisses/ajax_delete_select/',
						data: {file_ids: data},
						type: "POST",
						cache: false,
						success: function(data){
							reload_this_page('index/');
						}
					});
				}
			});
		});
		
		function reload_this_page(value){
			var link = reload_link + value;
			location.href=link;
		}
	{/literal}
</script>
