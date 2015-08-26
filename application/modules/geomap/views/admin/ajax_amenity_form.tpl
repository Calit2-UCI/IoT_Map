<div class="load_content_controller">
	<h1>{l i='header_amenity_select' gid='geomap'}</h1>
	<div class="inside">
		<ul class="controller-items" id="amenity_select_items"></ul>
	
		<div class="controller-actions">
			<input type="button" id="amenity_close_link" name="close_btn" value="{l i='btn_close' gid='start' type='button'}" class="fleft">
			{if $data.max > 1}
			<a href="#" id="amenity_select_back" class="btn-link link-margin"><ins class="with-icon i-delete no-hover"></ins>{l i='link_reset_all' gid='geomap'}</a>
			{/if}
		</div>
		{if $data.max > 1}
		<div class=" line top">{l i='text_availbale_select_amenities' gid='geomap'} <span id="amenity_max_left_block"></span></div>
		{/if}
	</div>
</div>
