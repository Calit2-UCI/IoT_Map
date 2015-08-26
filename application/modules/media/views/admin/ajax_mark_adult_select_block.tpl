<div class="load_content_controller">
	<h1>{l i='btn_mark_adult' gid='media'}?</h1>
	<div class="inside">
		
		<form id="mark_adult" class="edit-form n100" action="{$data.action}" method="post" enctype="multipart/form-data"  >
			<div class="btn">
				<div class="l">
					<input type="submit" id="lie_mark_adult" name="btn_confirm" value="{l i='btn_confirm' gid='media'}" >
				</div>
			</div>
		</form>
		
	</div>
</div>

<script type="text/javascript">
	{literal}
		$(function(){
			$('#mark_adult').unbind('submit').on('submit', function(e){
				e.preventDefault();
				var data = new Array();
				$('.grouping:checked').each(function(i){
					data[i] = $(this).val();
				});
				if(data.length > 0){
					$.ajax({
						url: site_url + 'admin/media/ajax_mark_adult_media/',
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
	{/literal}
</script>
