{include file="header.tpl"}
<script>{literal}
	function get_type_data(type){
		$('#default_view_type option').removeAttr('selected');
		$('#default_view_type option[value='+type+']').attr('selected', 'selected');
	}
	function get_zoom_data(zoom){
		$('#default_zoom').val(zoom);
	}
	function get_drag_data(point_gid, lat, lon){
		$('#default_lat').val(lat);
		$('#default_lon').val(lon);
	}

	$(function(){
		$("#default_lat").bind('keyup', function(){
			map.moveMarker('general', $("#default_lat").val(), $("#default_lon").val());
		});
		$("#default_lon").bind('keyup', function(){
			map.moveMarker('general', $("#default_lat").val(), $("#default_lon").val());
		});
		
		$("#default_zoom").bind('keyup', function(){
			map.setZoom(parseInt($(this).val()));
		});
		$("#default_view_type").bind('change', function(){
			map.setType(parseInt($(this).val())-1);
		});
	});
{/literal}</script>
<form method="post" action="{$data.action}" name="save_form" enctype="multipart/form-data">
	<div class="edit-form n150">
		<div class="row header">{l i='admin_header_geomap_settings_change' gid='geomap'}</div>
		<div class="row">
			<div class="h">{l i='field_driver' gid='geomap'}: </div>
			<div class="v">{$driver_data.name}</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_map_gid' gid='geomap'}: </div>
			<div class="v">
				<select id="map_selector" class="middle">
					{foreach item=item from=$maps}
					<option value="{$item.gid}" {if $item.gid eq $map_gid}selected{/if}>{l i='map_'+$item.gid gid='geomap'}</option>
					{/foreach}
					<option value="" {if !$map_gid}selected{/if}>{l i='map_default' gid='geomap'}</option>
				</select>
				<script>{literal}
					$(function(){
						$('#map_selector').bind('change', function(){
							var value = $(this).val();
							location.href = '{/literal}{$site_url}admin/geomap/settings/{$driver_data.gid}{literal}'+(value ? '/'+value : '');
						});
					});
				{/literal}</script>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_view_type' gid='geomap'}: </div>
			<div class="v">
				<select name="data[view_type]" id="default_view_type" class="middle">
					{foreach item=item key=key from=$lang_view_type.option}
					<option value="{$key|escape}" {if $key eq $data.view_type}selected{/if}>{$item}</option>
					{/foreach}
				</select>
			</div>
		</div>
		<div class="row">
			<div class="h">{l i='field_default_zoom' gid='geomap'}: </div>
			<div class="v"><input type="text" class="short" name="data[zoom]" id='default_zoom' value="{$data.zoom|escape}"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_default_lat' gid='geomap'}: </div>
			<div class="v"><input type="text" name="data[lat]" id='default_lat' value="{$data.lat|escape}" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_default_lon' gid='geomap'}: </div>
			<div class="v"><input type="text" name="data[lon]" id='default_lon' value="{$data.lon|escape}" class="middle"></div>
		</div>
		<div class="row">
			<div class="h">{l i='field_marker_icon' gid='geomap'}: </div>
			<div class="v">
				<input type="file" name="marker_icon" id='marker_icon'>
				{if $data.marker_icon || $data.icon}
				<br><input type="checkbox" name="marker_icon_delete" value="1" id="uichb"><label for="uichb">{l i='field_marker_icon_delete' gid='geomap'}</label><br>
				{/if}
			</div>
		</div>
		{include file="edit_settings_"+$gid+".tpl" module=geomap}
		<div class="row">{block name=show_map module=geomap map_gid=$gid gid=$map_gid markers=$markers settings=$view_settings map_id=map width='738' height='300'}</div>
	</div>
	<div class="btn"><div class="l"><input type="submit" name="btn_save" value="{l i='btn_save' gid='start' type='button'}"></div></div>
	<a class="cancel" href="{$site_url}admin/geomap">{l i='btn_cancel' gid='start'}</a>
</form>
<div class="clr"></div>
<script>{literal}
	$(function(){
		$("div.row:odd").addClass("zebra");
	});
{/literal}</script>

{include file="footer.tpl"}
