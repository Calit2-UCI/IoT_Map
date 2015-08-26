{include file="header.tpl" load_type='ui'}

{js file='admin-multilevel-sorter.js'}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_kisses_menu'}

<div class="actions">
	<ul>
		<li><div class="l"><a href="{$site_url}admin/kisses/add/" id="btn_add" />{l i='btn_add' gid='kisses'}</a></div></li>
		<li><div class="l"><a id="delete_select_block" href="javascript:void(0)">{l i='btn_link_delete' gid='kisses'}</a></div></li>
		<!--li><div class="l"><a href="#" onclick="javascript: mlSorter.update_sorting(); return false">{l i='link_save_sorter' gid='kisses'}</a></div></li-->
		<!--li><div class="l"><a href="{$site_url}admin/kisses/index/">{l i='sort_order' gid='kisses'}</a></div></li-->
	</ul>
	&nbsp;
</div>
{strip}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
	<tr>
		<th class="first w50"><input type="checkbox" id="grouping_all"></th>
		<th class="">{l i='images' gid='kisses'}</th>
		<!--th>
			<a href="{$sort_links.date_created}"{if $order eq 'date_created'} class="{$order_direction|lower}"{/if}>
				{l i='field_date_created' gid='kisses'}
			</a>
		</th-->
		<th class="w30">&nbsp;</th>
	</tr>
</table>

<div id="pages">
	
	<ul name="parent_0" class="sort connected" id="clsr0ul">
		{foreach from=$kisses item=kiss}
		{counter print=false assign=counter}
			<li id="item_{$kiss.id}">
				<div><input type="checkbox" class="grouping" value="{$kiss.id}" name="ids[]" id="kisses-{$kiss.id}"></div>
				<div><img src="{$file_url}{$kiss.image|escape}" alt="{$kiss.id}"></div>
				<!--div>{$kiss.date_created}</div-->
				<div class="icons">
					<!--a href="{$site_url}admin/kisses/edit/{$kiss.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_kisses' gid='kisses'}" title="{l i='link_edit_kisses' gid='kisses'}"></a-->
					<a class="delete_select_file" data-id="{$kiss.id}" href="javascript:void(0)"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_kisses' gid='kisses'}" title="{l i='link_delete_kisses' gid='kisses'}"></a>
				</div>
			</li>
		{foreachelse}
			<li>{l i='no_kisses' gid='kisses'}</li>
		{/foreach}
	</ul>
	
</div>
{/strip}

{include file="pagination.tpl"}

<script type="text/javascript">

var reload_link = "{$site_url}admin/kisses/index/";
var order = '{$order}';
var order_direction = '{$order_direction}';
var mlSorter;

{literal}
	$(function(){
		$('#grouping_all').bind('click', function(){
			var checked = $(this).is(':checked');
			if(checked){
				$('input.grouping').prop('checked', true);
			}else{
				$('input.grouping').prop('checked', false);
			}
		});
		$('#delete_selected').bind('click', function(){
			if(!$('input[type=checkbox].grouping').is(':checked')) return false; 
			if(!confirm('{/literal}{l i='note_alerts_delete_all' gid='kisses' type='js'}{literal}')) return false;
			$('#kisses_form').attr('action', $(this).attr('href')).submit();		
			return false;
		});
		mlSorter = new multilevelSorter({
			siteUrl: '{/literal}{$site_url}{literal}', 
			itemsBlockID: 'pages',
			urlSaveSort: 'admin/kisses/ajax_save_sorter',
			onActionUpdate: true,
		});
	});
	
	delete_select_block = new loadingContent({
		loadBlockWidth: '620px',
		loadBlockLeftType: 'center',
		loadBlockTopType: 'center',
		loadBlockTopPoint: 100,
		closeBtnClass: 'w'
	}).update_css_styles({'z-index': 2000}).update_css_styles({'z-index': 2000}, 'bg');
	
	$('.delete_select_file').unbind('click').click(function(){
		var id_kisses = $(this).attr('data-id');
		var data = new Array();
		
		var checked = $('input#kisses-'+id_kisses).is(':checked');
		if(checked){
			$('input#kisses-'+id_kisses).prop('checked', false);
			$('input#kisses-'+id_kisses).prop('checked', true);
		}else{
			$('input#kisses-'+id_kisses).prop('checked', true);
		}
			
			data[0] = id_kisses;
			
			if(data.length > 0){
				$.ajax({
					url: site_url + 'admin/kisses/ajax_confirm_delete_select',
					cache: false,
					success: function(data){
						delete_select_block.show_load_block(data);
					}
				});
			}else{
				error_object.show_error_block('{/literal}{l i="no_select_kisses" gid="kisses" type="js"}{literal}', 'error');
			}
		
	});
	
	$('#delete_select_block').unbind('click').click(function(){
		var data = new Array();
		
		$('.grouping:checked').each(function(i){
			data[i] = $(this).val();
		});
		if(data.length > 0){
			$.ajax({
				url: site_url + 'admin/kisses/ajax_confirm_delete_select',
				cache: false,
				success: function(data){
					delete_select_block.show_load_block(data);
				}
			});
		}else{
			error_object.show_error_block('{/literal}{l i="no_select_kisses" gid="kisses" type="js"}{literal}', 'error');
		}
	});
	
function reload_this_page(value){
	var link = reload_link + '/' + order + '/' + order_direction;
	location.href=link;
}
{/literal}</script>

{include file="footer.tpl"}

