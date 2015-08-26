<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-08-25 01:31:18 Pacific Daylight Time */ ?>

						</div>
				</div>
			</div>

			<div class="clr"></div>

		</div>
		<div class="footer"><?php if ($this->_vars['DEMO_MODE']):  echo $this->_vars['demo_copyright'];  else: ?>&copy;&nbsp;2000-2015&nbsp;<a href="http://www.pilotgroup.net">PilotGroup.NET</a> Powered by <a href="http://www.datingpro.com/">PG Dating Pro</a><?php endif; ?></div>
		<div class="clr"></div>
	</div>
	<?php echo tpl_function_helper(array('func_name' => 'lang_editor','helper_name' => 'languages','module' => 'languages'), $this);?>
</body>
</html>
