					<ul id="select_options">
					{foreach item=item key=key from=$reference_data.option}
					<li id="option_{$key}">{$item|default:"&nbsp;"}
						<div class="icons">
							<a href="#" class="active_link hide"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" alt="{l i='link_default_option' gid='field_editor'}" title="{l i='link_default_option' gid='field_editor'}"></a>
							<a href="#" class="deactive_link hide"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" alt="{l i='link_default_option' gid='field_editor'}" title="{l i='link_default_option' gid='field_editor'}"></a>
							<a href="#" class="edit_link"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_option' gid='field_editor'}" title="{l i='link_edit_option' gid='field_editor'}"></a>
							<a href="#" class="delete_link"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_option' gid='field_editor'}" title="{l i='link_delete_option' gid='field_editor'}"></a>
						</div>
					</li>
					{/foreach}
					</ul>
