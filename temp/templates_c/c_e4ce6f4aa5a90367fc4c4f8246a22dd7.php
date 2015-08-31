<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.js.php');
$this->register_function("js", "tpl_function_js"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seotag.php');
$this->register_function("seotag", "tpl_function_seotag");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-30 23:55:26 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => 'ui'));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<div>
		<h1><?php echo tpl_function_seotag(array('tag' => 'header_text'), $this);?></h1>
		<p class="header-comment"><?php echo l('text_register', 'users', '', 'text', array()); ?></p>
		<div class="edit_block">
			<form action="#" method="post" enctype="multipart/form-data">
				<div class="r">
					<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['user_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
					<div class="v">
						<select name="looking_user_type">
							<?php if (is_array($this->_vars['user_types']['option']) and count((array)$this->_vars['user_types']['option'])): foreach ((array)$this->_vars['user_types']['option'] as $this->_vars['key'] => $this->_vars['item']): ?><option value="<?php echo $this->_vars['key']; ?>
"<?php if ($this->_vars['key'] == $this->_vars['data']['looking_user_type']): ?> selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option><?php endforeach; endif; ?>
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_email', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="email" value="<?php echo $this->_run_modifier($this->_vars['data']['email'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="text" name="nickname" value="<?php echo $this->_run_modifier($this->_vars['data']['nickname'], 'escape', 'plugin', 1); ?>
"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_password', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type="password" name="password" value="<?php echo $this->_vars['data']['password']; ?>
"></div>
				</div>
				<?php if (! empty ( $this->_vars['use_repassword'] )): ?>
					<div class="r">
						<div class="f"><?php echo l('field_repassword', 'users', '', 'text', array()); ?>: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				<?php endif; ?>
				<div class="r">
					<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>: </div>
					<div class="v"><input type='text' value='<?php echo $this->_vars['data']['birth_date']; ?>
' name="birth_date" id="datepicker" maxlength="10"></div>
				</div>
				<div class="r">
					<div class="f"><?php echo l('field_location', 'users', '', 'text', array()); ?>: </div>
					<div class="v">
						<?php echo tpl_function_block(array('name' => 'location_select','module' => 'countries','select_type' => 'city','id_country' => $this->_vars['data']['id_country'],'id_region' => $this->_vars['data']['id_region'],'id_city' => $this->_vars['data']['id_city'],'var_country_name' => 'id_country','var_region_name' => 'id_region','var_city_name' => 'id_city'), $this);?>
					</div>
					<input type="hidden" name="lat" value="<?php echo $this->_run_modifier($this->_vars['data']['lat'], 'escape', 'plugin', 1); ?>
" id="lat">
					<input type="hidden" name="lon" value="<?php echo $this->_run_modifier($this->_vars['data']['lon'], 'escape', 'plugin', 1); ?>
" id="lon">
				</div>
									<?php echo tpl_function_block(array('name' => geomap_load_geocoder,'module' => 'geomap'), $this);?>
					
				<script><?php echo '
					$(function(){
						loadScripts(
							["';  echo tpl_function_js(array('module' => 'users','file' => 'users-map.js','return' => 'path'), $this); echo '"],
							function(){
								users_map = new usersMap({
									siteUrl: site_url,
								});
							},
							[\'users_map\'],
							{async: true}
						);
					});
				'; ?>
</script>
				<?php echo tpl_function_helper(array('func_name' => 'get_user_subscriptions_form','module' => 'subscriptions','func_param' => 'register'), $this);?>
				<br>
				<div class="r">
					<div class="f"><?php echo l('field_captcha', 'users', '', 'text', array()); ?>: </div>
					<div class="v captcha"><?php echo $this->_vars['data']['captcha_image']; ?>
 <input type="text" name="captcha_confirmation" value="" maxlength="<?php echo $this->_vars['data']['captcha_word_length']; ?>
" /></div>
				</div>
				<div class="r">
					<div class="v">
						<input id="confirmation" type='checkbox' value='1' name="confirmation" <?php if ($this->_vars['data']['confirmation']): ?>checked <?php endif; ?>/>
						<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'legal_terms_link')); tpl_block_capture(array('assign' => 'legal_terms_link'), null, $this); ob_start();  echo tpl_function_block(array('name' => 'get_page_link','module' => 'content','page_gid' => 'legal-terms'), $this); $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
						<label for="confirmation"><?php echo l('field_confirmation', 'users', '', 'text', array()); ?></label>
						<?php if (! empty ( $this->_vars['legal_terms_link'] )): ?>
							<a href="<?php echo $this->_vars['legal_terms_link']; ?>
"><?php echo l('terms_and_conditions', 'users', '', 'text', array()); ?></a>
						<?php else: ?>
							<?php echo l('terms_and_conditions', 'users', '', 'text', array()); ?>
						<?php endif; ?>
						<span class="pginfo msg confirmation"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">&nbsp;</div>
					<div class="v"><input type="submit" value="<?php echo l('btn_register', 'start', '', 'button', array()); ?>" name="btn_register"></div>
				</div>
			</form>

			<script type='text/javascript'><?php echo '
				$(function(){
					var date_now = new Date();
					var date_min = new Date(date_now.getYear() - ';  echo $this->_vars['age_max'];  echo ', 0, 1);
					var date_max = new Date(date_now.getYear() - ';  echo $this->_vars['age_min'];  echo ', 0, 1);
					var yr =  (date_min.getFullYear()) + \':\' + (date_max.getFullYear());
					$( "#datepicker" ).datepicker({
						dateFormat :\'yy-mm-dd\',
						changeYear: true,
						changeMonth: true,
						yearRange: yr,
						defaultDate: date_max
					});
				});
			</script>'; ?>

                        
                        <?php echo tpl_function_helper(array('func_name' => 'incomplete_signup_script','module' => 'incomplete_signup'), $this);?>
	</div>

		<?php echo tpl_function_block(array('name' => 'show_social_networks_like','module' => 'social_networking'), $this);?>
		<?php echo tpl_function_block(array('name' => 'show_social_networks_share','module' => 'social_networking'), $this);?>
		<?php echo tpl_function_block(array('name' => 'show_social_networks_comments','module' => 'social_networking'), $this);?>
	</div>
<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
