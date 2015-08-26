<li><div class="l" id="link_add_funds"><a id="btn_add_funds" href="javascript:void(0)">{l i='link_add_funds' gid='users_payments'}</a></div></li>
<script type="text/javascript">
	var add_funds_link = "{$site_url}admin/users_payments/ajax_add_funds";
	var add_funds_form_link = "{$site_url}admin/users_payments/ajax_add_funds_form";
	{literal}
	$(function() {
		loading_funds = new loadingContent({
			linkerObjID: 'link_add_funds',
			loadBlockWidth: '350px',
			loadBlockLeftType: 'center',
			loadBlockTopType: 'bottom',
			closeBtnClass: 'w'
		});
		$('#btn_add_funds').click(function() {
			open_add_funds(add_funds_form_link);
			return false;
		});
	});
	function open_add_funds(url){
		$.ajax({
			url: url, 
			type: 'GET',
			cache: false,
			success: function(data){
				loading_funds.show_load_block(data);
			}
		});
	}
	function send_add_funds(){
		var data = new Array();
		$('.grouping:checked').each(function(i){
			data[i] = $(this).val();
		});
		
		if(data.length > 0){
			$.ajax({
				url: add_funds_link, 
				type: 'POST',
				cache: false,
				dataType: 'json',
				data: {amount: $('#add_fund_amount').val(), user_ids: data}, 
				success: function(data){
					if(typeof(data.error) != 'undefined' && data.error != ''){
						error_object.show_error_block(data.error, 'error');
					}else{
						location.reload();
					}
				}
			});
		}else{
			error_object.show_error_block('{/literal}{l i="error_no_users_to_add_funds" gid="users_payments" type="js"}{literal}', 'error');
		}
	}
	{/literal}
</script>