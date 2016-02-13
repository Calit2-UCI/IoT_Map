<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.counter.php');
$this->register_function("counter", "tpl_function_counter");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:34:55 Pacific Standard Time */ ?>

<ul>
<?php echo tpl_function_counter(array('start' => 0,'print' => false), $this); if (is_array($this->_vars['content_tree']) and count((array)$this->_vars['content_tree'])): foreach ((array)$this->_vars['content_tree'] as $this->_vars['key'] => $this->_vars['item']): ?>
<li<?php if ($this->_vars['item']['active']): ?> class="active"<?php endif; ?>><a href="<?php echo tpl_function_seolink(array('module' => 'content','method' => 'view','data' => $this->_vars['item']), $this);?>"><?php echo $this->_vars['item']['title']; ?>
</a></li>
<?php endforeach; endif; ?>
</ul>
