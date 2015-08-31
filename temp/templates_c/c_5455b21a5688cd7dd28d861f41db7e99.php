<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:02:15 Pacific Daylight Time */ ?>

<?php if (! $this->_vars['hide_poll']): ?>
<div id="poll_block_<?php echo $this->_vars['poll_data']['id']; ?>
" class="poll_block" style="border-top: 0px;">
	<link rel="stylesheet" type="text/css" href="<?php echo $this->_vars['site_root']; ?>
application/modules/polls/views/default/css/style.css" />

	<p><?php if ($this->_vars['language']):  echo $this->_vars['poll_data']['question'][$this->_vars['language']];  else:  echo $this->_vars['poll_data']['question'][$this->_vars['cur_lang']];  endif; ?></p>
	<div class="poll">
		<?php echo $this->_vars['poll_block']; ?>

	</div>
	<script type="text/javascript">
		<?php echo '
			$(function() {
				loadScripts(
					"';  echo tpl_function_js(array('module' => 'polls','file' => 'polls.js','return' => 'path'), $this); echo '", 
					function(){
						polls = new Polls({
							siteUrl: \'';  echo $this->_vars['site_url'];  echo '\',
							poll_id: \'';  echo $this->_vars['poll_data']['id'];  echo '\'
						});
					},
					\'polls\'
				);
			});
		'; ?>

	</script>
</div>
<?php endif; ?>