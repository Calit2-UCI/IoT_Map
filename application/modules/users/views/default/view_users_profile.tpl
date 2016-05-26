<h2 class="line top bottom linked">
	{l i='table_header_personal' gid='users'}
</h2>
<div class="view-section">
	{l i='no_information' gid='users' assign='no_info_str'}
	
	{if $data.fname}
		<div class="r">
			<div class="f">{l i='field_fname' gid='users'}:</div>
			<div class="v">{$data.fname}</div>
		</div>
	{/if}
	
	<div class="r">
		<div class="f">{l i='field_user_type' gid='users'}:</div>
		<div class="v">{$data.user_type_str}</div>
	</div>
	{if $data.looking_user_type_str}
	<div class="r hide">
		<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
		<div class="v">{$data.looking_user_type_str}</div>
	</div>
	{/if}
	{if $data.age_min}
	<div class="r hide">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='from' gid='users'}:</div>
		<div class="v">{$data.age_min}</div>
	</div>
	{/if}
	{if $data.age_max}
	<div class="r hide">
		<div class="f">{l i='field_partner_age' gid='users'} {l i='to' gid='users'}:</div>
		<div class="v">{$data.age_max}</div>
	</div>
	{/if}
	<div class="r hide">
		<div class="f">{l i='field_nickname' gid='users'}:</div>
		<div class="v">{$data.nickname}</div>
	</div>

	{if $data.sname}
		<div class="r hide">
			<div class="f">{l i='field_sname' gid='users'}:</div>
			<div class="v">{$data.sname}</div>
		</div>
	{/if}
	<div class="r hide">
		<div class="f">{l i='birth_date' gid='users'}:</div>
		<div class="v">{$data.birth_date}</div>
	</div>
	
	<div class="r hide">
		<div class="f">Website:</div>
		<div class="v">{$data.website}</div>
	</div>
	
	{if $data.address}
	<div class="r">
		<div class="f">Address:</div>
		<div class="v">{$data.address}</div>   <!--{if $data.id_country == "US"}, United States{/if}</div>-->
	</div>
	{/if}
	
	{if $data.location}
	<div class="r hide">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{$data.location}</div>
	</div>
	{/if}
	
	{if $data.location}
	<div class="r">
		<div class="f">{l i='field_region' gid='users'}:</div>
		<div class="v">{$data.city}, {$data.region} {$data.postal_code}</div>
	</div>
	{/if}
	
	{if $data.postal_code}
	<div class="r hide">
		<div class="f">Zip code:</div>
		<div class="v">{$data.postal_code}</div>
	</div>
	{/if}
	
	
	<!--{/strip}{include file="map.html" module="users"}{strip}-->

	
</div>

{foreach item=item from=$sections}
	
	{capture assign='view_fields'}
		{include file="custom_view_fields.tpl" fields_data=$item.fields module="users"}
	{/capture}
	{if $view_fields|trim}
		<h2 class="line top bottom linked">
			{$item.name}
		</h2>
		<div class="view-section">{$view_fields}</div>
	{/if}
	
{/foreach}



