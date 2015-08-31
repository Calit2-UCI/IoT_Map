<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-31 00:06:50 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<div class="contact_us content-block">
	<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
	<div class="content-value fltl w650">
		<p class="maxwp100 mb10"><?php echo l('text_contact_form_edit', 'contact_us', '', 'text', array()); ?></p>
		<div class="edit_block">
			<form action="" method="post">
				<?php if ($this->_vars['reasons']): ?>
					<div class="r">
						<div class="f"><?php echo l('field_reason', 'contact_us', '', 'text', array()); ?>: </div>
						<div class="v">
							<select name="id_reason">
								<?php if (is_array($this->_vars['reasons']) and count((array)$this->_vars['reasons'])): foreach ((array)$this->_vars['reasons'] as $this->_vars['item']): ?>
									<option value="<?php echo $this->_vars['item']['id']; ?>
" <?php if ($this->_vars['data']['id_reason'] == $this->_vars['item']['id']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']['name']; ?>
</option>
								<?php endforeach; endif; ?>
							</select><br>
							<span class="pginfo msg reason"></span>
						</div>
					</div>
				<?php endif; ?>
				<div class="r">
					<div class="f"><?php echo l('field_user_name', 'contact_us', '', 'text', array()); ?>: </div>
					<div class="v">
						<input type="text" name="user_name" value="<?php echo $this->_vars['data']['user_name']; ?>
"><br>
						<span class="pginfo msg user_name"></span>
					</div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_user_email', 'contact_us', '', 'text', array()); ?>: </div>
					<div class="v">
						<input type="text" name="user_email" value="<?php echo $this->_vars['data']['user_email']; ?>
"><br>
						<span class="pginfo msg user_email"></span>
					</div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_subject', 'contact_us', '', 'text', array()); ?>: </div>
					<div class="v">
						<input type="text" name="subject" value="<?php echo $this->_vars['data']['subject']; ?>
"><br>
						<span class="pginfo msg subject"></span>
					</div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_message', 'contact_us', '', 'text', array()); ?>: </div>
					<div class="v">
						<textarea name="message"><?php echo $this->_vars['data']['message']; ?>
</textarea><br>
						<span class="pginfo msg message"></span>
					</div>
				</div>
				<div class="captcha r">
					<div class="f"><?php echo l('field_security_code', 'contact_us', '', 'text', array()); ?>: </div>
					<div class="v">
						<?php echo $this->_vars['data']['captcha']; ?>
<br>
						<input type="text" name="captcha_code" value=""><br>
						<span class="pginfo msg captcha_code"></span>
					</div>
				</div>
				<br>
				<div class="r">
					<div class="l"><input type="submit" class='btn' value="<?php echo l('btn_send', 'start', '', 'button', array()); ?>" name="btn_save"></div>
					<div class="b">&nbsp;</div>
				</div>
			</form>
		</div>
	</div>
	<div class="fltr">
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'big-right-banner'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'right-banner'), $this);?>
	</div>
</div>
<?php echo tpl_function_block(array('name' => 'show_social_networks_like','module' => 'social_networking'), $this); echo tpl_function_block(array('name' => 'show_social_networks_share','module' => 'social_networking'), $this); echo tpl_function_block(array('name' => 'show_social_networks_comments','module' => 'social_networking'), $this);?>
<br><br><br>
<div class="clr"></div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
