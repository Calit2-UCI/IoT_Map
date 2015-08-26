function albums(optionArr) {
	this.properties = {
		siteUrl: '/',
		galleryContentDiv: 'gallery_content',
		contentDiv: '#image_content',
		createAlbumButton: '#create_album_button',
		createAlbumContainer: '#create_album_container',
		albumNameInput: '#album_name',
		saveAlbumButton: '#save_album',
		saveAlbumUrl: 'media/ajax_save_album/',
		saveAlbumMode: 'small/',
		editAlbumUrl: 'media/edit_album',
		editAlbumUrlReplace: 'media/ajax_edit_album',
		editAlbumForm: '#album_form',
		noUserAlbums: '#no_user_albums',
		windowObj: new loadingContent({loadBlockWidth: '500px', closeBtnClass: 'w', loadBlockTopType: 'top', loadBlockTopPoint: 20, blockBody: true}),
		create_album_success_request: function (resp) {
			if (resp.status) {
				if (mediagallery) {
					mediagallery.properties.galleryContentPage = 1,
							mediagallery.properties.all_loaded = 0;
					mediagallery.load_content(1);
					this.windowObj.hide_load_block();
					if (resp.data.albums_select && mediagallery.properties.idUser == resp.data.id_user) {
						var selected_album = $(mediagallery.properties.albumSelector).val();
						$(mediagallery.properties.albumSelectorContainer).html(resp.data.albums_select).val(selected_album).prop('selected', 'selected');
					}
				}
			} else {
				error_object.show_error_block(resp.errors, 'error');
			}
		},
		edit_album_success_request: function () {
		}
	};

	var _self = this;

	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);
		_self.init_albums();
		_self.init_item_album_info();
		return this;
	};

	this.uninit = function () {
		$(_self.properties.contentDiv).off('click', _self.properties.createAlbumButton).off('click', _self.properties.saveAlbumButton);
		$('#' + _self.properties.galleryContentDiv).off('click', '.edit-album').off('mouseenter', '.photo').off('mouseleave', '.photo');
	};

	this.init_albums = function () {
		$(_self.properties.contentDiv).off('click', _self.properties.createAlbumButton).on('click', _self.properties.createAlbumButton, function () {
			$(_self.properties.createAlbumContainer).stop().fadeToggle().find('input[type="text"]').focus();
		}).off('click', _self.properties.saveAlbumButton).on('click', _self.properties.saveAlbumButton, function () {
			_self.create_album();
		});
	};

	this.init_item_album_info = function () {
		$('#' + _self.properties.galleryContentDiv).off('click', '.edit-album').on('click', '.edit-album', function (event) {
			event.preventDefault();
			href = $(this).attr('href');
			var ajax_link = href.replace(_self.properties.editAlbumUrl, _self.properties.editAlbumUrlReplace);
			_self.display_album_form(ajax_link);
			return false;

		})
				.off('mouseenter', '.photo').on('mouseenter', '.photo', function () {
			$(this).find('.info').stop().slideDown(100);
		})
				.off('mouseleave', '.photo').on('mouseleave', '.photo', function () {
			$(this).find('.info').stop(true).delay(100).slideUp(100);
		});
	};

	this.create_album = function () {
		name = $(_self.properties.albumNameInput).val();
		data = {'name': name};
		_self.properties.saveAlbumMode = 'small';
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.saveAlbumUrl + _self.properties.saveAlbumMode,
			type: 'POST',
			dataType: "json",
			data: data,
			cache: false,
			success: function (resp) {
				if (resp.status) {
					$('#user_albums').append('<div class="album album-item" id="user_album_' + resp.data.album_id + '"><i class="fa-check-empty status-icon"></i><span title="' + name + '">' + name + '</span></div> ');
					$('#user_album_' + resp.data.album_id).fadeIn('slow');
					$(_self.properties.albumNameInput).val('');
					$(_self.properties.noUserAlbums).addClass('hide');
					if (mediagallery && resp.data.albums_select && mediagallery.properties.idUser == resp.data.id_user) {
						var selected_album = $(mediagallery.properties.albumSelector).val();
						$(mediagallery.properties.albumSelectorContainer).html(resp.data.albums_select).val(selected_album).prop('selected', 'selected');
					}
				} else if (resp.errors.length) {
					error_object.show_error_block(resp.errors, 'error');
				}
				_self.properties.create_album_success_request(resp);
			}
		});
	};

	this.display_album_form = function (url) {
		$.ajax({
			url: url,
			type: 'POST',
			dataType: "html",
			cache: false,
			success: function (data) {
				_self.properties.windowObj.update_css_styles({width: '500px'});
				_self.properties.windowObj.show_load_block(data);
			}
		});
	};

	this.edit_album = function (album_id) {
		_self.properties.saveAlbumMode = 'full/';
		$.ajax({
			url: _self.properties.siteUrl + _self.properties.saveAlbumUrl + _self.properties.saveAlbumMode + album_id,
			type: 'POST',
			dataType: "json",
			data: $(_self.properties.editAlbumForm).serialize(),
			cache: false,
			success: function (data) {
				if (data.status) {
					_self.properties.edit_album_success_request(data);
				} else {
					error_object.show_error_block(data.errors, 'error');
				}
			}
		});
	};

	_self.Init(optionArr);
}