<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-10-13 09:35:52 Pacific Daylight Time */ ?>

<?php if ($this->_vars['auth_type'] == 'module'): ?>
	<div class="menu">
		<div class="t">
			<div class="b">
				<ul>
					<li<?php if ($this->_vars['step'] == 'modules'): ?> class="active"<?php endif; ?>><div class="r"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/modules">Installed modules</a></div></li>
					<li<?php if ($this->_vars['step'] == 'enable_modules'): ?> class="active"<?php endif; ?>><div class="r">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/enable_modules">Enable modules<?php if ($this->_vars['enabled'] && $this->_vars['step'] != 'enable_modules'): ?><span class="num"><?php echo $this->_vars['enabled']; ?>
</span><?php endif; ?></a>
					</div></li>
					<li<?php if ($this->_vars['step'] == 'updates'): ?> class="active"<?php endif; ?>><div class="r">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/updates">Enable updates<?php if ($this->_vars['updates'] && $this->_vars['step'] != 'updates'): ?><span class="num"><?php echo $this->_vars['updates']; ?>
</span><?php endif; ?></a>
					</div></li>
					<li<?php if ($this->_vars['step'] == 'product_updates'): ?> class="active"<?php endif; ?>><div class="r">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/product_updates">Enable product updates<?php if ($this->_vars['product_updates'] && $this->_vars['step'] != 'product_updates'): ?><span class="num"><?php echo $this->_vars['product_updates']; ?>
</span><?php endif; ?></a>
					</div></li>
					<li<?php if ($this->_vars['step'] == 'libraries'): ?> class="active"<?php endif; ?>><div class="r"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/libraries">Installed libraries</a></div></li>
					<li<?php if ($this->_vars['step'] == 'enable_libraries'): ?> class="active"<?php endif; ?>><div class="r">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/enable_libraries">Enable libraries<?php if ($this->_vars['enabled_libraries'] && $this->_vars['step'] != 'enable_libraries'): ?><span class="num"><?php echo $this->_vars['enabled_libraries']; ?>
</span><?php endif; ?></a>
					</div></li>
					<li<?php if ($this->_vars['step'] == 'langs'): ?> class="active"<?php endif; ?>><div class="r">
						<a href="<?php echo $this->_vars['site_url']; ?>
admin/install/langs">Languages</a>
					</div></li>
					<li<?php if ($this->_vars['step'] == 'ftp'): ?> class="active"<?php endif; ?>><div class="r"><a href="<?php echo $this->_vars['site_url']; ?>
admin/install/installer_settings">Panel settings</a></div></li>
				</ul>
			</div>
		</div>
	</div>
<?php endif; ?>