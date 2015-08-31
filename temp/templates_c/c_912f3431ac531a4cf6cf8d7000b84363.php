<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<?php if ($this->_vars['template'] == 'link'):  if (! $this->_vars['is_send']): ?><a href="javascript:void(0);" data-id="<?php echo $this->_vars['object_id']; ?>
" data-type="<?php echo $this->_vars['type']['gid']; ?>
" id="mark-as-span-<?php echo $this->_vars['rand']; ?>
" class="link-r-margin"><?php echo l('btn_mark_as_spam', 'spam', '', 'button', array()); ?></a><?php endif;  elseif ($this->_vars['template'] == 'minibutton'): ?>
<a <?php if (! $this->_vars['is_send']): ?>href="javascript:void(0);"<?php endif; ?> data-id="<?php echo $this->_vars['object_id']; ?>
" data-type="<?php echo $this->_vars['type']['gid']; ?>
" id="mark-as-span-<?php echo $this->_vars['rand']; ?>
" class="link-r-margin" title="<?php echo l('btn_mark_as_spam', 'spam', '', 'button', array()); ?>"><ins class="fa-flag-o pr5 <?php if ($this->_vars['is_send']): ?>g<?php endif; ?>"></ins></a>
<?php elseif ($this->_vars['template'] == 'whitebutton'): ?>
<a <?php if (! $this->_vars['is_send']): ?>href="javascript:void(0);"<?php endif; ?> data-id="<?php echo $this->_vars['object_id']; ?>
" data-type="<?php echo $this->_vars['type']['gid']; ?>
" id="mark-as-span-<?php echo $this->_vars['rand']; ?>
" class="link-r-margin" title="<?php echo l('btn_mark_as_spam', 'spam', '', 'button', array()); ?>"><ins class="fa-flag-o  edge w <?php if ($this->_vars['is_send']): ?>g<?php endif; ?>" id="<?php echo $this->_vars['type']['gid']; ?>
_<?php echo $this->_vars['object_id']; ?>
"></ins></a>
<?php else: ?>
<a <?php if (! $this->_vars['is_send']): ?>href="javascript:void(0);"<?php endif; ?> data-id="<?php echo $this->_vars['object_id']; ?>
" data-type="<?php echo $this->_vars['type']['gid']; ?>
" id="mark-as-span-<?php echo $this->_vars['rand']; ?>
" class="fright link-r-margin" title="<?php echo l('btn_mark_as_spam', 'spam', '', 'button', array()); ?>"><ins class="fa-flag-o icon-big edge hover <?php if ($this->_vars['is_send']): ?>g<?php endif; ?>"></ins></a>
<?php endif; ?>
<script><?php echo '
loadScripts(
	"';  echo tpl_function_js(array('module' => 'spam','file' => 'spam.js','return' => 'path'), $this); echo '",
	function(){
		spam = new Spam({
			siteUrl: \'';  echo $this->_vars['site_root'];  echo '\', 
			use_form: ';  if ($this->_vars['type']['form_type'] != 'checkbox'): ?>true<?php else: ?>false<?php endif;  echo ',
			';  if (! empty ( $this->_vars['is_spam_owner'] )): ?>isOwner: true,<?php endif;  echo '
			is_send: \'';  echo $this->_vars['is_send'];  echo '\', 
			error_is_send: \'';  if ($this->_vars['is_guest']): ?>ajax_login_link<?php else:  echo l("error_is_send_".$this->_vars['type']['gid'], "spam", '', 'text', array());  endif;  echo '\', 
			mark_as_spam_btn: \''; ?>
mark-as-span-<?php echo $this->_vars['rand'];  echo '\',
		});		
	},
	\'\'
);
'; ?>
</script>
