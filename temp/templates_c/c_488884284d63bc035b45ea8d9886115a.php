<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:13:28 Pacific Daylight Time */ ?>

<li><div class="l" id="link_add_funds"><a id="btn_add_funds" href="javascript:void(0)"><?php echo l('link_add_funds', 'users_payments', '', 'text', array()); ?></a></div></li>
<script type="text/javascript">
	var add_funds_link = "<?php echo $this->_vars['site_url']; ?>
admin/users_payments/ajax_add_funds";
	var add_funds_form_link = "<?php echo $this->_vars['site_url']; ?>
admin/users_payments/ajax_add_funds_form";
	<?php echo '
	$(function() {
		loading_funds = new loadingContent({
			linkerObjID: \'link_add_funds\',
			loadBlockWidth: \'350px\',
			loadBlockLeftType: \'center\',
			loadBlockTopType: \'bottom\',
			closeBtnClass: \'w\'
		});
		$(\'#btn_add_funds\').click(function() {
			open_add_funds(add_funds_form_link);
			return false;
		});
	});
	function open_add_funds(url){
		$.ajax({
			url: url, 
			type: \'GET\',
			cache: false,
			success: function(data){
				loading_funds.show_load_block(data);
			}
		});
	}
	function send_add_funds(){
		var data = new Array();
		$(\'.grouping:checked\').each(function(i){
			data[i] = $(this).val();
		});
		
		if(data.length > 0){
			$.ajax({
				url: add_funds_link, 
				type: \'POST\',
				cache: false,
				dataType: \'json\',
				data: {amount: $(\'#add_fund_amount\').val(), user_ids: data}, 
				success: function(data){
					if(typeof(data.error) != \'undefined\' && data.error != \'\'){
						error_object.show_error_block(data.error, \'error\');
					}else{
						location.reload();
					}
				}
			});
		}else{
			error_object.show_error_block(\'';  echo l("error_no_users_to_add_funds", "users_payments", '', "js", array());  echo '\', \'error\');
		}
	}
	'; ?>

</script>