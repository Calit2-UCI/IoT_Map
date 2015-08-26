function media(optionArr) {
	this.properties = {
		gallery_name: 'mediagallery',
		siteUrl: '/',
		idUser: 0,
		is_guest: false,
		addPhotoID: 'add_photo',
		addPhotoUrl: 'media/ajax_add_images',
		deleteMediaUrl: 'media/delete_',
		deleteMediaUrlReplace: 'media/ajax_delete_',
		viewMediaUrl: 'media/ajax_view_media',
		galleryContentDiv: 'gallery_content',
		galleryContentUrl: 'media/ajax_get_list',
		recentMediaUrl: 'media/ajax_get_recent_media',
		galleryContentParam: 'all',
		galleryContentPage: 1,
		galleryPageContent: '.media-button-content',
		galleryPageButton: '#media_button',
		filterObj: '#filters',
		menuAlbumItem: 'albums',
		albumSelectorContainer: '#album_id_container',
		albumSelector: '#album_id',
		albumId: 0,
		addVideoID: 'add_video',
		addVideoUrl: 'media/ajax_add_video',
		uploadVideoForm: '#upload_video',
		sorterId: 'media_sorter',
		refreshRecentPhotosButton: '#refresh_recent_photos',
		refreshRecentPhotosDiv: '#recent_photo',
		load_on_scroll: 0,
		all_loaded: 0,
		loading_status: 0,
		lang_delete_confirm: 'Are you sure you want to delete this file?',
		lang_delete_confirm_album: 'Do you really want to delete the album?',
		order: 'date_add',
		direction: 'desc',
		post_data: {},
		place: 'user_gallery',
		windowObj: new loadingContent({loadBlockWidth: '500px', closeBtnClass: 'w', loadBlockTopType: 'top', loadBlockTopPoint: 20, blockBody: true, showAfterImagesLoad: false})
	};

	var xhr_load_content = null;
	var _p = {};

	var _self = this;


	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		_self.init_links();
		_self.init_filters();
		_self.init_sorter();
		_self.init_global_events();
		if (_self.properties.load_on_scroll) {
			setTimeout(function () {
				_self.setLoadOnScroll();
			}, 500);
		}
		_self.init_item_block_info();
	};

	this.uninit = function () {
		$(window).unbind('scroll.media').off('changeParam.gallery');
		$('#' + _self.properties.galleryContentDiv).off();
		$('#' + _self.properties.addPhotoID).unbind();
		$('#' + _self.properties.addVideoID).unbind();
		$(_self.properties.galleryPageButton).unbind();
		$(_self.properties.filterObj).find('li').unbind();
		$(_self.properties.albumSelectorContainer).off();
		$('#' + _self.properties.sorterId).off();
	};

	this.init_item_block_info = function () {
		$('#' + _self.properties.galleryContentDiv)
				.off('click', '[data-click="album"]').on('click', '[data-click="album"]', function (event) {
			event.preventDefault();
			var album_id = $(this).parents('[data-album-id]').attr('data-album-id');
			_self.properties.idUser = $(this).data('user-id') || _self.properties.idUser;
			if (album_id) {
				$(_self.properties.albumSelector).val(album_id).prop('selected', 'selected').change();
			}
			return false;
		})
				.off('click', '[data-click="view-media"]').on('click', '[data-click="view-media"]', function (event) {
			event.preventDefault();
			_self.properties.idUser = $(this).data('user-id') || _self.properties.idUser;
			if ($(this).data('id-album')) {
				_self.properties.albumId = $(this).data('id-album');
			}
			if ($(this).data('place')) {
				_self.properties.place = $(this).data('place');
			}
			var id_media = $(this).data('id-media');
			_self.display_edit_view_form(id_media);
			return false;
		})
				.off('click', '.delete-media').on('click', '.delete-media', function (event) {
			event.preventDefault();
			var href = $(this).attr('href');
			var id_album = $(this).attr('data-album-id');
			if (!id_album) {
				var alert_text = _self.properties.lang_delete_confirm;
			} else {
				var alert_text = _self.properties.lang_delete_confirm_album;
			}
			var ajax_link = href.replace(_self.properties.deleteMediaUrl, _self.properties.deleteMediaUrlReplace);
			alerts.show({
				text: alert_text,
				type: 'confirm',
				ok_callback: function () {
					_self.delete_media(ajax_link);
				}
			});
			return false;
		})
				.off('mouseenter', '.photo').on('mouseenter', '.photo', function () {
			$(this).find('.info').stop().slideDown(100);
		})
				.off('mouseleave', '.photo').on('mouseleave', '.photo', function () {
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
	};

	this.init_links = function () {
		$('#' + _self.properties.addPhotoID).unbind('click').bind('click', function () {
			_self.display_upload_photos_form();
		});
		$('#' + _self.properties.addVideoID).unbind('click').bind('click', function () {
			_self.display_upload_video_form();
		});
		$(_self.properties.galleryPageButton).unbind('click').bind('click', function () {
			_self.next_page();
		});
		$(_self.properties.refreshRecentPhotosButton).unbind('click').bind('click', function () {
			_self.refresh_recent_photos();
		});
	};

	this.init_filters = function () {
		$(_self.properties.filterObj).find('li').each(function () {
			var param = $(this).attr('data-param');
			if (_self.properties.galleryContentParam == param) {
				$(this).addClass('active');
			}
			$(this).bind('click', function () {
				$(_self.properties.filterObj).find('li').removeClass('active');
				$(this).addClass('active');
				var param = $(this).attr('data-param');
				if (param == _self.properties.menuAlbumItem) {
					$(_self.properties.albumSelectorContainer).stop(true).fadeIn();
					$('#' + _self.properties.sorterId).hide();
				} else {
					$(_self.properties.albumSelectorContainer).hide();
					$('#' + _self.properties.sorterId).stop(true).fadeIn();
				}
				_self.properties.galleryContentParam = param;
				_self.properties.albumId = 0;
				_self.reload();
			});
		});

		$(_self.properties.albumSelectorContainer).off('change', _self.properties.albumSelector).on('change', _self.properties.albumSelector, function () {
			_self.properties.albumId = $(this).val();
			if (_self.properties.albumId > 0) {
				$('#' + _self.properties.sorterId).stop(true).fadeIn();
			} else {
				$('#' + _self.properties.sorterId).hide();
			}
			_self.reload();
		});
	};

	this.init_sorter = function () {
		$('#' + _self.properties.sorterId).off('change', 'select').on('change', 'select', function () {
			_self.load_content(1, true);
		});
		$('#' + _self.properties.sorterId).off('click', '[data-role="sorter-dir"]').on('click', '[data-role="sorter-dir"]', function () {
			if ($(this).hasClass('up')) {
				$(this).removeClass('up').addClass('down');
			} else {
				$(this).removeClass('down').addClass('up');
			}
			_self.load_content(1, true);
		});
	};

	this.init_global_events = function () {
		$(window).off('changeParam.gallery').on('changeParam.gallery', function (e, param) {
			_self.properties.galleryContentParam = param;
		});
	};

	this.get_post_data = function () {
		var data = $.extend(_self.properties.post_data, {order: _self.properties.order, direction: _self.properties.direction, gallery_name: _self.properties.gallery_name, place: _self.properties.place});
		if ($('#' + _self.properties.sorterId).size()) {
			data.order = $('#' + _self.properties.sorterId).find('select').val();
			data.direction = $('#' + _self.properties.sorterId).find('[data-role="sorter-dir"]').hasClass('up') ? 'asc' : 'desc';
		}
		return data;
	};

	this.next_page = function () {
		_self.properties.galleryContentPage++;
		_self.load_content();

	};

	this.display_edit_view_form = function (id_media) {
		var data = _self.get_post_data();
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.viewMediaUrl + '/' + id_media + '/' + _self.properties.idUser + '/' + _self.properties.galleryContentParam + '/' + _self.properties.albumId,
			type: 'POST',
			data: data,
			dataType: "html",
			cache: false,
			success: function (data) {
				_self.properties.windowObj.update_css_styles({width: '962px'});
				_self.properties.windowObj.show_load_block(data);
			}
		});
	};

	this.delete_media = function (url) {
		var data = _self.get_post_data();
		$.ajax({
			url: url,
			type: 'POST',
			data: data,
			dataType: 'json',
			cache: false,
			success: function (resp) {
				if (resp.status == 1) {
					_self.reload();
					error_object.show_error_block(resp.message, 'success');

					if (resp.data && resp.data.albums_select) {
						$(_self.properties.albumSelectorContainer).html(resp.data.albums_select);
					}
				} else {
					error_object.show_error_block(resp.message, 'error');
				}
			}
		});
	};

	this.reload = function () {
		_self.properties.galleryContentPage = 1,
				_self.properties.all_loaded = 0;
		_self.load_content(1);
	};

	this.display_upload_photos_form = function () {
		_p.display_upload_form('image');
	};

	this.display_upload_video_form = function () {
		_p.display_upload_form('video');
	};

	_p.display_upload_form = function (type) {
		if (_self.properties.is_guest) {
			error_object.errors_access();
		} else {
			var data = _self.get_post_data();
			data.id_album = _self.properties.albumId;
			$.ajax({
				url: _self.properties.siteUrl + (type === 'image' ? _self.properties.addPhotoUrl : _self.properties.addVideoUrl),
				type: 'POST',
				data: data,
				dataType: "html",
				cache: false,
				success: function (data) {
					_self.properties.windowObj.update_css_styles({width: '500px'});
					_self.properties.windowObj.show_load_block(data);

				}
			});
		}
	};

	this.load_content = function (is_new, force) {
		force = force || false;
		if (force) {
			if (xhr_load_content) {
				xhr_load_content.abort();
			}
			_self.properties.galleryContentPage = 1;
		} else if (_self.properties.all_loaded == 1 || _self.properties.loading_status) {
			return true;
		}
		_self.properties.loading_status = 1;
		var data = _self.get_post_data();

		xhr_load_content = $.ajax({
			url: _self.properties.siteUrl + _self.properties.galleryContentUrl + '/' + _self.properties.idUser + '/' + _self.properties.galleryContentParam + '/' + _self.properties.galleryContentPage + '/' + _self.properties.albumId,
			type: 'POST',
			data: data,
			dataType: "json",
			cache: false,
			success: function (resp) {
				$(_self.properties.albumSelector).val(_self.properties.albumId).prop('selected', 'selected');
				if (is_new) {
					$('#' + _self.properties.galleryContentDiv).html(resp.content);
				} else {
					$('#' + _self.properties.galleryContentDiv).append(resp.content);
				}

				if (resp.albums_select) {
					$(_self.properties.albumSelectorContainer).html(resp.albums_select);
				}

				if (!resp.have_more) {
					_self.properties.load_on_scroll = 0;
					_self.properties.all_loaded = 1;
					$(_self.properties.galleryPageContent).hide();
				} else {
					$(_self.properties.galleryPageContent).show();
				}
			},
			complete: function () {
				_self.properties.loading_status = 0;
			}
		});
	};

	this.set_properties = function (properties) {
		_self.properties = $.extend(_self.properties, properties);
	};

	this.setLoadOnScroll = function () {
		if (_self.properties.load_on_scroll) {
			/*if($(window).scrollTop() > $('#'+_self.properties.id).offset().top){
			 $(window).scrollTop($('#'+_self.properties.id).offset().top);
			 }*/
			$(window).scrollTop(0);
			$(window).bind('scroll.media', function () {
				var offset = $(_self.properties.galleryPageContent).offset();
				var window_height = window.innerHeight ? window.innerHeight : $(window).height();
				if (offset && $(window).scrollTop() >= offset.top - window_height + 100) {
					_self.next_page();
				}
			});
		} else {
			$(window).unbind('scroll.media');
		}
	};

	this.refresh_recent_photos = function () {
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.recentMediaUrl,
			type: 'POST',
			data: {count: 16, upload_gid: 'photo'},
			dataType: "html",
			cache: false,
			success: function (data) {
				$(_self.properties.refreshRecentPhotosDiv).html(data);
			}
		});
	};

	_self.Init(optionArr);
}