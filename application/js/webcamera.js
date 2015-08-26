function webcamera(optionArr){
	
	var _self = this;
	
	this.properties = {
		// Process settings
		wc_width: 320,
		wc_height: 240,
		wc_canvas: 'canvas',
		wc_video: 'video',
		wc_take_picture: 'take_picture',
		wc_save_picture: 'save_picture',
		wc_allow: 'allow',
		wc_change_photo: 'btn_change_photo',
		wc_use_webcamera: 'btn_use_webcamera',
		wc_cancel_webcamera: 'btn_cancel_webcamera',
		wc_load_avatar: 'load_avatar',
		wc_stuff: 'stuff',
		wc_repicture: 'repicture',
		wc_photo_edit: 'photo-edit',
		wc_videoStreamUrl: false,
		wc_alert: 'empty videoStreamUrl',
		errorObj: new Errors()
	};
	
	
	var wc_context = null;
	var canvas = $('#'+_self.properties.wc_canvas)[0];
	
	this.Init = function(options){
		_self.properties = $.extend(_self.properties, options);
		_self.wc_context = canvas.getContext('2d');
		return _self;
	}
	
	$('#'+_self.properties.wc_change_photo).unbind('click').bind('click', function(){
		$('#'+_self.properties.wc_load_avatar+', #'+_self.properties.wc_use_webcamera+', #'+_self.properties.wc_photo_edit+', #'+_self.properties.wc_change_photo).hide(300);
		$('#'+_self.properties.wc_load_avatar+', #'+_self.properties.wc_use_webcamera).show(300);
	});
	
	$('#'+_self.properties.wc_cancel_webcamera).unbind('click').bind('click', function(){
		$('#'+_self.properties.wc_stuff+', #'+_self.properties.wc_cancel_webcamera+', #'+_self.properties.wc_repicture).hide(300);
		$('#'+_self.properties.wc_load_avatar+', #'+_self.properties.wc_use_webcamera).show(300);
	});
	
	$('#'+_self.properties.wc_use_webcamera+', #'+_self.properties.wc_repicture).unbind('click').bind('click', function(){
		$('#'+_self.properties.wc_stuff+', #'+_self.properties.wc_cancel_webcamera).show(300);
		$('#'+_self.properties.wc_load_avatar+', #'+_self.properties.wc_use_webcamera+', #'+_self.properties.wc_photo_edit+', #'+_self.properties.wc_repicture+', #'+_self.properties.wc_save_picture).hide(300);
		$('#'+_self.properties.wc_take_picture+', #'+_self.properties.wc_video).css({'display':'inline-block'});
		$('#'+_self.properties.wc_canvas).attr('width','0');
		$('#'+_self.properties.wc_canvas).attr('height','0');
		$('#'+_self.properties.wc_video).attr('width','320');
		$('#'+_self.properties.wc_video).attr('height','240');
		
		$('#'+_self.properties.wc_take_picture).unbind('click').bind('click', _self.captureMe);
		if(!window.wc_videoStreamUrl){
			_self.get_UserMedia();
		}else{	
			video.src = window.wc_videoStreamUrl;
		}
	});
	
    this.captureMe = function () {
		
		if (!window.wc_videoStreamUrl) {
			_self.properties.errorObj.show_error_block(_self.properties.wc_alert, 'error');
			return false;
		}
		
		$('#'+_self.properties.wc_video).attr('width',_self.properties.wc_width);
		$('#'+_self.properties.wc_video).attr('height',_self.properties.wc_height);
		
		$('#'+_self.properties.wc_video+', #'+_self.properties.wc_take_picture).css({'display':'none'});
		$('#'+_self.properties.wc_canvas).attr('width', _self.properties.wc_width);
		$('#'+_self.properties.wc_canvas).attr('height', _self.properties.wc_height);
		$('#'+_self.properties.wc_repicture).css({'display':'inline-block'});
		
		// canvas translate mirror
		_self.wc_context.translate(canvas.width, 0);
		_self.wc_context.scale(-1, 1);
		
		_self.wc_context.drawImage(video, 0, 0, video.width, video.height);
		
		var base64dataUrl = canvas.toDataURL('image/png');
		_self.wc_context.setTransform(1, 0, 0, 1, 0, 0);
		var img = new Image();
		img.src = base64dataUrl;
		blob = window.dataURLtoBlob && window.dataURLtoBlob(base64dataUrl);
		
		$('#'+_self.properties.wc_save_picture).css({'display':'inline-block'});
		
		$('#'+_self.properties.wc_canvas).html(img);
		
		if (canvas.toBlob){
			canvas.toBlob(function(blob){
				try{
					var file = new File([blob], 'avatar.png', {type: 'image/png'});
					avatar_web_uploader.addFile(file);
				}catch(e){
					alert(e);
				}
			});
		} else {
			console.log("toBlob NOT SUPPORT");
		}
	}
	
	// video stream
	this.get_UserMedia = function (){
		navigator.getUserMedia = navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia;
		
		if(navigator.getUserMedia){
			window.URL.createObjectURL = window.URL.createObjectURL || window.URL.webkitCreateObjectURL || window.URL.mozCreateObjectURL || window.URL.msCreateObjectURL;
			
			navigator.getUserMedia({video: true, audio: false}, function (stream) {
				try{
					  $('#'+_self.properties.wc_allow).css({'display':'none'});
					  window.wc_videoStreamUrl = window.URL.createObjectURL(stream);
					  video.src = window.wc_videoStreamUrl;
				}catch(e){
					  console.log(e);
				}
			}, function (err) {
			  //console.log("An error occured! " + err);
			  _self.properties.errorObj.show_error_block(_self.properties.wc_alert, 'error');
			});
		} else {
			_self.properties.errorObj.show_error_block(_self.properties.wc_alert, 'error');
		}
		
	}
	
	_self.Init(optionArr);
}
