function avatar(optionArr){
	this.properties = {
		site_url: '',
		load_avatar_url: 'users/ajax_load_avatar/',
		photo_id: 'user_photo',
		id_user: 0,
		recrop_url: 'users/ajax_recrop_avatar/',
		recrop_container_id: 'photo_source_recrop_box',
		recrop_img_id: 'photo_source_recrop',
		photo_sizes_id: 'photo_sizes',
		image_content_id: 'image_content_avatar',
		menu_container_id: 'media_menu',
		recropMenuContainer: '#recrop_menu',
		image_css_size: {width: 700, height: 500},
		selections: [],
		section: '',
		window_obj: new loadingContent({loadBlockWidth: '800px', closeBtnClass: 'w', loadBlockTopType: 'top', loadBlockTopPoint: 20, blockBody: true, showAfterImagesLoad: false})
	};

	var _p = {},
		xhr_recrop = null;
	this.imageareaselect = null;
	this.selections = [];
	this.index = 0;
	this.img = new Image();
	this.image_css_size = {width:0, height: 0};
	var _self = this;


	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_p.avatar_obj = $('#'+_self.properties.photo_id);
		_p.avatar_obj.off('click', 'img').on('click', 'img', function(){
			_self.load_avatar();
		});

		return this;
	};

	this.uninit = function(){
		_self.uninit_imageareaselect();
		_p.avatar_obj.off();
		return this;
	};

	this.load_avatar = function(){
		$.ajax({
			url: _self.properties.site_url + _self.properties.load_avatar_url,
			type: 'POST',
			data: {id_user: _self.properties.id_user},
			dataType: 'json',
			cache: false,
			success: function(resp){
				if(resp.status){
					_self.properties.window_obj.show_load_block(resp.data.html);
					_self.selections = [];
					_self.properties.selections = resp.data.selections;
					_self.add_selections(_self.properties.selections);
				}else if(resp.errors.length){
					error_object.show_error_block(resp.errors, 'error');
				}
			}
		});
	};

	this.add_selection = function(prefix, x1, y1, width, height){
		_self.selections.push({prefix: prefix, x1: x1, y1: y1, width: width, height: height, orig_width: width, orig_height: height});
		return this;
	};

	this.add_selections = function(selections){
		for(var i in selections) if(selections.hasOwnProperty(i)){
			_self.add_selection(i, 0, 0, parseInt(selections[i].width), parseInt(selections[i].height));
		}
	};

	this.recrop = function(){
		if(xhr_recrop && xhr_recrop.state() == 'pending'){
			return xhr_recrop;
		}
		xhr_recrop = $.ajax({
			url: _self.properties.site_url + _self.properties.recrop_url,
			type: 'POST',
			data: _self.selections[_self.index],
			dataType: 'json',
			cache: false,
			success: function(resp){
				if(resp.status){
					if(resp.data.img_url){
						$('img[src^="'+resp.data.img_url+'"]').attr('src', resp.data.img_url+'?'+new Date().getTime());
					}
				}else if(resp.errors.length){
					error_object.show_error_block(resp.errors, 'error');
				}
				if(resp.msg.length){
					error_object.show_error_block(resp.msg, 'success');
				}
			}
		});
		return xhr_recrop;
	};

	this.init_imageareaselect = function(){
		_p.img_obj = $('#'+_self.properties.recrop_img_id);
		_p.photo_sizes_obj = $('#'+_self.properties.photo_sizes_id);
		_p.image_content_obj = $('#'+_self.properties.image_content_id);

		_p.image_content_obj.off('click', '#'+_self.properties.menu_container_id+' [data-section]').on('click', '#'+_self.properties.menu_container_id+' [data-section]', function(){
			var section = $(this).data('section');
			if(section === 'recrop' && _self.selections.length){
				_p.image_content_obj.find('[data-area="recrop"]').show();
				_self.index = 0;

				_p.photo_sizes_obj.html('');
				for(var i in _self.selections) if(_self.selections.hasOwnProperty(i)){
					_p.photo_sizes_obj.append('<li><span data-index="'+i+'">'+_self.selections[i].orig_width+'x'+_self.selections[i].orig_height+'</span></li>');
				}
				_p.photo_sizes_obj.find('li').removeClass('active').eq(_self.index).addClass('active');

				_p.photo_sizes_obj.off('click', 'li span').on('click', 'li span', function(){
					var size_index = $(this).data('index');
					if(size_index >= _self.selections.length || size_index == _self.index) return false;
					_self.index = size_index;
					_self.change_selection();
					_p.photo_sizes_obj.find('li').removeClass('active');
					$(this).parent().addClass('active');
					return false;
				});

				$('#'+_self.properties.recrop_container_id).off('recrop:do').on('recrop:do', function(){
					_self.recrop();
				});

				if($.isEmptyObject(_self.selections)) return;

				_self.img.src = _p.img_obj.attr('src');
				_self.img.onload = function() {
					if(_self.img.width){
						_p.image_ready();
					}else{
						_p.img_obj.off('load').on('load', function(){
							_p.image_ready();
						});
					}
				};
			}else{
				_p.image_content_obj.find('[data-area="recrop"]').hide();
				if(_self.imageareaselect){
					_self.imageareaselect.setOptions({hide: true});
				}
			}
			$(this).parents('ul').find('li').removeClass('active');
			$(this).parent().addClass('active');
		});

		$('#'+_self.properties.menu_container_id).find('[data-section="'+_self.properties.section+'"]').parent().addClass('active');
		$('#'+_self.properties.menu_container_id+' .active span').click();

		return this;
	};

	_p.image_ready = function(attempt){
		attempt = attempt || 0;
		while(!(_p.img_obj.width() && _p.img_obj.height()) && attempt < 60){
			setTimeout(function(){_p.image_ready(++attempt);}, 50);
			return this;
		}
		_self.image_css_size = {width: _p.img_obj.width(), height: _p.img_obj.height()};
		_self.selections = [];
		_self.add_selections(_self.properties.selections);
		_self.recalc_selection();
		_self.imageareaselect = _p.img_obj.imgAreaSelect({
			handles: true,
			persistent: true,
			aspectRatio: _self.selections[_self.index].width + ':' + _self.selections[_self.index].height,
			minWidth: _self.selections[_self.index].width,
			minHeight: _self.selections[_self.index].height,
			imageWidth: _self.img.width,
			imageHeight: _self.img.height,
			instance: true,
			parent: '#'+_self.properties.recrop_container_id,
			x1: _self.selections[_self.index].x1,
			y1: _self.selections[_self.index].y1,
			x2: _self.selections[_self.index].x1 + _self.selections[_self.index].width,
			y2: _self.selections[_self.index].y1 + _self.selections[_self.index].height,
			zIndex: 2,
			onSelectStart: function(image, selection){
				_p.hide_recrop_btn(this);
			},
			onSelectEnd: function(image, selection){
				if(!selection.width || !selection.height) return;
				_p.show_recrop_btn(this);
				_self.selections[_self.index].x1 = Math.min(selection.x1, selection.x2);
				_self.selections[_self.index].y1 = Math.min(selection.y1, selection.y2);
				_self.selections[_self.index].width = selection.width;
				_self.selections[_self.index].height = selection.height;
			},
			onSelectChange: function(image, selection){
				if(!selection.width || !selection.height) return;
				_p.hide_recrop_btn(this);
				_self.selections[_self.index].x1 = Math.min(selection.x1, selection.x2);
				_self.selections[_self.index].y1 = Math.min(selection.y1, selection.y2);
				_self.selections[_self.index].width = selection.width;
				_self.selections[_self.index].height = selection.height;
			},
			onInit: function(image, selection){
				_p.show_recrop_btn(this);
			}
		});
	};

	_p.show_recrop_btn = function(imageareaselect){
		var selection_obj = $(imageareaselect.parent).find('.'+imageareaselect.classPrefix+'-selection');
		//imageAreaSelect плагин перехватывает и переопределяет события мышки, поэтому задаем onmousedown с собственным триггером
		var btn = $('<div class="selection-recrop-btn" onmouseup="$(\'#'+_self.properties.recrop_container_id+'\').trigger(\'recrop:do\');"></div>');
		selection_obj.html(btn);
	};

	_p.hide_recrop_btn = function(imageareaselect){
		$(imageareaselect.parent).find('.'+imageareaselect.classPrefix+'-selection').html('');
	};

	this.uninit_imageareaselect = function(){
		if(_p.img_obj)				_p.img_obj.off();
		if(_p.photo_sizes_obj)		_p.photo_sizes_obj.off();
		if(_p.image_content_obj)	_p.image_content_obj.off();
		$('#'+_self.properties.recrop_container_id).off();
		_self.imageareaselect = null;
		_self.selections = [];
		_self.index = 0;
		return this;
	};

	this.change_selection = function(){
		var selection = _self.selections[_self.index];
		_self.imageareaselect.setOptions({
			aspectRatio: selection.width + ':' + selection.height,
			minWidth: selection.width,
			minHeight: selection.height,
			x1: selection.x1,
			y1: selection.y1,
			x2: selection.x1 + selection.width,
			y2: selection.y1 + selection.height
		});
		_self.imageareaselect.update(false);
		return this;
	};

	this.recalc_selection = function(){
		var height_ratio = _self.img.height / _self.image_css_size.height;
		var width_ratio = _self.img.width / _self.image_css_size.width;
		for(var i in _self.selections) if(_self.selections.hasOwnProperty(i)){
			if(_self.img.width < _self.selections[i].width || _self.img.height < _self.selections[i].height){
				var ratio;
				if(_self.img.width < _self.selections[i].width){
					ratio = _self.img.width / _self.selections[i].width;
					_self.selections[i].width *= ratio;
					_self.selections[i].height *= ratio;
				}
				if(_self.img.height < _self.selections[i].height){
					ratio = _self.img.height / _self.selections[i].height;
					_self.selections[i].width *= ratio;
					_self.selections[i].height *= ratio;
				}
			}else{
				var width_recalc = _self.selections[i].width * width_ratio;
				var height_recalc = _self.selections[i].height * height_ratio;
				if(width_recalc >= _self.selections[i].width && width_recalc < _self.img.width && height_recalc >= _self.selections[i].height && height_recalc < _self.img.height){
					_self.selections[i].width = width_recalc;
					_self.selections[i].height = height_recalc;
				}
				var x1 = Math.round(_self.img.width / 2 - _self.selections[i].width / 2);
				if(x1 < 0){
					x1 = 0;
				}
				var y1 = Math.round(_self.img.height / 2 - _self.selections[i].height / 2);
				if(y1 < 0){
					y1 = 0;
				}
				_self.selections[i].x1 = x1;
				_self.selections[i].y1 = y1;
			}
		}
		return this;
	};

	_self.Init(optionArr);

	return this;
}