<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-15 06:57:53 Pacific Standard Time */ ?>

<?php $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start();  if ($this->_vars['auth_type'] == 'user'): ?>
	<ul><li><?php echo l('welcome', 'users', '', 'text', array()); ?>&nbsp;<a id="users_link_profile" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'profile'), $this);?>"><?php echo $this->_vars['user_session_data']['output_name']; ?>
</a>!</li></ul>
<?php endif; ?>
	
<ul id="ajax_login_link_menu">
	<li<?php if ($this->_vars['auth_type'] == 'user'): ?> class="hide-always"<?php endif; ?>><a id="ajax_login_link" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'login_form'), $this);?>" onclick="return false;" id="ajax_login_link"><?php echo l('header_login', 'users', '', 'text', array()); ?></a></li>
</ul>

<script><?php echo '
	$(function(){
		user_ajax_login = new loadingContent({
			loadBlockWidth: \'520px\',
			linkerObjID: \'ajax_login_link_menu\',
			loadBlockLeftType: \'right\',
			loadBlockTopType: \'bottom\',
			closeBtnClass: \'w\'
		}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
		$(\'#ajax_login_link\').unbind(\'click\').click(function(){
			$.ajax({
				url: site_url + \'users/ajax_login_form\',
				cache: false,
				success: function(data){
					user_ajax_login.show_load_block(data);
				}
			});
			return false;
		});
	});
</script>'; ?>


<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
