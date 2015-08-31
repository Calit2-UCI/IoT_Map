<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-01 00:42:06 Pacific Daylight Time */ ?>

<?php echo l('sort_by', $this->_vars['sort_module'], '', 'text', array()); ?>&nbsp;
<select id="sorter-select-<?php echo $this->_vars['sort_rand']; ?>
">
	<?php if (is_array($this->_vars['sort_links']) and count((array)$this->_vars['sort_links'])): foreach ((array)$this->_vars['sort_links'] as $this->_vars['key'] => $this->_vars['item']): ?>
		<option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['sort_order']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
	<?php endforeach; endif; ?>
</select>
<i id="sorter-dir-<?php echo $this->_vars['sort_rand']; ?>
" data-role="sorter-dir" class="fa-long-arrow <?php if ($this->_vars['sort_direction'] == 'ASC'): ?>up<?php else: ?>down<?php endif; ?> icon-big pointer plr5"></i>
<a href="<?php echo $this->_vars['sort_url']; ?>
" id="sorter-link-<?php echo $this->_vars['sort_rand']; ?>
" class="hide">Go!</a>
<script type='text/javascript'><?php echo '
	$(\'#sorter-select-';  echo $this->_vars['sort_rand'];  echo '\').unbind(\'change\').bind(\'change\', function(){
		var url = $(\'#sorter-link-';  echo $this->_vars['sort_rand'];  echo '\').attr(\'href\') + \'/\' + $(this).find(\'option:selected\').val() + \'/ASC\';
		$(\'#sorter-link-';  echo $this->_vars['sort_rand'];  echo '\').attr(\'href\', url).trigger(\'click\');
	});
	$(\'#sorter-dir-';  echo $this->_vars['sort_rand'];  echo '\').unbind(\'click\').bind(\'click\', function(){
		var url = $(\'#sorter-link-';  echo $this->_vars['sort_rand'];  echo '\').attr(\'href\') + \'/\' + $(\'#sorter-select-';  echo $this->_vars['sort_rand'];  echo ' option:selected\').val() + \'/';  if ($this->_vars['sort_direction'] == 'ASC'): ?>DESC<?php else: ?>ASC<?php endif;  echo '\';
		$(\'#sorter-link-';  echo $this->_vars['sort_rand'];  echo '\').attr(\'href\', url).trigger(\'click\');
	});
</script>'; ?>
