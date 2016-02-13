<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.capture.php');
$this->register_block("capture", "tpl_block_capture"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\block.strip.php');
$this->register_block("strip", "tpl_block_strip"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-13 02:41:58 Pacific Standard Time */ ?>

<h2 class="line top bottom linked">
	<?php echo l('table_header_personal', 'users', '', 'text', array()); ?>
</h2>
<div class="view-section">
	<?php 
$this->assign('no_info_str', l('no_information', 'users', '', 'text', array()));
 ?>
	
	<?php if ($this->_vars['data']['fname']): ?>
		<div class="r">
			<div class="f"><?php echo l('field_fname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['fname']; ?>
</div>
		</div>
	<?php endif; ?>
	
	<div class="r">
		<div class="f"><?php echo l('field_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['user_type_str']; ?>
</div>
	</div>
	<?php if ($this->_vars['data']['looking_user_type_str']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_looking_user_type', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['looking_user_type_str']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_min']): ?>
	<div class="r hide">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('from', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_min']; ?>
</div>
	</div>
	<?php endif; ?>
	<?php if ($this->_vars['data']['age_max']): ?>
	<div class="r hide">
		<div class="f"><?php echo l('field_partner_age', 'users', '', 'text', array()); ?> <?php echo l('to', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['age_max']; ?>
</div>
	</div>
	<?php endif; ?>
	<div class="r hide">
		<div class="f"><?php echo l('field_nickname', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['nickname']; ?>
</div>
	</div>

	<?php if ($this->_vars['data']['sname']): ?>
		<div class="r hide">
			<div class="f"><?php echo l('field_sname', 'users', '', 'text', array()); ?>:</div>
			<div class="v"><?php echo $this->_vars['data']['sname']; ?>
</div>
		</div>
	<?php endif; ?>
	<div class="r hide">
		<div class="f"><?php echo l('birth_date', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['birth_date']; ?>
</div>
	</div>
	
	<div class="r">
		<div class="f">Website:</div>
		<div class="v"><?php echo $this->_vars['data']['website']; ?>
</div>
	</div>
	
	<?php if (true): ?>	
	<div class="r">
		<div class="f">Address (street):</div>
		<div class="v"><?php echo $this->_vars['data']['address']; ?>
 <?php echo $this->_vars['data']['postal_code'];  if ($this->_vars['data']['id_country'] == "US"): ?>, United States<?php endif; ?></div>
	</div>
	<?php endif; ?>
	
	<?php if ($this->_vars['data']['location']): ?>
	<div class="r">
		<div class="f"><?php echo l('field_region', 'users', '', 'text', array()); ?>:</div>
		<div class="v"><?php echo $this->_vars['data']['location']; ?>
</div>
	</div>
	<?php endif; ?>
	
	<!--<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_strip($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack);  $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "map.html", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
  $this->_tag_stack[] = array('tpl_block_strip', array()); tpl_block_strip(array(), null, $this); ob_start(); ?>-->

<!--
<script>
var latitude = <?php echo $this->_vars['data']['lat']; ?>
;
var longitude = <?php echo $this->_vars['data']['lon']; ?>
;
</script>

<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<div style="overflow:hidden;">
	<div id="gmap_canvas" style="height:450px;width:450px;border:5px solid #cccccc; border-radius:5px;"></div>
</div>

<script type="text/javascript"><?php echo '
function init_map(){
	var myOptions = {zoom:14,center:new google.maps.LatLng(latitude,longitude),
	mapTypeId: google.maps.MapTypeId.ROADMAP};
	map = new google.maps.Map(document.getElementById("gmap_canvas"), myOptions);
	marker = new google.maps.Marker({map: map,position: new google.maps.LatLng(latitude, longitude)});
	infowindow = new google.maps.InfoWindow({content:"<b>Irvine</b><br/>3801 Parkview Ln Apt 17C<br/> Irvine" });
	google.maps.event.addListener(marker, "click", function(){infowindow.open(map,marker);});
	infowindow.open(map,marker);}
</script>'; ?>

-->
	
</div>

<?php if (is_array($this->_vars['sections']) and count((array)$this->_vars['sections'])): foreach ((array)$this->_vars['sections'] as $this->_vars['item']): ?>
	
	<?php $this->_tag_stack[] = array('tpl_block_capture', array('assign' => 'view_fields')); tpl_block_capture(array('assign' => 'view_fields'), null, $this); ob_start(); ?>
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. "users". $this->module_templates.  $this->get_current_theme_gid('', '"users"'). "custom_view_fields.tpl", array('fields_data' => $this->_vars['item']['fields'],'load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
	<?php $this->_block_content = ob_get_contents(); ob_end_clean(); $this->_block_content = tpl_block_capture($this->_tag_stack[count($this->_tag_stack) - 1][1], $this->_block_content, $this); echo $this->_block_content; array_pop($this->_tag_stack); ?>
	<?php if ($this->_run_modifier($this->_vars['view_fields'], 'trim', 'PHP', 1)): ?>
		<h2 class="line top bottom linked">
			<?php echo $this->_vars['item']['name']; ?>

		</h2>
		<div class="view-section"><?php echo $this->_vars['view_fields']; ?>
</div>
	<?php endif; ?>
	
<?php endforeach; endif; ?>



