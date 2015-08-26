{include file="header.tpl" load_type='ui'}
<div class="lef">
	<div class="edit-form n100">
		<div class="row header">{l i='admin_header_pages_in_group' gid='banners'} : {$group_data.name}</div>
		<div class="row zebra">
			<ol id="group_pages" class="connectSort">
				{foreach item=item key=key from=$group_pages}
				<li id="group_pages_{$key}"><b class="name">{$item.name}</b><br><i>{$site_url}<span class="link">{$item.link}</span></i></li>
				{/foreach}
			</ol>
		</div>
	</div>
	<div class="btn"><div class="l"><input type="button" name="btn_save" value="{l i='btn_save' gid='start' type='button'}" onclick="javascript: save_pages();"></div></div>
	<a class="cancel" href="{$site_url}admin/banners/groups_list">{l i='btn_cancel' gid='start'}</a>
</div>
<div class="ref">
	<div class="edit-form">
		<div class="row header">{l i='admin_header_free_pages' gid='banners'}</div>

		<div class="row">&nbsp;
			<select onchange="javascript: load_pages(this.value);">
			<option value="0">...</option>
			{foreach item=item from=$modules}<option value="{$item.id}">{$item.module_name} ({$item.module_data.module_name})</option>{/foreach}
			</select>
		</div>

		<div class="row zebra">
			<ol id="module_pages" class="connectSort">
				<br><br>
			</ol>
		</div>
	</div>
</div>
<div class="clr"></div>

<script type="text/javascript">
var url = '{$site_url}admin/banners/ajax_get_modules_pages/';
var save_url = '{$site_url}admin/banners/ajax_save_group_pages/{$group_data.id}';
var return_url = '{$site_url}admin/banners/groups_list';
{literal}
$(function(){
	$("#group_pages, #module_pages").sortable({
		connectWith: '.connectSort',
		placeholder: 'limiter',
		scroll: true,
		forcePlaceholderSize: true
	}).disableSelection();
});
function load_pages(val){
	if(val == 0){
		$("#module_pages").html();
		return;
	}

	$.ajax({
		url: url + val, 
		cache: false,
		success: function(data){
			$("#module_pages").html(data);
			$("#group_pages").sortable({
				connectWith: '.connectSort',
				scroll: true,
				forcePlaceholderSize: true
			}).disableSelection();
			$("#module_pages").sortable({
				connectWith: '.connectSort',
				items: 'li.sortable',
				scroll: true,
				forcePlaceholderSize: true
			}).disableSelection();
		}
	});

}

function save_pages(){
	var data = new Object();
	$("#group_pages li").each(function(i){
		var id = $(this).attr('id');
		data[i] = {
			name: $('#'+id+' .name').html(),
			link: $('#'+id+' .link').html()
		};
	});
	$.ajax({
		url: save_url, 
		type: 'POST',
		data: {pages: data}, 
		cache: false,
		success: function(data){
			location.href = return_url;
		}
	});
}
{/literal}
</script>
{include file="footer.tpl"}