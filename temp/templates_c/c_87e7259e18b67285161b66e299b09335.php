<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 19:13:28 Pacific Daylight Time */ ?>

<a title="<?php echo $this->_vars['params']['title_text']; ?>
" id='delete_select_block_<?php echo $this->_vars['params']['id_user']; ?>
' href="<?php echo $this->_vars['site_url']; ?>
admin/users/ajax_delete_select/<?php echo $this->_vars['params']['id_user']; ?>
/<?php echo $this->_vars['params']['deleted']; ?>
"><i class="fa fa-trash"></i></a>
<script>
<?php echo '
$(function(){
	$(\'#delete_select_block_';  echo $this->_vars['params']['id_user'];  echo '\').unbind(\'click\').click(function(){
		$.ajax({
			url: site_url + \'admin/users/ajax_delete_select/';  echo $this->_vars['params']['id_user']; ?>
/<?php echo $this->_vars['params']['deleted'];  echo '\',
			cache: false,
			success: function(data){
				delete_select_block.show_load_block(data);
			}
		});
		return false;
	});
});
'; ?>

</script>