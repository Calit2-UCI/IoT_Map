function editMedia(optionArr) {
	this.properties = {
		gallery_name: 'mediagallery',
		siteUrl: '/',
		mediaId: '',
		getMediaContentUrl: 'media/ajax_get_media_content/',
		getMediaSectionUrl: 'media/ajax_get_section_content/',
		section: 'view',
		contentDiv: '#image_content',
		menuContainer: '#media_menu',
		recropMenuContainer: '#recrop_menu',
		sectionsContainer: '#media_sections',
		mediaPreloaderContainer: '#media_preloader',
		saveDescriptionUrl: 'media/ajax_save_description/',
		savePermissionsUrl: 'media/ajax_save_permissions/',
		addMediaInAlbumUrl: 'media/ajax_add_media_in_album/',
		deleteMediaFromAlbumUrl: 'media/ajax_delete_media_from_album/',
		error_in_adding_to_favorites: 'error_in_adding_to_favorites',
		success_add_to_favorites: 'success_add_to_favorites',
		recrop_container_id: 'photo_source_recrop_box',
		recrop_img_id: 'photo_source_recrop',
		photo_sizes_id: 'photo_sizes',
		recrop_btn_id: 'recrop_btn',
		recropUrl: 'media/ajax_recrop/',
		selections: [],
		rotate_left_id: 'photo_rotate_left',
		rotate_right_id: 'photo_rotate_right',
		rotateUrl: 'media/ajax_rotate/',
		nextMediaButton: '#next_media',
		previousMediaButton: '#prev_media',
		nextMedia: null,
		previousMedia: null,
		albumId: 0,
		galleryContentParam: 'all',
		mediaPosition: '#media_position',
		order: 'date_add',
		direction: 'desc',
		post_data: {},
		precache_images: true,
		rand_param: null,
		rand_param_change: false,
		success_request: function (message) {
		},
		fail_request: function (message) {
		},
		loaded_sections: {comments: true, access: true, albums: false, recrop: false},
		errorObj: new Errors
	};

	this.imageareaselect = null;
	this.image_css_size = {width: 700, height: 500};
	this.selections = [];
	this.index = 0;
	this.img = new Image();

	var xhr_description = null,
			xhr_load_section = null,
			xhr_load_content = null,
			xhr_recrop = null,
			_p = {},
			_self = this;

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		_self.add_selections(_self.properties.selections);
		_self.init_menu();
		_self.init_content();
		_self.init_buttons();
		_self.init_albums();
	};

	this.uninit = function () {
		$(_self.properties.contentDiv).off();
		$('#' + _self.properties.photo_sizes_id).off();
		$('#' + _self.properties.recrop_container_id).off();
		$('#' + _self.properties.recrop_img_id).off('load');
		_self.imageareaselect = null;
	};

	this.init_albums = function () {
		$(_self.properties.contentDiv).off('click', '#common_albums .album-item.active:not(.disabled)').on('click', '#common_albums .album-item.active', function () {
			var id = $(this).attr('id');
			if (id) {
				var str = '#common_album_';
				id = id.substr(str.length - 1);
				_self.delete_photo_from_album(id, str + id);
			}
		}).off('click', '#common_albums .album-item:not(.active)').on('click', '#common_albums .album-item:not(.active):not(.disabled)', function () {
			var id = $(this).attr('id');
			if (id) {
				var str = '#common_album_';
				id = id.substr(str.length - 1);
				_self.add_photo_in_album(id, str + id);
			}
		}).off('click', '#user_albums .album-item.active').on('click', '#user_albums .album-item.active', function () {
			var str = '#user_album_';
			var id = $(this).attr('id').substr(str.length - 1);
			_self.delete_photo_from_album(id, str + id);
		}).off('click', '#user_albums .album-item:not(.active)').on('click', '#user_albums .album-item:not(.active)', function () {
			var str = '#user_album_';
			var id = $(this).attr('id').substr(str.length - 1);
			_self.add_photo_in_album(id, str + id);
		}).off('click', '.to_favorites.active').on('click', '.to_favorites.active', function () {
			_self.properties.errorObj.show_error_block(_self.properties.error_in_adding_to_favorites, 'error');
		}).off('click', '.to_favorites:not(.active)').on('click', '.to_favorites:not(.active)', function () {
			var id = $(this).data('id');
			var str = '.to_favorites';
			_self.add_photo_to_favorites(id, str);
		});
	};

	this.init_buttons = function () {
		$(_self.properties.contentDiv).off('click', '#save_permissions').on('click', '#save_permissions', function () {
			_self.save_permissions();
		}).off('click', _self.properties.nextMediaButton).on('click', _self.properties.nextMediaButton, function () {
			_self.display_next_media();
		}).off('click', _self.properties.previousMediaButton).on('click', _self.properties.previousMediaButton, function () {
			_self.display_previous_media();
		}).off('focus', '[contenteditable]').on('focus', '[contenteditable]', function () {
			$(this).parent().find('i').removeClass('active');
		}).off('click', '.contenteditable i.active').on('click', '.contenteditable i.active', function () {
			$(this).parent().find('[contenteditable]').focus();
			$(this).removeClass('active');
		}).off('blur', '[contenteditable]').on('blur', '[contenteditable]', function () {
			var btn = $(this).parent().find('i');
			var description = $(this).html().replace(/<br\s*\/?>/mg, "\n");
			_self.save_description(description, btn);
		});
	};

	this.init_menu = function () {
		$(_self.properties.contentDiv).off('click', _self.properties.menuContainer + ' [data-section]').on('click', _self.properties.menuContainer + ' [data-section]', function () {
			var section = $(this).data('section');
			if ($(this).parent().hasClass('active')) {
				return false;
			}
			_self.properties.section = section;
			if (section === 'recrop' && _self.selections.length) {
				$(_self.properties.contentDiv).find('[data-area="view"]').hide();
				$(_self.properties.contentDiv).find('[data-area="recrop"]').show();
				_self.index = 0;
				_self.init_imageareaselect();

				$('#' + _self.properties.rotate_left_id).bind('click', function () {
					_self.rotate(90);
					return false;
				});

				$('#' + _self.properties.rotate_right_id).bind('click', function () {
					_self.rotate(-90);
					return false;
				});

			} else {
				$(_self.properties.contentDiv).find(_self.properties.menuContainer).find('li').removeClass('active');
				$(this).parent().addClass('active');
				$(_self.properties.contentDiv).find('[data-area="recrop"]').hide();
				$(_self.properties.contentDiv).find('[data-area="view"]').show();
				_self.show_section(section);
			}
			return false;

		});
		$(_self.properties.menuContainer).find('[data-section="' + _self.properties.section + '"]').parent().addClass('active');

		$(_self.properties.contentDiv).off('click', _self.properties.recropMenuContainer + ' [data-section]').on('click', _self.properties.recropMenuContainer + ' [data-section]', function () {
			var section = $(this).data('section');
			if (section === 'view') {
				$(_self.properties.contentDiv).find('[data-area="recrop"]').hide();
				$(_self.properties.contentDiv).find('[data-area="view"]').show();
			}
		});
	};

	this.show_section = function (section) {
		var dfd = $.Deferred();
		if (_self.properties.loaded_sections[section] === false) {
			var load_deferred = _self.load_section(section);
			load_deferred.then(dfd.resolve(section));
		} else {
			dfd.resolve(section);
		}
		dfd.done(function (section) {
			var dfd_switch = $.Deferred();
			$(_self.properties.sectionsContainer).find('[data-section]').stop(true).fadeOut(200, function () {
				dfd_switch.resolve();
			}).filter('[data-section="' + section + '"]').delay(150).fadeIn(200);
			dfd_switch.done(function () {
				/*var scroll = $(_self.properties.sectionsContainer).position().top;
				 if(typeof window[_self.properties.gallery_name] === 'object'){
				 $('#'+window[_self.properties.gallery_name].properties.windowObj.properties.loadBlockBgID).animate({scrollTop: scroll}, 500);
				 }*/
			});
		});
	};

	this.add_photo_to_favorites = function (album_id, selector) {
		var data = window[_self.properties.gallery_name].get_post_data();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.addMediaInAlbumUrl + _self.properties.mediaId + '/' + album_id,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (resp) {
				if (resp.status) {
					$(selector).addClass('active');
					$(selector).find('.status-icon').removeClass('fa-star-empty').addClass('fa-star').addClass('g');
					_self.properties.errorObj.show_error_block(_self.properties.success_add_to_favorites, 'success');
				} else {
					_self.properties.errorObj.show_error_block(resp.errors, 'error');
				}
			}
		});
	};

	this.delete_photo_from_favorites = function (album_id, selector) {
		var data = window[_self.properties.gallery_name].get_post_data();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteMediaFromAlbumUrl + _self.properties.mediaId + '/' + album_id,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (data) {
				if (data.status) {
					$(selector).removeClass('active');
					$(selector).find('.status-icon').removeClass('fa-star').addClass('fa-star-empty');
				} else {
					_self.properties.errorObj.show_error_block(data.errors, 'error');
				}
			}
		});
	};

	this.add_photo_in_album = function (album_id, selector) {
		var data = window[_self.properties.gallery_name].get_post_data();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.addMediaInAlbumUrl + _self.properties.mediaId + '/' + album_id,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (resp) {
				if (resp.status) {
					$(selector).addClass('active');
					$(selector).find('.status-icon').removeClass('fa-check-empty').addClass('fa-check');
				} else {
					_self.properties.errorObj.show_error_block(resp.errors, 'error');
				}
			}
		});
	};

	this.delete_photo_from_album = function (album_id, selector) {
		var data = window[_self.properties.gallery_name].get_post_data();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.deleteMediaFromAlbumUrl + _self.properties.mediaId + '/' + album_id,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (data) {
				if (data.status) {
					$(selector).removeClass('active');
					$(selector).find('.status-icon').removeClass('fa-check').addClass('fa-check-empty');
				} else {
					_self.properties.errorObj.show_error_block(data.errors, 'error');
				}
			}
		});
	};

	this.save_description = function (description, btn) {
		if (xhr_description && xhr_description.state() === 'pending') {
			xhr_description.always(_self.save_description(btn));
			return;
		}
		xhr_description = $.ajax({
			url: _self.properties.siteUrl + _self.properties.saveDescriptionUrl + _self.properties.mediaId,
			type: 'POST',
			dataType: "json",
			data: {description: description},
			cache: false,
			success: function (data) {
				if (data.status) {
					btn.addClass('active');
					_self.properties.success_request();
				} else {
					_self.properties.fail_request(data.errors);
				}
			}
		});
	};

	this.save_permissions = function () {
		var permissions = $('#permissions:checked').val();
		var data = {'permissions': permissions};
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.savePermissionsUrl + _self.properties.mediaId,
			type: 'POST',
			dataType: "json",
			data: data,
			cache: false,
			success: function (data) {
				if (data.status) {
					_self.properties.success_request();
				} else {
					_self.properties.fail_request(data.errors);
				}
			}
		});
	};

	this.load_section = function (section) {
		if (xhr_load_section && xhr_load_section.state() === 'pending') {
			return xhr_load_section;
		}
		var data = window[_self.properties.gallery_name].get_post_data();
		data.id = _self.properties.mediaId;
		data.album_id = _self.properties.albumId;
		data.section = section;
		xhr_load_section = $.ajax({
			url: _self.properties.siteUrl + _self.properties.getMediaSectionUrl,
			type: 'POST',
			data: data,
			dataType: 'json',
			cache: false,
			success: function (data) {
				_self.properties.loaded_sections[section] = true;
				$(_self.properties.sectionsContainer).find('[data-section="' + section + '"]').html(data.content);
			}
		});
		return xhr_load_section;
	};

	this.init_content = function (animate) {
		if (xhr_load_content && xhr_load_content.state() === 'pending') {
			return xhr_load_content;
		}
		_self.properties.loaded_sections = {comments: true, access: true, albums: false, recrop: false};
		animate = animate || false;
		var data = window[_self.properties.gallery_name].get_post_data();
		data.rand_param = _self.properties.rand_param !== null ? _self.properties.rand_param : 0;

		if (animate) {
			$(_self.properties.contentDiv).stop().animate({opacity: 0.3}, 200);
		}
		//data.without_position = (_self.properties.nextMedia !== null && _self.properties.nextMedia !== null) ? 1 : 0;
		xhr_load_content = $.ajax({
			url: _self.properties.siteUrl + _self.properties.getMediaContentUrl + _self.properties.mediaId + '/' + _self.properties.galleryContentParam + '/' + _self.properties.albumId,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (resp) {
				var height_old = $(_self.properties.contentDiv).height();
				var new_content = $(resp.content.trim());
				var dfd = $.Deferred();
				if (animate) {
					dfd = _p.animate_load(height_old, new_content);
				} else {
					$(_self.properties.contentDiv).html(resp.content).stop(true).css({opacity: 1});
					dfd.resolve();
				}
				if (resp.position_info) {
					$(_self.properties.mediaPosition).html(resp.position_info.position + ' / ' + resp.position_info.count);
					_self.properties.nextMedia = resp.position_info.next;
					_self.properties.previousMedia = resp.position_info.previous;
					if (_self.properties.precache_images) {
						if (resp.position_info.next_image.image) {
							var next_img = new Image();
							next_img.src = resp.position_info.next_image.image + '?' + _self.properties.rand_param;
							if (resp.position_info.next_image.thumb) {
								var next_img_thumb = new Image();
								next_img_thumb.src = resp.position_info.next_image.thumb + '?' + _self.properties.rand_param;
							}
						}
						if (resp.position_info.previous_image.image) {
							var previous_img = new Image();
							previous_img.src = resp.position_info.previous_image.image + '?' + _self.properties.rand_param;
							if (resp.position_info.previous_image.thumb) {
								var previous_img_thumb = new Image();
								previous_img_thumb.src = resp.position_info.previous_image.thumb + '?' + _self.properties.rand_param;
							}
						}
					}
				}

				if (resp.views_num) {
					$('span[data-gid^="media' + _self.properties.mediaId + '"] .view_num').html(resp.views_num);
				}

				dfd.always(function () {
					if (_self.properties.nextMedia == 0) {
						$(_self.properties.nextMediaButton).hide();
					}
					if (_self.properties.previousMedia == 0) {
						$(_self.properties.previousMediaButton).hide();
					}
				});
			}
		});
		return xhr_load_content;
	};

	_p.animate_load = function (height_old, new_content) {
		var dfd = $.Deferred();
		/*$(_self.properties.contentDiv).stop(true).animate({opacity: 0}, 100);*/
		var img_obj = new_content.find('[data-image-src]');
		var img_src = img_obj.data('image-src');
		var set_content = function () {
			var height = $(_self.properties.contentDiv).css({visibility: 'hidden'}).html(new_content).height();
			$(_self.properties.contentDiv).css({height: height_old + 'px'});
			dfd.resolve(height);
		};
		if (img_src) {
			var img = new Image();
			img.src = img_src;
			if (img.height) {
				set_content();
			} else {
				/*img_obj.off('load').on('load', function(){
				 set_content();
				 });*/
				$(_self.properties.contentDiv).html(new_content).stop(true).css({opacity: 1});
				dfd.resolve();
			}
		} else {
			$(_self.properties.contentDiv).html(new_content).stop(true).css({opacity: 1});
			dfd.reject();
		}
		dfd.done(function (height) {
			$(_self.properties.contentDiv).css({visibility: 'visible'}).stop(true).animate({height: height + 'px', opacity: 1}, 200, function () {
				$(this).css('height', 'auto');
			});
			if (typeof window[_self.properties.gallery_name] === 'object') {
				$('#' + window[_self.properties.gallery_name].properties.windowObj.properties.loadBlockBgID).animate({scrollTop: 0}, 100);
			}
		});
		return dfd;
	};

	this.display_next_media = function () {
		_self.properties.mediaId = _self.properties.nextMedia;
		if (_self.properties.rand_param_change === false) {
			_self.init_content(true);
		} else {
			_self.init_content(false);
			_self.properties.rand_param_change = false;
		}
	};

	this.display_previous_media = function () {
		_self.properties.mediaId = _self.properties.previousMedia;
		if (_self.properties.rand_param_change === false) {
			_self.init_content(true);
		} else {
			_self.init_content(false);
			_self.properties.rand_param_change = false;
		}
	};


	/* RECROP METHODS*/
	this.add_selection = function (prefix, x1, y1, width, height) {
		_self.selections.push({prefix: prefix, x1: x1, y1: y1, width: width, height: height, orig_width: width, orig_height: height});
		return this;
	};

	this.add_selections = function (selections) {
		for (var i in selections)
			if (selections.hasOwnProperty(i)) {
				_self.add_selection(i, 0, 0, parseInt(selections[i].width), parseInt(selections[i].height));
			}
	};

	this.recalc_selection = function () {
		var height_ratio = _self.img.height / _self.image_css_size.height;
		var width_ratio = _self.img.width / _self.image_css_size.width;
		for (var i in _self.selections)
			if (_self.selections.hasOwnProperty(i)) {
				if (_self.img.width < _self.selections[i].width || _self.img.height < _self.selections[i].height) {
					var ratio;
					if (_self.img.width < _self.selections[i].width) {
						ratio = _self.img.width / _self.selections[i].width;
						_self.selections[i].width *= ratio;
						_self.selections[i].height *= ratio;
					}
					if (_self.img.height < _self.selections[i].height) {
						ratio = _self.img.height / _self.selections[i].height;
						_self.selections[i].width *= ratio;
						_self.selections[i].height *= ratio;
					}
				} else {
					var width_recalc = _self.selections[i].width * width_ratio;
					var height_recalc = _self.selections[i].height * height_ratio;
					if (width_recalc >= _self.selections[i].width && width_recalc < _self.img.width && height_recalc >= _self.selections[i].height && height_recalc < _self.img.height) {
						_self.selections[i].width = width_recalc;
						_self.selections[i].height = height_recalc;
					}
					var x1 = Math.round(_self.img.width / 2 - _self.selections[i].width / 2);
					if (x1 < 0) {
						x1 = 0;
					}
					var y1 = Math.round(_self.img.height / 2 - _self.selections[i].height / 2);
					if (y1 < 0) {
						y1 = 0;
					}
					_self.selections[i].x1 = x1;
					_self.selections[i].y1 = y1;
				}
			}
		return this;
	};

	this.change_selection = function () {
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

	this.init_imageareaselect = function () {
		_p.img_obj = $('#' + _self.properties.recrop_img_id);
		_p.photo_sizes_obj = $('#' + _self.properties.photo_sizes_id);
		_p.recrop_btn_obj = $('#' + _self.properties.recrop_btn_id);
		_p.photo_sizes_obj.html('');
		for (var i in _self.selections)
			if (_self.selections.hasOwnProperty(i)) {
				_p.photo_sizes_obj.append('<li><span data-index="' + i + '">' + _self.selections[i].orig_width + 'x' + _self.selections[i].orig_height + '</span></li>');
			}
		_p.photo_sizes_obj.find('li').removeClass('active').eq(_self.index).addClass('active');

		_p.photo_sizes_obj.off('click', 'li span').on('click', 'li span', function () {
			var size_index = $(this).data('index');
			if (size_index >= _self.selections.length || size_index == _self.index)
				return false;
			_self.index = size_index;
			_self.change_selection();
			_p.photo_sizes_obj.find('li').removeClass('active');
			$(this).parent().addClass('active');
			return false;
		});

		$('#' + _self.properties.recrop_container_id).off('recrop:do').on('recrop:do', function () {
			_self.recrop();
		});

		if ($.isEmptyObject(_self.selections))
			return;

		_self.img.src = _p.img_obj.attr('src');
		_self.img.onload = function () {
			if (_self.img.width) {
				_p.image_ready();
			} else {
				_p.img_obj.off('load').on('load', function () {
					_p.image_ready();
				});
			}
		};
		return this;
	};

	_p.image_ready = function (attempt) {
		attempt = attempt || 0;
		while (!(_p.img_obj.width() && _p.img_obj.height()) && attempt < 60) {
			setTimeout(function () {
				_p.image_ready(++attempt);
			}, 50);
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
			parent: '#' + _self.properties.recrop_container_id,
			x1: _self.selections[_self.index].x1,
			y1: _self.selections[_self.index].y1,
			x2: _self.selections[_self.index].x1 + _self.selections[_self.index].width,
			y2: _self.selections[_self.index].y1 + _self.selections[_self.index].height,
			zIndex: 2,
			onSelectStart: function (image, selection) {
				_p.hide_recrop_btn(this);
			},
			onSelectEnd: function (image, selection) {
				if (!selection.width || !selection.height)
					return;
				_p.show_recrop_btn(this);
				_self.selections[_self.index].x1 = Math.min(selection.x1, selection.x2);
				_self.selections[_self.index].y1 = Math.min(selection.y1, selection.y2);
				_self.selections[_self.index].width = selection.width;
				_self.selections[_self.index].height = selection.height;
			},
			onSelectChange: function (image, selection) {
				if (!selection.width || !selection.height)
					return;
				_p.hide_recrop_btn(this);
				_self.selections[_self.index].x1 = Math.min(selection.x1, selection.x2);
				_self.selections[_self.index].y1 = Math.min(selection.y1, selection.y2);
				_self.selections[_self.index].width = selection.width;
				_self.selections[_self.index].height = selection.height;
			},
			onInit: function (image, selection) {
				_p.show_recrop_btn(this);
			}
		});
		return this;
	};

	_p.show_recrop_btn = function (imageareaselect) {
		var selection_obj = $(imageareaselect.parent).find('.' + imageareaselect.classPrefix + '-selection');
		//imageAreaSelect плагин перехватывает и переопределяет события мышки, поэтому задаем onmousedown с собственным триггером
		var btn = $('<div class="selection-recrop-btn" onmouseup="$(\'#' + _self.properties.recrop_container_id + '\').trigger(\'recrop:do\');"></div>');
		selection_obj.html(btn);
	};

	_p.hide_recrop_btn = function (imageareaselect) {
		$(imageareaselect.parent).find('.' + imageareaselect.classPrefix + '-selection').html('');
	};

	this.recrop = function () {
		if (xhr_recrop && xhr_recrop.state() === 'pending') {
			return xhr_recrop;
		}
		xhr_recrop = $.ajax({
			url: _self.properties.siteUrl + _self.properties.recropUrl + _self.properties.mediaId,
			type: 'POST',
			data: _self.selections[_self.index],
			dataType: 'json',
			cache: false,
			success: function (resp) {
				if (resp.status) {
					if (resp.data.img_url) {
						$('img[src^="' + resp.data.img_url + '"]').attr('src', resp.data.img_url + '?' + new Date().getTime());
					}
				} else if (resp.errors.length) {
					_self.properties.errorObj.show_error_block(resp.errors, 'error');
				}
				if (resp.msg.length) {
					_self.properties.errorObj.show_error_block(resp.msg, 'success');
				}
			}
		});
		return xhr_recrop;
	};

	this.uninit_imageareaselect = function () {
		_self.imageareaselect = null;
		_self.selections = [];
		_self.index = 0;
		return this;
	};

	this.rotate = function (angle) {
		if (xhr_recrop && xhr_recrop.state() === 'pending') {
			return xhr_recrop;
		}

		xhr_recrop = $.ajax({
			url: _self.properties.siteUrl + _self.properties.rotateUrl + _self.properties.mediaId + '/' + angle,
			type: 'POST',
			data: {},
			dataType: 'json',
			cache: false,
			success: function (resp) {
				if (resp.status) {
					if (resp.data.img_url) {
						$('img[src^="' + resp.data.img_url + '"]').attr('src', resp.data.img_url + '?' + new Date().getTime());
						_self.index = 0;
						_self.init_imageareaselect();
						for (var i in resp.data.thumbs) {
							$('img[src^="' + resp.data.thumbs[i] + '"]').attr('src', resp.data.thumbs[i] + '?' + new Date().getTime());
						}
					}
					_self.properties.rand_param = Math.floor((Math.random() * 999999) + 1);
					_self.properties.rand_param_change = true;
				} else if (resp.errors.length) {
					_self.properties.errorObj.show_error_block(resp.errors, 'error');
				}
				if (resp.msg.length) {
					_self.properties.errorObj.show_error_block(resp.msg, 'success');
				}
			}
		});

		return xhr_recrop;
	};

	_self.Init(optionArr);
}