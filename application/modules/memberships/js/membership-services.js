function membershipServices(optionArr){
	this.properties = {
		siteUrl: '/',
		saveUrl: 'admin/memberships/ajax_save_membership_services/',
		returnUrl: 'admin/memberships/index',
		membershipServicesId: 'membership_services',
		availableServicesId: 'available_services',
		totalPriceId: 'total_price',
		servicePriceClass: 'service_price',
		serviceCountClass: 'service_count',
		serviceIdClass: 'service_id',
		membershipId: null,
	};
	
	var _self = this;
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		
		$("#"+_self.properties.membershipServicesId+", #"+_self.properties.availableServicesId).sortable({
			connectWith: '.connectSort',
			placeholder: 'limiter',
			scroll: true,
			forcePlaceholderSize: true,
			stop: function(event, ui) {
				_self.update_total_price();
				$("#"+_self.properties.membershipServicesId).find('li').each(function(i){
					$(this).find('.'+_self.properties.serviceCountClass).removeClass('hide').change(function(){
						_self.update_total_price();
					});
				});
				$("#"+_self.properties.availableServicesId).find('li').each(function(i){
					$(this).find('.'+_self.properties.serviceCountClass).addClass('hide');
				});
			}
		});
		
		$("#"+_self.properties.membershipServicesId).off('change', 'li').on('change', 'li', function(){
			_self.update_total_price();
		});
		
		$("#"+_self.properties.membershipServicesId).find('input[type="button"]').bind('click', function(){
			_self.save_services();
		});
	};
	
	this.update_total_price = function(){
		var price = 0;
		$("#"+_self.properties.membershipServicesId).find('li').each(function(i){
			var id = $(this).attr('id');
			var itemPrice = $('#'+id).find('.'+_self.properties.servicePriceClass).html();
			var itemCount = $(this).find('.'+_self.properties.serviceCountClass).val();
			price += parseFloat(itemPrice)*itemCount;
		});
		$('#'+_self.properties.totalPriceId).html(price);
	}
	
	this.save_services = function(){
		var data = new Object();
		$("#"+_self.properties.membershipServicesId).find('li').each(function(i){
			var id = $(this).attr('id');
			var service_id = $('#'+id).find('.'+_self.properties.serviceIdClass).val();
			data[service_id] = $('#'+id).find('.'+_self.properties.serviceCountClass).val();
		});
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.saveUrl + _self.properties.membershipId, 
			type: 'POST',
			data: {services: data}, 
			cache: false,
			success: function(data){
				location.href = _self.properties.siteUrl + _self.properties.returnUrl;
			}
		});
	}
	
	_self.Init(optionArr);
}
