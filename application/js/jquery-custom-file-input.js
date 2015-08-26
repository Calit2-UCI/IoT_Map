jQuery.fn.choose = function(f) {
	$(this).bind('choose', f);
};

jQuery.fn.set_name = function(name) {
};

jQuery.fn.file = function() {
	return this.each(function() {
		var btn = $(this);
		var pos = btn.offset();
		var iName = 'pic';
								
		function update() {
			pos = btn.offset();
			file.css({
				'top': pos.top,
				'left': pos.left,
				'width': btn.width(),
				'height': btn.height()
			});
		}

		btn.mouseover(update);

		var file = $('<div></div>').appendTo($(this).parent()).css({
			'position': 'absolute',
			'overflow': 'hidden',
			'-moz-opacity': '0',
			'filter':  'alpha(opacity: 0)',
			'opacity': '0',
			'z-index': '2'		
		});

		var form = file;
		var input = form.find('input');
		
		function reset() {
			var input = $('<input type="file" multiple name="'+iName+'">').appendTo(form);
			input.change(function(e) {
				input.unbind();
				input.detach();
				btn.trigger('choose', [input]);
				reset();
			});
		};
		
		reset();

		function placer(e) {
		//	form.css('margin-left', e.pageX - pos.left - offset.width);
		//	form.css('margin-top', e.pageY - pos.top - offset.height + 3);					
		}

		function set_name(name){
			input.attr('name', name);
			iName = name;
		}
		
		function redirect(name) {
			file[name](function(e) {
				btn.trigger(name);
			});
		}

		file.mousemove(placer);
		btn.mousemove(placer);

		redirect('mouseover');
		redirect('mouseout');
		redirect('mousedown');
		redirect('mouseup');

		var offset = {
			width: file.width() - 25,
			height: file.height() / 2
		};

		update();
	});
};
