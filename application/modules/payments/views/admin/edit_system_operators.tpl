				<ul id="operators_sorting">
					{foreach item=item key=key from=$system_data.operators_data}
					<li id="operator_{$key}">{l i='field_system_tarifs' gid='payments'} ({$item|default:"&nbsp;"}, {block name=currency_output module=start}): 
						<div class="icons">
							{if $system_data.tarifs_status[$key] > 0}
							<a href="#" class="deactivate_link"><img src="{$site_root}{$img_folder}icon-full.png" alt="{l i='link_system_tarif_deactivate' gid='payments' type='button'}" title="{l i='link_system_tarif_deactivate' gid='payments' type='button'}"></a>
							{else}
							<a href="#" class="activate_link"><img src="{$site_root}{$img_folder}icon-empty.png" alt="{l i='link_system_tarif_activate' gid='payments' type='button'}" title="{l i='link_system_tarif_activate' gid='payments' type='button'}"></a>
							{/if}
							<a href="#" class="add_link"><img src="{$site_root}{$img_folder}icon-add.png" alt="{l i='link_system_tarif_add_new' gid='payments' type='button'}" title="{l i='link_system_tarif_add_new' gid='payments' type='button'}"></a>
							<a href="#" class="edit_link"><img src="{$site_root}{$img_folder}icon-edit.png" alt="{l i='link_system_tarif_edit' gid='payments' type='button'}" title="{l i='link_system_tarif_edit' gid='payments' type='button'}"></a>
							<a href="#" class="delete_link"><img src="{$site_root}{$img_folder}icon-delete.png" alt="{l i='link_system_tarif_delete' gid='payments' type='button'}" title="{l i='link_system_tarif_delete' gid='payments' type='button'}"></a>
						</div>
						<div class="tarifs" id="{$key}_tarifs_block">
							{foreach item=tarif_item key=tarif_key from=$system_data.tarifs_data[$key]}
							<div class="tarif_row">
								<input type="text" name="tarifs_data[{$key}][]" value="{$tarif_item|escape}">
								<a href="#" class="remove_link"><img src="{$site_root}{$img_folder}icon-delete.png" alt="{l i='link_system_tarif_delete' gid='payments' type='button'}" title="{l i='link_system_tarif_delete' gid='payments' type='button'}"></a>
							</div>
							{foreachelse}
							{for start=0 stop=2}
							<div class="tarif_row">
								<input type="text" name="tarifs_data[{$key}][]" value="">
								<a href="#" class="remove_link"><img src="{$site_root}{$img_folder}icon-delete.png" alt="{l i='link_system_tarif_delete' gid='payments' type='button'}" title="{l i='link_system_tarif_delete' gid='payments' type='button'}"></a>
							</div>
							{/for}
							{/foreach}
							<div class="tarif_row">
								<input type="text" name="tarifs_data[{$key}][]" value="">
								<a href="#" class="remove_link"><img src="{$site_root}{$img_folder}icon-delete.png" alt="{l i='link_system_tarif_delete' gid='payments' type='button'}" title="{l i='link_system_tarif_delete' gid='payments' type='button'}"></a>
							</div>
						</div>
						<div id="{$key}_tarif_block" class="hide">
							<div class="tarif_row">
								<input type="text" name="tarifs_data[{$key}][]" value="">
								<a href="#" class="remove_link"><img src="{$site_root}{$img_folder}icon-delete.png" alt="{l i='link_system_tarif_delete' gid='payments' type='button'}" title="{l i='link_system_tarif_delete' gid='payments' type='button'}"></a>
							</div>
						</div>
					</li>
					{/foreach}
				</ul>
				
				
