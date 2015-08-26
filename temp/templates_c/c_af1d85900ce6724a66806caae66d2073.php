<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 02:01:51 Pacific Daylight Time */ ?>

<?php if ($this->_vars['count_active'] > 1): ?>
	<?php if (! $this->_vars['type'] || $this->_vars['type'] == 'dropdown'): ?>
		<ul>
			<li>
				<select onchange="location.href = '<?php echo $this->_vars['site_url']; ?>
users/change_language/'+this.value;">
					<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['item']): ?>
						<?php if ($this->_vars['item']['status'] == '1'): ?>
							<option value="<?php echo $this->_vars['item']['id']; ?>
" <?php if ($this->_vars['item']['id'] == $this->_vars['current_lang']): ?> selected<?php endif; ?>>
								<?php echo $this->_vars['item']['name']; ?>

							</option>
						<?php endif; ?>
					<?php endforeach; endif; ?>
				</select>
			</li>
		</ul>
	<?php elseif ($this->_vars['type'] == 'menu'): ?>
		<menu class="header-item" label="<?php echo l('on_account_header', 'users_payments', '', 'text', array()); ?>">
			<?php echo $this->_vars['languages'][$this->_vars['current_lang']]['name']; ?>
&nbsp;
			<i class="fa-caret-down"></i>
			<div class="drop w150">
				<menu>
					<?php if (is_array($this->_vars['languages']) and count((array)$this->_vars['languages'])): foreach ((array)$this->_vars['languages'] as $this->_vars['item']): ?>
						<li>
							<?php if ($this->_vars['item']['status'] == '1'): ?>
								<a href="#" onclick="location.href = '<?php echo $this->_vars['site_url']; ?>
users/change_language/<?php echo $this->_vars['item']['id']; ?>
'"><?php echo $this->_vars['item']['name']; ?>
</a>
							<?php endif; ?>
						</li>
					<?php endforeach; endif; ?>
				</menu>
			</div>
		</menu>
	<?php endif; ?>
<?php endif; ?>
