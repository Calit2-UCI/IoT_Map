<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-03 01:40:41 Pacific Daylight Time */ ?>

<a href="" data-im="<?php echo $this->_vars['im_data']['id_contact']; ?>
" data-pjax="0" onclick="event.preventDefault(); addImContact_<?php echo $this->_vars['im_data']['id_contact']; ?>
();" class="link-r-margin chat-im" title="<?php echo l('im_chat', 'im', '', 'text', array()); ?>"><i class="fa-comment icon-big edge hover"></i></a>

<script><?php echo '
	function addImContact_';  echo $this->_vars['im_data']['id_contact'];  echo '(waiting_im_sec){
		waiting_im_sec = waiting_im_sec || 0;
		if(!window.im && waiting_im_sec < 30){
			setTimeout(function(){addImContact_';  echo $this->_vars['im_data']['id_contact'];  echo '(waiting_im_sec);}, 100);
			return;
		}
		var data = ';  echo $this->_vars['im_json_data'];  echo ';
		im.openContact(data.contact_list);
	}
	$(\'.chat-im\').on(\'click\', function(){
		var contact_id = $(this).data(\'im\');
		if (contact_id) {
			im.setActiveContact(contact_id);
		}
	});
</script>'; ?>
