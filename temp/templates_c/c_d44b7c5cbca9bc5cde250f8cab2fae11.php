<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.menu.php');
$this->register_function("menu", "tpl_function_menu"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-06 00:40:36 Pacific Standard Time */ ?>

			</div>
		</div>
		<?php echo tpl_function_helper(array('func_name' => 'show_banner_place','module' => 'banners','func_param' => 'bottom-banner'), $this);?>
		<div class="footer" style="background-color:#FFFFFF; height:100px">
			<div class="content">
				<?php echo tpl_function_menu(array('gid' => 'user_footer_menu'), $this);?>
				<!--div class="copyright"><?php if ($this->_vars['DEMO_MODE']):  echo $this->_vars['demo_copyright'];  else: ?>&copy;&nbsp;2000-2015&nbsp;<a href="http://www.pilotgroup.net">PilotGroup.NET</a> Powered by <a href="http://www.datingpro.com/">PG Dating Pro</a><?php endif; ?></div-->
				<div class="copyright"><center>Copyright&nbsp;&copy;&nbsp;2015-2016&nbsp;Calit2 IoTOC</center></div>
				</br>
			</div>
		</div>
		<?php echo tpl_function_helper(array('func_name' => 'lang_editor','module' => 'languages'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'seo_traker','helper_name' => 'seo_advanced_helper','module' => 'seo_advanced','func_param' => 'footer'), $this);?>
		<?php echo tpl_function_helper(array('func_name' => 'cookie_policy_block','module' => 'cookie_policy','helper_name' => 'cookie_policy'), $this); if (empty ( $this->_vars['is_pjax'] )): ?>
</div>
</body>
</html>
<?php endif; ?>
