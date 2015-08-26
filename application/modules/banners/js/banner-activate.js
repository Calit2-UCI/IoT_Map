function BannerActivate(defOptions){
	this.properties = {
		mainID: 'positions',
		data:{}
	}

	var _self = this;

	this.init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.initialize_positions();
		_self.refresh_sum();
	}

	this.refresh_sum = function(){
		var final_sum = 0;

		$('#'+_self.properties.mainID+' input').each(function(){
			var used_id = parseInt($(this).attr('id').substring(9));
			var val = parseInt($(this).val());
			if(val < 0) val = 0;
			if(val > _self.properties.data[used_id].positions) val = _self.properties.data[used_id].positions;
			if(isNaN(val)) val = '';
			$(this).val(val);
			final_sum += val*parseFloat(_self.properties.data[used_id].price);
		});
		$('#final_price').text(Math.round(100*final_sum)/100);
	}

	this.initialize_positions = function(){
		$('#'+_self.properties.mainID+' input').each(function(){
			$(this).bind('keyup', function(){
				_self.refresh_sum();
			});
			var used_id = parseInt($(this).attr('id').substring(9));
			_self.properties.data[used_id] = {};
			_self.properties.data[used_id].price = parseFloat($('#price_'+used_id).attr('float'));
			_self.properties.data[used_id].positions = parseInt($('#free_pos_'+used_id).attr('int'));
		});

	}

	_self.init(defOptions);
}
