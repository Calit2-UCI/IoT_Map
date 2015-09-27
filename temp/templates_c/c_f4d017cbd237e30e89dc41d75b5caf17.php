<?php
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\function.block.php');
$this->register_function("block", "tpl_function_block"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\modifier.escape.php');
$this->register_modifier("escape", "tpl_modifier_escape"); 
require_once('C:\xampp\htdocs\iot.calit2.uci.edu\system\libraries\template_lite\plugins\compiler.l.php');
$this->register_compiler("l", "tpl_compiler_l");  /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2015-09-26 10:59:30 Pacific Daylight Time */ ?>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "header.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
<script><?php echo '
	function get_type_data(type){
		$(\'#default_view_type option\').removeAttr(\'selected\');
		$(\'#default_view_type option[value=\'+type+\']\').attr(\'selected\', \'selected\');
	}
	function get_zoom_data(zoom){
		$(\'#default_zoom\').val(zoom);
	}
	function get_drag_data(point_gid, lat, lon){
		$(\'#default_lat\').val(lat);
		$(\'#default_lon\').val(lon);
	}

	$(function(){
		$("#default_lat").bind(\'keyup\', function(){
			map.moveMarker(\'general\', $("#default_lat").val(), $("#default_lon").val());
		});
		$("#default_lon").bind(\'keyup\', function(){
			map.moveMarker(\'general\', $("#default_lat").val(), $("#default_lon").val());
		});
		
		$("#default_zoom").bind(\'keyup\', function(){
			map.setZoom(parseInt($(this).val()));
		});
		$("#default_view_type").bind(\'change\', function(){
			map.setType(parseInt($(this).val())-1);
		});
	});
'; ?>
</script>
<form method="post" action="<?php echo $this->_vars['data']['action']; ?>
" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header"><?php echo l('admin_header_geomap_settings_change', 'geomap', '', 'text', array()); ?></div>
		<div class="row">
			<div class="h"><?php echo l('field_driver', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v"><?php echo $this->_vars['driver_data']['name']; ?>
</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_map_gid', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v">
				<select id="map_selector" class="middle">
					<?php if (is_array($this->_vars['maps']) and count((array)$this->_vars['maps'])): foreach ((array)$this->_vars['maps'] as $this->_vars['item']): ?>
					<option value="<?php echo $this->_vars['item']['gid']; ?>
" <?php if ($this->_vars['item']['gid'] == $this->_vars['map_gid']): ?>selected<?php endif; ?>><?php echo l('map_'.$this->_vars['item']['gid'], 'geomap', '', 'text', array()); ?></option>
					<?php endforeach; endif; ?>
					<option value="" <?php if (! $this->_vars['map_gid']): ?>selected<?php endif; ?>><?php echo l('map_default', 'geomap', '', 'text', array()); ?></option>
				</select>
				<script><?php echo '
					$(function(){
						$(\'#map_selector\').bind(\'change\', function(){
							var value = $(this).val();
							location.href = \'';  echo $this->_vars['site_url']; ?>
admin/geomap/settings/<?php echo $this->_vars['driver_data']['gid'];  echo '\'+(value ? \'/\'+value : \'\');
						});
					});
				'; ?>
</script>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_view_type', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v">
				<select name="data[view_type]" id="default_view_type" class="middle">
					<?php if (is_array($this->_vars['lang_view_type']['option']) and count((array)$this->_vars['lang_view_type']['option'])): foreach ((array)$this->_vars['lang_view_type']['option'] as $this->_vars['key'] => $this->_vars['item']): ?>
					<option value="<?php echo $this->_run_modifier($this->_vars['key'], 'escape', 'plugin', 1); ?>
" <?php if ($this->_vars['key'] == $this->_vars['data']['view_type']): ?>selected<?php endif; ?>><?php echo $this->_vars['item']; ?>
</option>
					<?php endforeach; endif; ?>
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_default_zoom', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" class="short" name="data[zoom]" id='default_zoom' value="<?php echo $this->_run_modifier($this->_vars['data']['zoom'], 'escape', 'plugin', 1); ?>
"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_default_lat', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="data[lat]" id='default_lat' value="<?php echo $this->_run_modifier($this->_vars['data']['lat'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_default_lon', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v"><input type="text" name="data[lon]" id='default_lon' value="<?php echo $this->_run_modifier($this->_vars['data']['lon'], 'escape', 'plugin', 1); ?>
" class="middle"></div>
		</div>
		<div class="row">
			<div class="h"><?php echo l('field_marker_icon', 'geomap', '', 'text', array()); ?>: </div>
			<div class="v">
				<input type="file" name="marker_icon" id='marker_icon'>
				<?php if ($this->_vars['data']['marker_icon'] || $this->_vars['data']['icon']): ?>
				<br><input type="checkbox" name="marker_icon_delete" value="1" id="uichb"><label for="uichb"><?php echo l('field_marker_icon_delete', 'geomap', '', 'text', array()); ?></label><br>
				<?php endif; ?>
			</div>
		</div>
		<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->module_path. geomap. $this->module_templates.  $this->get_current_theme_gid('', 'geomap'). "edit_settings_".$this->_vars['gid'].".tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
		<div class="row"><?php echo tpl_function_block(array('name' => show_map,'module' => geomap,'map_gid' => $this->_vars['gid'],'gid' => $this->_vars['map_gid'],'markers' => $this->_vars['markers'],'settings' => $this->_vars['view_settings'],'map_id' => map,'width' => '738','height' => '300'), $this);?></div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="<?php echo l('btn_save', 'start', '', 'button', array()); ?>"></div></div>
	<a class="cancel" href="<?php echo $this->_vars['site_url']; ?>
admin/geomap"><?php echo l('btn_cancel', 'start', '', 'text', array()); ?></a>
</form>
<div class="clr"></div>
<script><?php echo '
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
'; ?>
</script>

<?php $_templatelite_tpl_vars = $this->_vars;
echo $this->_fetch_compile_include( $this->general_path.  $this->get_current_theme_gid('', ''). "footer.tpl", array('load_type' => false));
$this->_vars = $_templatelite_tpl_vars;
unset($_templatelite_tpl_vars);
 ?>
