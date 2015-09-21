<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-02 09:07:55 Pacific Daylight Time */ ?>

<div class="load_content_controller">
	<div class="inside">
		
		<form id="delete_user" class="edit-form n100" action="<?php echo $this->_vars['data']['action']; ?>
" method="post" enctype="multipart/form-data"  >
			
			<h1><?php echo l('success_text_delete', 'media', '', 'text', array()); ?></h1>
			
			<div class="btn">
				<div class="l">
					<input type="submit" id="lie_delete" name="btn_confirm" value="<?php echo l('btn_confirm', 'media', '', 'text', array()); ?>" >
				</div>
			</div>
			
		</form>
		
	</div>
</div>

<script type="text/javascript">
	<?php echo '
		$(function(){
			
			$(\'#delete_user\').unbind(\'submit\').on(\'submit\', function(e){
				e.preventDefault();
				var data = new Array();
				$(\'.grouping:checked\').each(function(i){
					data[i] = $(this).val();
				});
				if(data.length > 0){
					$.ajax({
						url: site_url + \'admin/media/ajax_delete_media/\',
						data: {file_ids: data},
						type: "POST",
						cache: false,
						success: function(data){
							reload_this_page(\'index/\'+param);
						}
					});
				}
			});
		});
	'; ?>

</script>
