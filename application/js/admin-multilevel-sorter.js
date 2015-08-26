function multilevelSorter(optionArr){
	this.properties = {
		siteUrl: '',
		itemsBlockID: 'menu_items',
		urlSaveSort: '',
		urlDeleteItem: '',
		urlActivateItem: '',
		urlDeactivateItem: '',
		onActionUpdate: false,
		reloadTimeout: 600,
		onStart: function(){},
		onStop: function(){},
		onUpdate: function(){}
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.set_sortable();
		_self.update_closers();
	}

	this.set_sortable = function(){
		$(".sort").sortable({
			connectWith: '.connected',
			items: 'li',
			scroll: true,
			forcePlaceholderSize: true,
			placeholder: 'limiter',
			revert: true,
			distance: 20,
			start: function(event, ui){
                _self.properties.onStart(event, ui);
            },
			stop: function(event, ui) { 
				_self.update_closers();
                _self.properties.onStop(event, ui);
			},
			update: function(event, ui) { 
				if(_self.properties.onActionUpdate){
					_self.update_sorting();
				}
				_self.properties.onUpdate(event, ui);
			}
		});
		
		$(".closer").bind('click', function(){
			var id = $(this).attr("id");
			$("#"+id+"ul").slideToggle();

			$("#"+id).toggleClass('collapse').toggleClass('expand');
		});
	}


	this.update_closers = function(){
		$(".closer").each(function(){
			var id = $(this).attr("id");
			if($("#"+id+"ul li").length > 0 && !$(this).hasClass('visible')){
				$(this).addClass('visible');
			}
			if($("#"+id+"ul li").length <= 0 && $(this).hasClass('visible')){
				$(this).removeClass('visible');
			}
		});
	}

	this.update_sorting = function(){
		var data = new Object;
		$('ul.sort').each(function(){
			var id = $(this).attr("id");
			var name = $(this).attr("name");
			if($("#"+id+" > li").length > 0){
				data[name] = new Object;
				$("#"+id+" > li").each(function(i){
					data[name][$(this).attr('id')] = i+1;
				});
			}
		});
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlSaveSort, 
			type: 'POST',
			data: ({sorter: data}), 
			cache: false,
			success: function(data){
				if(data){
					var result = JSON.parse(data);
					error_object=new Errors;
					if(result['errors']){
						error_object.show_error_block(result['errors'], 'error');
					}else if(result['success']){
						error_object.show_error_block(result['success'], 'success');
					}
				}
			}
		});
	}

	this.deleteItem = function(itemId){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlDeleteItem + itemId, 
			type: 'GET',
			cache: false,
			success: function(data){
				$('#item_'+itemId).remove();
			}
		});
	}

	this.activateItem = function(itemId){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlActivateItem + itemId, 
			type: 'GET',
			cache: false,
			success: function(data){
				$('#active_'+itemId).show();
				$('#deactive_'+itemId).hide();
			}
		});
	}

	this.deactivateItem = function(itemId){
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlDeactivateItem + itemId, 
			type: 'GET',
			cache: false,
			success: function(data){
				$('#active_'+itemId).hide();
				$('#deactive_'+itemId).show();
			}
		});
	}

	_self.Init(optionArr);
}