function Wall(){
	/*if(!Wall.instance){
		Wall.instance = this;
	}else{
		return Wall.instance;
	}*/

	this.properties = {
		id: 'wall',
		place: 'myprofile',
		id_wall: 0,
		id_user: 0,
		url_load: '/wall_events/ajax_get_events/all/',
		url_load_new: '/wall_events/ajax_get_events/new/',
		url_post: '/wall_events/ajax_post/',
		url_delete: '/wall_events/ajax_post_delete/',
		view_button_title: 'View more...',
		placeholder: '',
		onInit: function(){},
		load_on_scroll: 0,
		all_loaded: 0,
		max_id: 0,
		min_id: 0,
		uploaded: false,
		gallery: null,
		next_gallery_image: null,
		prev_gallery_image: null,
		images: [],
		image_lng: 'Image'
	}

	var loading_status = 0;
	var deleting_status = 0;
	var posting_status = 0;
	var window_on_scroll;

	var _self = this;


	this.init = function(options){
		$.extend(_self.properties, options);
		$('#'+_self.properties.id).html('').append('<div id="'+_self.properties.id+'_events"></div><div id="'+_self.properties.id+'_btn"></div>');
		$('#'+_self.properties.id+'_btn')
			.append('<div class="show-more-btn center"><input type="button" value="'+_self.properties.view_button_title+'" /></div>')
			.on('click', 'input[type="button"]', function(){_self.loadEvents();})
			.find('input[type="button"]').hide();

		if(_self.properties.load_on_scroll){
			setTimeout(function(){_self.setLoadOnScroll(1, 1);}, 500);
		}

		_self.properties.gallery = new loadingContent({
			loadBlockID: 'wall_gallery',
			loadBlockWidth: '980px',
			loadBlockTopType: 'top',
			loadBlockTopPoint: 10,
			linkerObjID: null,
			blockBody: true,
			showAfterImagesLoad: true,
			closeBtnClass: 'w'
		});

		$('#'+_self.properties.id).on('click', '.wall-gallery img', function(){
			_self.initGallery(this);
		});
		_self.properties.onInit();
		return this;
	};

	this.bind_delete = function() {
		$('.delete_wall_event').off('click').on('click', function() {
			var id = $(this).data('id');
			alerts.show({
				text: $(this).data('message'),
				type: 'confirm',
				ok_callback: function(){
					_self.deletePost(id);
				}
			});
		});
	};

	this.uninit = function(){
		$('#'+_self.properties.id+'_btn').off('click', 'input[type="button"]');
		$(window).unbind('scroll', window_on_scroll);
		return true;
	}

	this.nextGalleryImage = function(){
		_self.setGalleryImage(_self.properties.next_gallery_image);
	}

	this.prevGalleryImage = function(){
		_self.setGalleryImage(_self.properties.prev_gallery_image);
	}

	this.initGallery = function(event_object){
		if(event_object){
			var gallery = $(event_object).parents('.wall-gallery').attr('gallery');
			_self.properties.images = $('.wall-gallery[gallery="'+gallery+'"]').find('img');
			if(_self.properties.images.length > 1){
				_self.properties.gallery.create_right(function(){_self.nextGalleryImage(); _self.properties.gallery.reposition_load_block();});
				_self.properties.gallery.create_left(function(){_self.prevGalleryImage(); _self.properties.gallery.reposition_load_block();});
			}
			_self.setGalleryImage(event_object);
		}
		return this;
	}

	this.setGalleryImage = function(event_object){
		if(_self.properties.images.length){
			var next = _self.properties.images[0];
			var prev = _self.properties.images[_self.properties.images.length-1];
			var header_count = '1 / ' + (_self.properties.images.length);
			for(var i in _self.properties.images){
				if(_self.properties.images[i] == event_object){
					if(typeof _self.properties.images[+i+1] !== 'undefined'){
						next = _self.properties.images[+i+1];
					}
					if(typeof _self.properties.images[+i-1] !== 'undefined'){
						prev = _self.properties.images[+i-1];
					}
					header_count = (+i+1) + ' / ' + (_self.properties.images.length);
				}
			}
			_self.properties.next_gallery_image = next;
			_self.properties.prev_gallery_image = prev;
			var prev_html = '<img class="hide" src="'+$(prev).attr('gallery-src')+'" />';
			var next_html = '<img class="hide" src="'+$(next).attr('gallery-src')+'" />';
			var cur_html = '<img src="'+$(event_object).attr('gallery-src')+'" />';
			var html = '<div class="gallery-window load_content"><h1>'+_self.properties.image_lng+' '+header_count+'</h1>' + prev_html + next_html + cur_html + '</div>';
			_self.properties.gallery.show_load_block(html, true);
		}
	}

	this.setLoadOnScroll = function(load_on_scroll, is_init){
		is_init = is_init || 0;
		window_on_scroll = function(){
			var offset = $('#'+_self.properties.id+'_btn').offset();
			var window_height = window.innerHeight ? window.innerHeight : $(window).height();
			if(offset && $(window).scrollTop() >= offset.top - window_height + 100){
				_self.loadEvents();
			}
		}
		if(load_on_scroll && (!_self.properties.load_on_scroll || is_init)){
			_self.properties.load_on_scroll = 1;
			/*if($(window).scrollTop() > $('#'+_self.properties.id).offset().top){
				$(window).scrollTop($('#'+_self.properties.id).offset().top);
			}*/
			//$(window).scrollTop(0);
			$(window).bind('scroll', window_on_scroll);
		}else{
			_self.properties.load_on_scroll = 0;
			$(window).unbind('scroll', window_on_scroll);
		}
		return this;
	}


	this.loadEvents = function(type, onsuccess){
		type = type || 'all';
		onsuccess = onsuccess || null;
		if((_self.properties.all_loaded && type != 'new') || loading_status){
			return this;
		}
		loading_status = 1;
		$('#'+_self.properties.id+'_btn').find('input[type="button"]').attr('disabled', 'disabled');

		var data = {
			min_id: _self.properties.min_id,
			max_id: _self.properties.max_id,
			place: _self.properties.place,
			id_user: _self.properties.id_user,
			id_wall: _self.properties.id_wall
		};
		var url = (type == 'new') ? _self.properties.url_load_new : _self.properties.url_load;

		$.ajax({
			type: 'POST',
			url: url,
			data: data,
			success: function(resp, textStatus, jqXHR){
				_self.properties.all_loaded = resp.all_loaded;
				if(resp.min_id < _self.properties.min_id || _self.properties.min_id == 0){
					_self.properties.min_id = resp.min_id;
				}
				if(resp.max_id > _self.properties.max_id || _self.properties.max_id == 0){
					_self.properties.max_id = resp.max_id;
				}

				if(resp.html && resp.html != ''){
					if(type == 'new'){
						$('#'+_self.properties.id+'_events').prepend($(resp.html));
					}else{
						$('#'+_self.properties.id+'_events').append($(resp.html));
					}
				}
				if(type != 'new'){
					if(resp.all_loaded){
						$('#'+_self.properties.id+'_btn').find('input[type="button"]').hide();
					}else{
						$('#'+_self.properties.id+'_btn').find('input[type="button"]').removeAttr('disabled').show();
					}
				}else{
					$('#'+_self.properties.id+'_btn').find('input[type="button"]').removeAttr('disabled');
				}
				loading_status = 0;
				if(typeof onsuccess == 'function'){
					onsuccess();
				}
				_self.bind_delete();
			},
			error: function(jqXHR, textStatus, errorThrown){
				loading_status = 0;
				if(typeof(console)!=='undefined'){
					console.error(errorThrown);
				}
				$('#'+_self.properties.id+'_btn').find('input[type="button"]').removeAttr('disabled');
				if(type != 'new'){
					$('#'+_self.properties.id+'_btn').find('input[type="button"]').show();
				}
			},
			dataType: 'json'
		});
		return this;
	}

	var _get_sort_array = function(object, assoc, keys_type){
		assoc = assoc || false;
		keys_type = keys_type || 'int';
		var key;
		var arr = [];
		var return_arr = [];
		for(var i in object){
			if(object.hasOwnProperty(i)){
				key = (keys_type === 'int') ? parseInt(i) : i;
				arr[key] = object[i];
			}
		}
		if(!assoc){
			arr.sort();
			for(var i in arr){
				return_arr.push(arr[i]);
			}
		}else{
			return_arr = arr;
		}
		return return_arr;
	}

	this.newPost = function(onsuccess){
		onsuccess = onsuccess || null;
		var text = _self.trimStr($('#wall_post_text').val());
		var embed_code = _self.trimStr($('#wall_embed_code').val());
		if(posting_status || !(text || embed_code)){
			return this;
		}
		posting_status = 1;

		$.ajax({
			type: 'POST',
			url: _self.properties.url_post,
			data: {post: {
				text: text,
				embed_code: embed_code,
				place: _self.properties.place,
				min_id: _self.properties.min_id,
				max_id: _self.properties.max_id,
				id_user: _self.properties.id_user,
				id_wall: _self.properties.id_wall
			}},
			success: function(resp, textStatus, jqXHR){
				if(resp.status == 1){
					$('#wall_post_text').val('');
					$('#wall_embed_code').val('');
					if(resp.joined_id){
						$('#wall_event_'+resp.joined_id).remove();
					}
				}else if(resp.error){

				}
				if(resp.msg){
					error_object.show_error_block(resp.msg, 'success');
				}
				if(typeof onsuccess == 'function'){
					onsuccess();
				}

				posting_status = 0;
			},
			error: function(jqXHR, textStatus, errorThrown){
				posting_status = 0;
				if(typeof(console)!=='undefined'){
					console.error(errorThrown);
				}
			},
			dataType: 'json'
		});
		return this;
	}


	this.deletePost = function(id_post){
		id_post = id_post || 0;
		if(deleting_status || !id_post){
			return this;
		}
		deleting_status = 1;

		$.ajax({
			type: 'POST',
			url: _self.properties.url_delete,
			data: {event_id: id_post},
			success: function(resp, textStatus, jqXHR){
				if(resp.status == 1){
					$('#wall_event_'+id_post).remove();
				}
				if(resp.msg){
					error_object.show_error_block(resp.msg, 'success');
				}
				deleting_status = 0;
			},
			error: function(jqXHR, textStatus, errorThrown){
				deleting_status = 0;
				if(typeof(console)!=='undefined'){
					console.error(errorThrown);
				}
			},
			dataType: 'json'
		});
		return this;
	}


	this.trimStr = function(s) {
		s = s.replace( /^\s+/g, '');
		return s.replace( /\s+$/g, '');
	}

	return this;
};