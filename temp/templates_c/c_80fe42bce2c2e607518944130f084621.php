<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 19:47:35 Pacific Daylight Time */ ?>

<div class="tabs tab-size-15 noPrint">
	<ul>
		<?php 
$this->assign('wall_section_name', l('filter_section_wall', 'users', '', 'text', array()));
 ?><li<?php if ($this->_vars['profile_section'] == 'wall'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['seodata'],'section-code' => 'wall','section-name' => $this->_vars['wall_section_name']), $this);?>"><?php echo $this->_vars['wall_section_name']; ?>
</a></li>		<?php 
$this->assign('profile_section_name', l('filter_section_profile', 'users', '', 'text', array()));
 ?><li<?php if ($this->_vars['profile_section'] == 'profile'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['seodata'],'section-code' => 'profile','section-name' => $this->_vars['profile_section_name']), $this);?>"><?php echo $this->_vars['profile_section_name']; ?>
</a></li>
		<?php 
$this->assign('gallery_section_name', l('filter_section_gallery', 'users', '', 'text', array()));
 ?><li<?php if ($this->_vars['profile_section'] == 'gallery'): ?> class="active"<?php endif; ?>><a data-pjax-no-scroll="1" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'view','data' => $this->_vars['seodata'],'section-code' => 'gallery','section-name' => $this->_vars['gallery_section_name']), $this);?>"><?php echo $this->_vars['gallery_section_name']; ?>
</a></li>	</ul>
</div>
