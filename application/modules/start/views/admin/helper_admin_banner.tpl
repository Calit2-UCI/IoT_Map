{js module='start' file='admin-banners.js'}
<script>{literal}
	$(function(){
		banners = new storeBanners({
            lang_code: "{/literal}{$lang_code}{literal}",
			langs: {
                custom: {
                    header: "{/literal}{l i='banners_header_custom' gid='start' type='js'}{literal}", 
                    description: "{/literal}{l i='banners_description_custom' gid='start' type='js'}{literal}", 
                    button: "{/literal}{l i='banners_button_custom' gid='start' type='js'}{literal}"
                },
                design: {
                    header: "{/literal}{l i='banners_header_design' gid='start' type='js'}{literal}", 
                    description: "{/literal}{l i='banners_description_design' gid='start' type='js'}{literal}", 
                    button: "{/literal}{l i='banners_button_design' gid='start' type='js'}{literal}"
                },
                modules: {
                    header: "{/literal}{l i='banners_header_modules' gid='start' type='js'}{literal}", 
                    description: "{/literal}{l i='banners_description_modules' gid='start' type='js'}{literal}", 
                    button: "{/literal}{l i='banners_button_modules' gid='start' type='js'}{literal}"
                },
                packages: {
                    header: "{/literal}{l i='banners_header_packages' gid='start' type='js'}{literal}", 
                    description: "{/literal}{l i='banners_description_packages' gid='start' type='js'}{literal}", 
                    button: "{/literal}{l i='banners_button_packages' gid='start' type='js'}{literal}"
                }
            }
		});
	});
{/literal}</script>