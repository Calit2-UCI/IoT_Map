{include file="header.tpl"}
<script>{literal}
	function get_drag_data(point_gid, lat, lon){
		$('#default_lat').val(lat);
		$('#default_lon').val(lon);
	}
	function test1(t){
		console.log(t);
	}
	function test2(t){
		console.log(t);
	}
	function test3(t, t1, t2){
		console.log(t);
		console.log(t1);
		console.log(t2);
		
	}
{/literal}</script>

<div class="lc">
	{helper func_name='get_content_tree' helper_name='content' func_param=$page.id}
</div>
<div class="rc">
	<div class="edit-block">
		<h1>{l i='map_example_header' gid='geomap'}</h1>
		{block name='show_default_map' module='geomap' map_gid=$map_gid id_user=$user_id markers=$markers settings=$map_settings}
	</div>
</div>
<div class="clr"></div>
{include file="footer.tpl"}
