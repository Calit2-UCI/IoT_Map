$(function(){
	$("ul.dropdown li").hover(function(){$(this).addClass("");$('ul.sub_menu',this).css({'visibility':'visible' , 'display':'block'});},
			function(){$(this).removeClass("");$('ul.sub_menu',this).css({'visibility':'hidden' , 'display':'none'});});
	
	$("ul.dropdown li.s_drop").hover(function(){$(this).addClass("");$('ul.sub_menu2',this).css({'visibility':'visible' , 'display':'block'});},
			function(){$(this).removeClass("");$('ul.sub_menu2',this).css({'visibility':'hidden' , 'display':'none'});});
			
				
	$("ul.dropdown_menu li").hover(function(){
        $(this).addClass("hover");
        $('ul:first',this).css('visibility', 'visible');
    
    }, function(){
        $(this).removeClass("hover");
        $('ul:first',this).css('visibility', 'hidden');
    
    });
    $("ul.dropdown_menu li ul li:has(ul)").find("a:first").append(" &raquo; ");		
});