function sortLocations(optionArr){
	this.properties = {
		siteUrl: '',
		urlSaveSort: '',
	}

	var _self = this;

	this.errors = {
	}

	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.moveCountry();
		_self.change();
	}

	this.moveCountry= function() {
		$('#moveToSortList').bind('click', function(){
			$('#clsr0ul_sort option:selected').appendTo('#clsr0ul');
			_self.sortCountries();
			_self.saveSorter();
		});
		$('#moveToDefault').bind('click', function(){
			$('#clsr0ul option:selected').appendTo('#clsr0ul_sort');
			_self.saveSorter();
		});
	}
	
	this.change = function() {
		$('#moveUp').bind('click', function(){
			_self.changePriority("up");
		});
		$('#moveDown').bind('click', function(){
			_self.changePriority("down");
		});
	}
	
	this.sortCountries= function(){	
		$('#clsr0ul').each( function(){
			$(this).html( $(this).find('option').sort(function(a, b) {
				return a.text == b.text ? 0 : a.text < b.text ? -1 : 1
			}));
		});
	}
	
	this.changePriority= function(direction) {		
		var len = $('#clsr0ul_sort option').size();
		var list_text = new Array();
		var list_id = new Array();
		var selected_indexes = new Array();
		var j = 0;
		
		$('#clsr0ul_sort').find('option').each(function (i) {
			list_text[i] = $(this).text();
			list_id[i] = $(this).prop("id");
			if($(this).is(":selected")) {
				selected_indexes[j]=i;
				$(this).prop('selected', false);
				j++;
			}
		});
		
		if (direction == "up") {
			for(var i = 0; i < selected_indexes.length; i++) {
				if(selected_indexes[i] > 0) {
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] - 1)+')').text(list_text[selected_indexes[i]]);
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] - 1)+')').prop("id", list_id[selected_indexes[i]]);
					$('#clsr0ul_sort option:eq('+selected_indexes[i]+')').text(list_text[selected_indexes[i] - 1]);
					$('#clsr0ul_sort option:eq('+selected_indexes[i]+')').prop("id", list_id[selected_indexes[i] - 1]);
					
					list_text[selected_indexes[i] - 1] = $('#clsr0ul_sort option:eq('+(selected_indexes[i] - 1)+')').val();
					list_id[selected_indexes[i] - 1] = $('#clsr0ul_sort option:eq('+(selected_indexes[i] - 1)+')').prop("id");
					list_text[selected_indexes[i]] = $('#clsr0ul_sort option:eq('+(selected_indexes[i])+')').val();
					list_id[selected_indexes[i]] = $('#clsr0ul_sort option:eq('+(selected_indexes[i])+')').prop("id");
					
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] - 1)+')').prop('selected', true);
				}
			}
		} else if (direction == "down") {
			for(var i = selected_indexes.length; i >= 0; i--) {
				if(selected_indexes[i] < len-1) {
					$('#clsr0ul_sort option:eq('+selected_indexes[i]+')').text(list_text[selected_indexes[i] + 1]);
					$('#clsr0ul_sort option:eq('+selected_indexes[i]+')').prop("id", list_id[selected_indexes[i] + 1]);
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] + 1)+')').text(list_text[selected_indexes[i]]);
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] + 1)+')').prop("id", list_id[selected_indexes[i]]);
					
					list_text[selected_indexes[i] + 1] = $('#clsr0ul_sort option:eq('+(selected_indexes[i] + 1)+')').val();
					list_id[selected_indexes[i] + 1] = $('#clsr0ul_sort option:eq('+(selected_indexes[i] + 1)+')').prop("id");
					list_text[selected_indexes[i]] = $('#clsr0ul_sort option:eq('+(selected_indexes[i])+')').val();
					list_id[selected_indexes[i]] = $('#clsr0ul_sort option:eq('+(selected_indexes[i])+')').prop("id");
					
					$('#clsr0ul_sort option:eq('+(selected_indexes[i] + 1)+')').prop('selected', true);
				}
			}
		}
		
		_self.saveSorter();
	}
	
	this.saveSorter= function() {
		var data = new Object;
		$('#clsr0ul_sort').each(function(){
			var id = $(this).prop("id");
			var name = $(this).prop("name");
			if($("#"+id+" > option").length > 0){
				data[name] = new Object;
				$("#"+id+" > option").each(function(i){
					if($(this).prop('id') !== undefined) {
						data[name][$(this).prop('id')] = i+1;
					}
				});
			}
		});

		result = JSON.stringify(data);
		
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.urlSaveSort, 
			type: 'POST',
			data: ({sorter: result}), 
			cache: false
		});
	}
	
	_self.Init(optionArr);
}