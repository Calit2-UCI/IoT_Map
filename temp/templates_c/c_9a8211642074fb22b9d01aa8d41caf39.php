<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:03:37 Pacific Daylight Time */ ?>

<?php if ($this->_vars['auth_type'] == 'admin'):  if (is_array($this->_vars['menu']) and count((array)$this->_vars['menu'])): foreach ((array)$this->_vars['menu'] as $this->_vars['level1']):  if ($this->_vars['level1']['sub']): ?>
<div class="menu">
	<div class="t">
		<div class="b">
			<ul>
				<?php if (is_array($this->_vars['level1']['sub']) and count((array)$this->_vars['level1']['sub'])): foreach ((array)$this->_vars['level1']['sub'] as $this->_vars['level2']): ?>
				<li<?php if ($this->_vars['level2']['active'] == 1): ?> class="active"<?php endif; ?>>
					
					<div class="r">
						<a href="<?php echo $this->_vars['level2']['link']; ?>
">
							<i class="fa fa-<?php echo $this->_vars['level2']['icon']; ?>
 <?php if ($this->_vars['level2']['active'] == 1): ?>w<?php endif; ?>"></i>
							<?php echo $this->_vars['level2']['value']; ?>
	<?php if ($this->_vars['level2']['indicator']): ?><span class="num"><?php echo $this->_vars['level2']['indicator']; ?>
</span><?php endif; ?>
						</a>
					</div>
				</li>
				<?php endforeach; endif; ?>
			</ul>
		</div>
	</div>
</div>
<?php endif;  endforeach; endif;  else: ?>
<div class="menu">
	<div class="t">
		<div class="b min400">
		</div>
	</div>
</div>
<?php endif; ?>
