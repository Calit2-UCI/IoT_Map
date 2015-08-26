<a href="" data-im="{$im_data.id_contact}" data-pjax="0" onclick="event.preventDefault(); addImContact_{$im_data.id_contact}();" class="link-r-margin chat-im" title="{l i='im_chat' gid='im'}"><i class="fa-comment icon-big edge hover"></i></a>

<script>{literal}
	function addImContact_{/literal}{$im_data.id_contact}{literal}(waiting_im_sec){
		waiting_im_sec = waiting_im_sec || 0;
		if(!window.im && waiting_im_sec < 30){
			setTimeout(function(){addImContact_{/literal}{$im_data.id_contact}{literal}(waiting_im_sec);}, 100);
			return;
		}
		var data = {/literal}{$im_json_data}{literal};
		im.openContact(data.contact_list);
	}
	$('.chat-im').on('click', function(){
		var contact_id = $(this).data('im');
		if (contact_id) {
			im.setActiveContact(contact_id);
		}
	});
</script>{/literal}