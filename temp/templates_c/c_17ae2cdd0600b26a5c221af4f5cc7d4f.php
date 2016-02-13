<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.selectbox.php');
$this->register_function("selectbox", "tpl_function_selectbox"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.seolink.php');
$this->register_function("seolink", "tpl_function_seolink"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.helper.php');
$this->register_function("helper", "tpl_function_helper");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-01-26 03:42:16 Pacific Standard Time */ ?>


<!-------------script.js----------------->
<script><?php echo '
	$(function(){
		user_ajax_login = new loadingContent({
			loadBlockWidth: \'520px\',
			linkerObjID: \'ajax_login_link_menu\',
			loadBlockLeftType: \'right\',
			loadBlockTopType: \'bottom\',
			closeBtnClass: \'w\'
		}).update_css_styles({\'z-index\': 2000}).update_css_styles({\'z-index\': 2000}, \'bg\');
		$(\'#ajax_login_link\').unbind(\'click\').click(function(){
			$.ajax({
				url: site_url + \'users/ajax_login_form\',
				cache: false,
				success: function(data){
					user_ajax_login.show_load_block(data);
				}
			});
			return false;
		});
	});

	<!-----add js here------>
	
	
</script>'; ?>



<!--------------style.css---------------->
<style type="text/css">
<?php echo '
.background{
	background-color:#089de3;
    box-shadow: 0 4px 4px -2px #968a88;
    -moz-box-shadow: 0 4px 4px -2px #968a88;
    -webkit-box-shadow: 0 4px 4px -2px #968a88;
}

.logoHeader{
	display: inline-block;
	/*width: 13%;*/
	position: relative;
	padding-top: 1%;
	padding-bottom: 0.7%;
}

	<!-----add style here------>

.pHeader{
	font-size:100%;
}

.buttons{
	width:40%;
	float:right;
	padding-top:2.35%;	
}

.companyRegButton{
	display:inline-block;
	float:left;
	width:68%;
}

.loginButton{
	display:inline-block;
	float:right;
	width:29.5%;
}

.ulHeader{
	display: inline-block;
	position: relative;
	float:right;
	width:86%;
}

.searchPart {
	display: inline-block; 
	width: 100%; 
	align:right;
	padding:2.35% 0;	
}

.searchDropdown{
	display: inline-block;
	width: 40%;
	position: relative;
}

.searchBarAndButton{
	width:59%;
	float:right;
}

.searchTextBox {
	width: 77%;
	padding-top:1%;
	padding-bottom:0.8%;
	padding-left:1%;
	float:left;
}

.searchButton {
	width:20%;
	float:right;
	height: 2.1em;
}

'; ?>

</style>
<!---------------------------------------->

<div class="background">
	<div class="content">
		<div class="logoHeader">
			<a href="<?php echo $this->_vars['site_url']; ?>
" class="logo-block">
			<img src="<?php echo $this->_vars['site_root'];  echo $this->_vars['logo_settings']['path']; ?>
" alt="<?php echo tpl_function_helper(array('func_name' => 'seo_tags_default','func_param' => 'header_text'), $this);?>">
			</a>
		</div>
		
		<ul class="ulHeader">
			<li>
				<div class="buttons">
				<!--<div>--> <!--style="line-height:30%;">-->
					<div class="companyRegButton">
						<a href="<?php echo $this->_vars['site_url']; ?>
users/registration"><button type="button" style="width:100%;height: 2.13em;">Company Registration</button></a>
					</div>

					<div class="loginButton" id="ajax_login_link_menu">
						<!--a id="ajax_login_link" href="<?php echo tpl_function_seolink(array('module' => 'users','method' => 'login_form'), $this);?>"><button type="button" style="width:100%;">Login</button></a-->
						<a href="<?php echo $this->_vars['site_url']; ?>
users/login_form"><button type="button" style="width:100%;height: 2.13em;">Login</button></a>
					</div>
				</div>

				<div class="searchPart">
				
					<div class="searchDropdown">
						<?php echo tpl_function_selectbox(array('input' => 'user_type','id' => 'looking_user_type','value' => $this->_vars['user_types']['option'],'default' => $this->_vars['all_select_lang']), $this);?>
					</div>
					<div class="searchBarAndButton">
						<input class="searchTextBox" type="text" name="search" placeholder="<?php echo l('search_people', 'start', '', 'text', array()); ?>"/>
						<input class="searchButton"type="submit" value="Search" id="main_search_button_<?php echo $this->_vars['form_settings']['form_id']; ?>
" name="search_button">
					</div>
				</div>
			</li>
		</ul>
	</div>
</div>

<div class="content"> <!--welcome content-->
	</br>
	<div  style="padding:3% 66% 0 0;">
		<h1 style="font-size:225%;">Welcome to Calit2 IoTOC</h1>
		<p class="pHeader">Find your local professional business partner in a snap with Calit2's IoT OC map. Join our growing local IoT OC community.</p>
		</br><p class="pHeader">Create a business profile, post your IoT skills and ideas to communicate with other IoT business professionals like yourself.</p> 
		</br><p class="pHeader">We hope that this non-profit academic research site is the place where you will find your IoT OC partner!</p>
		</br></br><h1>Want to join in?</h1>
		<p>Just create a simple company profile, post up keywords that best describe your business and soon you'll be networking with other IoT professionals. IoT business simplified! Get started today and find your IoT business match!</p>
	</div>
	</br>
	</br>
	</br>
</div>


