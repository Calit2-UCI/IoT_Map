{include file="header.tpl" load_type='ui'}
<div class="lef">
	<div class="edit-form n100">
		<div class="row header">{l i='admin_header_services_in_package' gid='packages'} : {$package.name}</div>
		<div class="row zebra">
			<ol id="package_services" class="connectSort">
				{foreach item=item key=key from=$package.services_list}
				<li id="package_services_{$item.id}"><b class="name">{$item.name}</b><input class='service_id' type='hidden' value='{$item.id}'><br><i>{l i='field_price' gid='packages'}:<span class="price">{$item.price}{block name='currency_format_output' module='start'}</span></i>{assign var=k value=$item.id}<input type="text" value='{$package.services_list_array[$k]}' class="packaqe_count" id="count_package_services_{$item.id}"></li>
				{/foreach}
			</ol>
		</div>
		{l i='total' gid='packages'}: <span id='total_price'>{$total_price}</span>{block name='currency_format_output' module='start'}<br>
		{l i='package_cost' gid='packages'}: {$package.price}{block name='currency_format_output' module='start'}
	</div>
	<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" onclick="javascript: save_services();"></div></div>
	<a class="cancel" href="{$site_url}admin/packages/index">{l i='btn_cancel' gid='start'}</a>
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header">{l i='admin_header_available_services' gid='packages'}</div>
		<div class="row zebra">
			<ol id="available_services" class="connectSort">
				{foreach item=item key=key from=$services}
				<li id="available_services_{$item.id}"><b class="name">{$item.name}</b><input class='service_id' type='hidden' value='{$item.id}'><br><i>{l i='field_price' gid='packages'}:<span class="price">{$item.price}{block name='currency_format_output' module='start'}</span></i><input type="text" value="1" class="packaqe_count hide" id="count_package_services_{$item.id}"></li>
				{/foreach}
			</ol>
		</div>
	</div>
</div>
<div class="clr"></div>
<script type="text/javascript">
var save_url = '{$site_url}admin/packages/ajax_save_package_services/{$package.id}';
var return_url = '{$site_url}admin/packages/index';
{literal}
$(function(){
	$("#package_services, #available_services").sortable({
		connectWith: '.connectSort',
		placeholder: 'limiter',
		scroll: true,
		forcePlaceholderSize: true,
		stop: function(event, ui) {
			update_total_price();
			$("#package_services li").each(function(i){
				$(this).find('.packaqe_count').removeClass('hide').change(function(){
					update_total_price();
				});
			});
			$("#available_services li").each(function(i){
				$(this).find('.packaqe_count').addClass('hide');
			});
		}
	})
	
	$("#package_services input").on('change', function(){
		update_total_price();
	});
});
function update_total_price(){
	price = 0;
	$("#package_services li").each(function(i){
		var id = $(this).attr('id');
		count = $(this).find('.packaqe_count').val();
		price += parseFloat($('#'+id+' .price').html())*count;
	});
	$('#total_price').html(price);
}
function save_services(){
	var data = new Object();
	$("#package_services li").each(function(i){
		var id = $(this).attr('id');
		data[$('#'+id+' .service_id').val()] =  $('#'+id+' .packaqe_count').val();
	});
	$.ajax({
		url: save_url, 
		type: 'POST',
		data: {services: data}, 
		cache: false,
		success: function(data){
			location.href = return_url;
		}
	});
}
{/literal}
</script>
{include file="footer.tpl"}