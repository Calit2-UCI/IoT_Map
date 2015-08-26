function dynamicBlocksLayout(optionArr) {
	this.properties = {
		siteUrl: '',
		updateId: 'update_layout',
		formId: 'layout_form',
		areaId: 'area_layout',
		zIndex: 1001,
		errorObj: new Errors,
		sepDropTO: null,
		deleteDropTO: null
	};

	var _self = this;
	this.Init = function (options) {
		_self.properties = $.extend(_self.properties, options);

		$('#' + _self.properties.updateId).bind('click', function () {
			$('#' + _self.properties.formId).submit();
		});

		_self.set_draggable('#' + _self.properties.areaId + ' li.col');
		_self.set_resizable('#' + _self.properties.areaId + ' li.col');
		_self.set_droppable('#' + _self.properties.areaId + ' li');
	};

	this.set_draggable = function (selector) {
		var item = $(selector);
		item.draggable({
			refreshPositions: true,
			revert: true,
			zIndex: _self.properties.zIndex,
			start: function (event, ui) {
				_self.start_dragging(ui.helper);
			},
			stop: function (event, ui) {
				_self.stop_dragging(ui.helper);
			},
			drag: function (event, ui) {
				ui.position.left = ui.offset.left;
				ui.position.top = ui.offset.top;
			}
		});
		return item;
	};

	this.set_droppable = function (selector) {
		var item = $(selector);
		item.droppable({
			accept: '#' + _self.properties.areaId + ' li.col',
			over: function (event, ui) {
				var item = $(this);
				var min_width = ui.helper.attr('data-min-width');
				if (_self.properties.sepDropTO) {
					clearTimeout(_self.properties.sepDropTO);
				}
				var to = (item.is('.sep')) ? 300 : 1;
				$('#' + _self.properties.areaId).find('li.hover').removeClass('hover');
				item.addClass('hover');
				_self.properties.sepDropTO = setTimeout(function () {
					_self.set_drop_area(item, ui.offset, min_width, event.target);
				}, to);
			}
		});
		return item;
	};

	this.start_dragging = function (item) {
		/*if(item.hasClass('col30')){
		 if(item.hasClass('first')){
		 var next = item.next();
		 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
		 if(next.hasClass('col70')){
		 next.removeClass('col70').addClass('first');
		 next.find('input[name^="data"]').val('100');
		 next.resizable("destroy");
		 item.resizable("destroy");
		 }else{
		 next.removeClass('col30').addClass('col50').addClass('first');
		 next.find('input[name^="data"]').val('50');
		 
		 next = next.next();
		 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
		 next.removeClass('col30').addClass('col50');
		 next.find('input[name^="data"]').val('50');
		 }
		 }else{	
		 if(item.prev().hasClass('first')){
		 var prev = item.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 if(prev.hasClass('col70')){
		 prev.removeClass('col70').addClass('first');
		 prev.find('input[name^="data"]').val('100');
		 prev.resizable("destroy");
		 item.resizable("destroy");
		 }else{
		 prev.removeClass('col30').addClass('col50');
		 prev.find('input[name^="data"]').val('50');
		 
		 var next = item.next();
		 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
		 next.removeClass('col30').addClass('col50');
		 next.find('input[name^="data"]').val('50');
		 }
		 }else{
		 var prev = item.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 
		 prev.removeClass('col30').addClass('col50');
		 prev.find('input[name^="data"]').val('50');
		 
		 prev = prev.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 prev.removeClass('col30').addClass('col50');
		 prev.find('input[name^="data"]').val('50');
		 }
		 }
		 }else if(item.hasClass('col50')){
		 if(item.hasClass('first')){
		 var next = item.next();
		 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
		 next.removeClass('col50').addClass('first');
		 next.find('input[name^="data"]').val('100');
		 next.resizable("destroy");
		 }else{
		 var prev = item.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 prev.removeClass('col50');
		 prev.find('input[name^="data"]').val('100');
		 prev.resizable("destroy");
		 }
		 item.resizable("destroy");
		 }else if(item.hasClass('col70')){
		 if(item.hasClass('first')){
		 var next = item.next();
		 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
		 next.removeClass('col30').addClass('first');
		 next.find('input[name^="data"]').val('100');
		 next.resizable("destroy");
		 }else{
		 var prev = item.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 prev.removeClass('col30');
		 prev.find('input[name^="data"]').val('100');
		 prev.resizable("destroy");
		 }
		 item.resizable("destroy");
		 }else{
		 var prev = item.prev();
		 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
		 prev.unbind();
		 prev.remove();			
		 }*/
		var drop = $('<li class="col drop"></li>');
		if (item.is('.col30')) {
			drop.addClass('col30');
		} else if (item.is('.col50')) {
			drop.addClass('col50');
		} else if (item.is('.col70')) {
			drop.addClass('col70');
		}
		if (item.is('.first')) {
			drop.addClass('first');
		}
		item.before(drop);
		item.addClass('drag');
	};

	this.stop_dragging = function (item) {
		if (!item.hasClass('drag')) {
			return;
		}
		var drop = $('#' + _self.properties.areaId + ' .drop');
		if (drop.length) {
			_self.delete_drop_area(drop);
		}
		item.removeClass('drag');
		if (item.hasClass('col30')) {
			if (item.hasClass('first')) {
				var next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				if (next.hasClass('col50')) {
					next.removeClass('col50').removeClass('first').addClass('col30');
					next.find('input[name^="data"]').val('30');

					next = next.next();
					while (next.hasClass('drag') || next.hasClass('drop')) {
						next = next.next();
					}
					next.removeClass('col50').addClass('col30');
					next.find('input[name^="data"]').val('30');
				} else {
					next.removeClass('first').addClass('col70');
					next.find('input[name^="data"]').val('70');
					_self.set_resizable(next);
					_self.set_resizable(item);
				}
			} else if (item.prev().hasClass('first')) {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				if (prev.hasClass('col50')) {
					prev.removeClass('col50').addClass('col30');
					prev.find('input[name^="data"]').val('30');

					var next = item.next();
					while (next.hasClass('drag') || next.hasClass('drop')) {
						next = next.next();
					}
					next.removeClass('col50').addClass('col30');
					next.find('input[name^="data"]').val('30');
				} else {
					prev.addClass('col70');
					prev.find('input[name^="data"]').val('70');
					_self.set_resizable(prev);
					_self.set_resizable(item);
				}
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.removeClass('col50').addClass('col30');
				prev.find('input[name^="data"]').val('30');

				prev = prev.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.removeClass('col50').addClass('col30');
				prev.find('input[name^="data"]').val('30');
			}
		} else if (item.hasClass('col50')) {
			if (item.hasClass('first')) {
				var next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				next.removeClass('first').addClass('col50');
				next.find('input[name^="data"]').val('50');
				_self.set_resizable(next);
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.addClass('col50');
				prev.find('input[name^="data"]').val('50');
				_self.set_resizable(prev);
			}
			_self.set_resizable(item);
		} else if (item.hasClass('col70')) {
			if (item.hasClass('first')) {
				var next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				next.removeClass('first').addClass('col30');
				next.find('input[name^="data"]').val('30');
				_self.set_resizable(next);
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.addClass('col30');
				prev.find('input[name^="data"]').val('30');
				_self.set_resizable(prev);
			}
			_self.set_resizable(item);
		} else {
			_self.add_separator(item);
		}
	};

	this.set_drop_area = function (item, offset, min_width, target) {
		$('#' + _self.properties.areaId).find('li.hover').removeClass('hover');
		target = target || null;
		var exists_drop = $('#' + _self.properties.areaId + ' .drop');
		if (!(item.hasClass('sep'))) {
			_self.delete_drop_area(exists_drop);
		}


		var next = item.next();
		while (next.hasClass('drag') || next.hasClass('drop')) {
			next = next.next();
		}
		if (item.hasClass('drop') || item.hasClass('sep') && next.hasClass('sep')) {
			return;
		}

		var drop = $('<li class="col drop"></li>');

		if (item.hasClass('col30')) {
			if (min_width > 30) {
				return;
			}
			if (item.attr('data-min-width') > 30) {
				return;
			}
			drop.addClass('col30');
			drop.find('input[name^="data"]').val('30');
			if (item.hasClass('first')) {
				next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				if (!next.hasClass('col70')) {
					return;
				}
				if (next.attr('data-min-width') > 30) {
					return;
				}
				next.removeClass('col70').addClass('col30').find('input[name^="data"]').val('30');
				drop.addClass('first');
				item.removeClass('first');
				item.before(drop);
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				if (!prev.hasClass('col70')) {
					return;
				}
				if (prev.attr('data-min-width') > 30) {
					return;
				}
				prev.removeClass('col70').addClass('col30').find('input[name^="data"]').val('30');
				drop.addClass('first');
				item.removeClass('first');
				item.before(drop);
			}
		} else if (item.hasClass('col50')) {
			if (min_width > 30) {
				return;
			}
			if (item.attr('data-min-width') > 30) {
				return;
			}
			drop.addClass('col30');
			drop.find('input[name^="data"]').val('30');
			if (item.hasClass('first')) {
				next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				if (next.attr('data-min-width') > 30) {
					return;
				}
				next.removeClass('col50').addClass('col30').find('input[name^="data"]').val('30');
				var left = offset.left;
				if (target && target.offsetLeft) {
					left = target.offsetLeft;
				}
				if (left < (item.offset().left + item.width() / 2)) {
					item.removeClass('first');
					item.before(drop);
					drop.addClass('first');
				} else {
					item.after(drop);
				}
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				if (prev.attr('data-min-width') > 30) {
					return;
				}
				prev.removeClass('col50').addClass('col30').find('input[name^="data"]').val('30');
				var left = offset.left;
				if (target && target.offsetLeft) {
					left = target.offsetLeft;
				}
				if (left < (item.offset().left + item.width() / 2)) {
					item.before(drop);
				} else {
					item.after(drop);
				}
				/*if(item.prev().hasClass('drop')){
				 item.after(drop);
				 }else{
				 item.before(drop);
				 }*/
			}
			item.removeClass('col50').addClass('col30').find('input[name^="data"]').val('30');
		} else if (item.hasClass('col70')) {
			if (min_width > 30) {
				return;
			}
			drop.addClass('col30');
			drop.find('input[name^="data"]').val('30');
			if (item.hasClass('first')) {
				var left = offset.left / 2;
				if (left < (item.width() + 6) / 2) {
					item.removeClass('first');
					drop.addClass('first');
					item.before(drop);
				} else {
					item.after(drop);
				}
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.removeClass('col50').addClass('col30');
				prev.find('input[name^="data"]').val('30');

				var left = offset.left / 2;
				if (left < (item.width() + 6)) {
					item.before(drop);
				} else {
					item.after(drop);
				}
			}
			item.removeClass('col70').addClass('col30');
			item.find('input[name^="data"]').val('30');
		} else if (item.hasClass('col')) {
			if (min_width > 50) {
				return;
			}
			if (item.attr('data-min-width') > 50) {
				return;
			}
			drop.addClass('col50');
			drop.find('input[name^="data"]').val('50');
			var left = offset.left / 2;
			if (left < (item.width() + 6) / 2) {
				item.removeClass('first');
				drop.addClass('first');
				item.before(drop);
			} else {
				item.after(drop);
			}
			item.addClass('col50');
			item.find('input[name^="data"]').val('50');
		} else {
			_self.add_separator(item);
			item.before(drop);
		}

		drop.droppable({
			accept: '#' + _self.properties.areaId + ' li.col',
			drop: function (event, ui) {
				var item = $(this);
				_self.apply_block(item, ui.draggable);
			},
			out: function (event, ui) {
				var item = $(this);
				_self.delete_drop_area(item);
			},
		});
		_self.delete_drop_area(exists_drop);
	}

	this.delete_drop_area = function (item) {
		item.unbind();
		if (item.hasClass('col30')) {
			if (item.hasClass('first')) {
				var next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				next.removeClass('col30').addClass('col50').addClass('first');
				next.find('input[name^="data"]').val('50');

				next = next.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				next.removeClass('col30').addClass('col50');
				next.find('input[name^="data"]').val('50');
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.removeClass('col30').addClass('col50');
				prev.find('input[name^="data"]').val('50');

				if (prev.hasClass('first')) {
					var next = item.next();
					while (next.hasClass('drag') || next.hasClass('drop')) {
						next = next.next();
					}
					next.removeClass('col30').addClass('col50');
					next.find('input[name^="data"]').val('50');
				} else {
					prev = prev.prev();
					while (prev.hasClass('drag') || prev.hasClass('drop')) {
						prev = prev.prev();
					}
					prev.removeClass('col30').addClass('col50');
					prev.find('input[name^="data"]').val('50');
				}
			}
		} else if (item.hasClass('col50')) {
			if (item.hasClass('first')) {
				var next = item.next();
				while (next.hasClass('drag') || next.hasClass('drop')) {
					next = next.next();
				}
				next.removeClass('col50').addClass('first');
				next.find('input[name^="data"]').val('100');
			} else {
				var prev = item.prev();
				while (prev.hasClass('drag') || prev.hasClass('drop')) {
					prev = prev.prev();
				}
				prev.removeClass('col50');
				prev.find('input[name^="data"]').val('100');
			}
		} else {
			var prev = item.prev();
			while (prev.hasClass('drag') || prev.hasClass('drop')) {
				prev = prev.prev();
			}
			prev.unbind();
			prev.remove();
		}
		item.remove();
	};

	this.apply_block = function (item, draggable) {
		draggable.removeClass('drag');
		draggable.find('input[name^="data"]').val('100');
		if (item.hasClass('col30')) {
			draggable.addClass('col30');
			draggable.find('input[name^="data"]').val('30');
			/*if(item.hasClass('first')){
			 var next = item.next();
			 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
			 if(next.hasClass('col70')){
			 _self.set_resizable(next);
			 _self.set_resizable(draggable);
			 }
			 }else{
			 var prev = item.prev();
			 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
			 if(prev.hasClass('col70')){
			 _self.set_resizable(prev);
			 _self.set_resizable(draggable);
			 }
			 }*/
		} else {
			draggable.removeClass('col30');
		}
		if (item.hasClass('col50')) {
			draggable.addClass('col50');
			draggable.find('input[name^="data"]').val('50');
			/*if(item.hasClass('first')){
			 var next = item.next();
			 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
			 _self.set_resizable(next);
			 }else{
			 var prev = item.prev();
			 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
			 _self.set_resizable(prev);
			 }			
			 _self.set_resizable(draggable);*/
		} else {
			draggable.removeClass('col50');
		}
		if (item.hasClass('col70')) {
			draggable.addClass('col70');
			draggable.find('input[name^="data"]').val('70');
			/*if(item.hasClass('first')){
			 var next = item.next();
			 while(next.hasClass('drag') || next.hasClass('drop')) next = next.next();
			 _self.set_resizable(next);
			 }else{
			 var prev = item.prev();
			 while(prev.hasClass('drag') || prev.hasClass('drop')) prev = prev.prev();
			 _self.set_resizable(prev);
			 }
			 _self.set_resizable(draggable);*/
		} else {
			draggable.removeClass('col70');
		}
		if (item.hasClass('first')) {
			draggable.addClass('first');
		} else {
			draggable.removeClass('first');
		}
		item.replaceWith(draggable);

		var cols = $('#' + _self.properties.areaId).find('.col');
		_self.unset_resizable(cols);
		_self.set_resizable(cols);
	};

	this.add_separator = function (item) {
		var separator = '<li class="sep">&nbsp;</li>';
		separator = _self.set_droppable(separator);
		item.before(separator);
	};

	this.unset_resizable = function (selector) {
		$(selector).each(function (index, item) {
			if ($(item).hasClass('ui-resizable')) {
				try {
					$(item).resizable('destroy');
				} catch (e) {

				}
			}
		});
		return selector;
	};

	this.set_resizable = function (selector) {
		$(selector).each(function (index, item) {
			item = $(item);
			var width = null;
			var handles = null;
			var min_width_scale = 2;
			var max_width_scale = 4;
			var min_width = item.attr('data-min-width');
			if (min_width > 50) {
				return;
			}
			if (min_width == 50) {
				min_width_scale = 3;
			}
			if (item.hasClass('col30')) {
				if (item.hasClass('first')) {
					var next = item.next();
					while (next.hasClass('drag') || next.hasClass('drop')) {
						next = next.next();
					}
					if (next.hasClass('col70')) {
						min_width = next.attr('data-min-width');
						if (min_width > 50) {
							return;
						}
						if (min_width == 50) {
							min_width_scale = 3;
						}
						width = item.width() / 2;
					} else {
						return;
					}
				} else {
					var prev = item.prev();
					while (prev.hasClass('drag') || prev.hasClass('drop')) {
						prev = prev.prev();
					}
					if (prev.hasClass('col70')) {
						min_width = prev.attr('data-min-width');
						if (min_width > 50) {
							return;
						}
						if (min_width == 50) {
							min_width_scale = 3;
						}
						width = item.width() / 2;
					} else {
						return;
					}
				}
			} else if (item.hasClass('col50')) {
				if (item.hasClass('first')) {
					var next = item.next();
					while (next.hasClass('drag') || next.hasClass('drop')) {
						next = next.next();
					}
					min_width = next.attr('data-min-width');
				} else {
					var prev = item.prev();
					while (prev.hasClass('drag') || prev.hasClass('drop')) {
						prev = prev.prev();
					}
					min_width = prev.attr('data-min-width');
				}
				if (min_width > 50) {
					return;
				}
				if (min_width == 50) {
					if (min_width_scale == 3) {
						return;
					} else {
						max_width_scale = 3;
					}
				}
				width = item.width() / 3;
			} else if (item.hasClass('col70')) {
				width = item.width() / 4 - 4;
			} else {
				return;
			}
			if (item.hasClass('first')) {
				handles = 'e';
			} else {
				handles = 'w';
			}
			item.resizable({
				handles: handles,
				grid: [width, 0],
				minWidth: min_width_scale * width,
				maxWidth: max_width_scale * width,
				resize: function (event, ui) {
					_self.resize(ui.helper, ui.originalSize);
				},
				stop: function (event, ui) {
					_self.resized(ui.helper, ui.originalSize);
				},
			});
		});
	};

	this.resize = function (item, size) {
		var scale = null;
		var delta = null;
		if (item.hasClass('col50')) {
			scale = 2;
			delta = 0;
		} else if (item.hasClass('col30')) {
			scale = 3;
			delta = -10;
		} else {
			scale = 3 / 2;
			delta = 10;
		}
		var width = Math.floor(item.width() - delta);
		if (item.hasClass('first')) {
			var next = item.next();
			while (next.hasClass('drag') || next.hasClass('drop')) {
				next = next.next();
			}
			next.width(Math.floor(scale * (size.width - delta) - width));
		} else {
			var prev = item.prev();
			while (prev.hasClass('drag') || prev.hasClass('drop')) {
				prev = prev.prev();
			}
			prev.width(Math.floor(scale * (size.width - delta) - width));
		}
		item.css({position: 'relative', float: 'left', left: 'auto', top: 'auto', width: width});
	};

	this.resized = function (item) {
		var delta = 20;
		var width1 = item.width();
		item.removeClass('col30').removeClass('col50').removeClass('col70');
		if (item.hasClass('first')) {
			var next = item.next();
			while (next.hasClass('drag') || next.hasClass('drop')) {
				next = next.next();
			}
			var width2 = next.width();
			next.removeClass('col30').removeClass('col50').removeClass('col70');
			if (width1 - delta < width2 && width1 + delta > width2) {
				next.addClass('col50');
				next.find('input[name^="data"]').val('50');

				item.addClass('col50');
				item.find('input[name^="data"]').val('50');
			} else if (width1 < width2) {
				next.addClass('col70');
				next.find('input[name^="data"]').val('70');

				item.addClass('col30');
				item.find('input[name^="data"]').val('30');
			} else {
				next.addClass('col30');
				next.find('input[name^="data"]').val('30');

				item.addClass('col70');
				item.find('input[name^="data"]').val('70');
			}
			next.removeAttr('style');
		} else {
			var prev = item.prev();
			while (prev.hasClass('drag') || prev.hasClass('drop')) {
				prev = prev.prev();
			}
			var width2 = prev.width();
			prev.removeClass('col30').removeClass('col50').removeClass('col70');
			if (width1 - delta < width2 && width1 + delta > width2) {
				prev.addClass('col50');
				prev.find('input[name^="data"]').val('50');

				item.addClass('col50');
				item.find('input[name^="data"]').val('50');
			} else if (width1 < width2) {
				prev.addClass('col70');
				prev.find('input[name^="data"]').val('70');

				item.addClass('col30');
				item.find('input[name^="data"]').val('30');
			} else {
				prev.addClass('col30');
				prev.find('input[name^="data"]').val('30');

				item.addClass('col70');
				item.find('input[name^="data"]').val('70');
			}
			prev.removeAttr('style');
		}
		item.removeAttr('style');
	};

	_self.Init(optionArr);
}
