function load_preview(set_gid){
	var height = $(window).height()-100;
	var width = $(window).width()-120;
	contentLoad.show_load_block('<div class="load_content"><iframe src="'+set_gid+'" style="width: '+width+'px; height: '+height+'px"></iframe></div>');
	var load_block_id = contentLoad.properties.loadBlockID;
	
	$('#'+load_block_id+' iframe').bind('load', function(){
		$(this).contents().find('body').bind('click', function(){ return false;});	
		var clicker = $('<div></div>').css({
		'z-index': 10200,
		'width': '100%',	
		'height': $(this).contents().height()+'px',	
		'position': 'absolute'			
		});			
		$(this).contents().find('body').prepend(clicker);
	});

	return false;
}

