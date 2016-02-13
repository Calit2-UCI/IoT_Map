<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-11-08 22:39:29 Pacific Standard Time */ ?>

<ul>
<?php if (is_array($this->_vars['list']) and count((array)$this->_vars['list'])): foreach ((array)$this->_vars['list'] as $this->_vars['li']): ?>
<li>
	<?php if ($this->_vars['li']['clickable']): ?><a href="<?php echo $this->_vars['li']['link']; ?>
"><?php echo $this->_vars['li']['name']; ?>
</a><?php else:  echo $this->_vars['li']['name'];  endif; ?>
	<?php if ($this->_vars['li']['items']):  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "site_map". $this->module_templates.  $this->get_current_theme_gid('', '"site_map"'). "sitemap_level.tpl", array('list' => $this->_vars['li']['items'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  endif; ?>
</li>
<?php endforeach; endif; ?>
</ul>