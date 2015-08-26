{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_social_networking_menu'}
{if $allow_add}
    <div class="actions">
        <ul>
            <li><div class="l"><a href="{$site_url}admin/social_networking/service_edit">{l i='link_add_service' gid='social_networking'}</a></div></li>
        </ul>
        &nbsp;
    </div>
{else}
    <div class="actions">
        <ul>

        </ul>
        &nbsp;
    </div>
{/if}
<table cellspacing="0" cellpadding="0" class="data" width="100%">
    <tr>
        <th class="first">{l i='field_name' gid='social_networking'}</th>
        <th class="w100">{l i='field_oauth' gid='social_networking'}</th>
        <th class="w100">{l i='field_active' gid='social_networking'}</th>
		<th class="w100"></th>
        {if $allow_edit || $allow_delete}
            <th class="w100">{l i='actions' gid='social_networking'}</th>
        {/if}
    </tr>
    {foreach item=item from=$services}
        {counter print=false assign=counter}
        <tr{if $counter is div by 2} class="zebra"{/if}>
            <td>{$item.name}</td>
            
            <td class="center">
                {if $item.oauth_enabled}
                    {if $item.oauth_status}
                        <a href="{$site_url}admin/social_networking/oauth_active/{$item.id}/0" title="{l i='note_disable_login' gid='social_networking'}"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" ></a>
                        {else}
                        <a href="{$site_url}admin/social_networking/oauth_active/{$item.id}/1" title="{l i='note_enable_login' gid='social_networking'}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" ></a>
                        {/if}
                    {/if}
            </td>
            <td class="center">
                {if $item.status}
                    <a href="{$site_url}admin/social_networking/service_active/{$item.id}/0" title="{l i='note_disable_widget' gid='social_networking'}"><img src="{$site_root}{$img_folder}icon-full.png" width="16" height="16" border="0" ></a>
                    {else}
                    <a href="{$site_url}admin/social_networking/service_active/{$item.id}/1" title="{l i='note_enable_widget' gid='social_networking'}"><img src="{$site_root}{$img_folder}icon-empty.png" width="16" height="16" border="0" ></a>
                    {/if}
            </td>
			<td class="icons">
				{if $item.app_enabled}
					<a href="{$site_url}admin/social_networking/application/{$item.id}/"><img width="16" height="16" border="0" title="{l i='link_application' gid='social_networking'}" alt="{l i='link_application' gid='social_networking'}" src="{$site_root}{$img_folder}icon-edit.png"></a>
				{/if}
			</td>
            {if $allow_edit || $allow_delete}
                <td class="icons">
                    {if $allow_edit}
                        <a href="{$site_url}admin/social_networking/service_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_service' gid='social_networking'}" title="{l i='link_edit_service' gid='social_networking'}"></a>
                        {/if}
                        {if $allow_delete}
                        <a href="{$site_url}admin/social_networking/service_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_service' gid='social_networking' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_service' gid='social_networking'}" title="{l i='link_delete_service' gid='social_networking'}"></a>
                        {/if}
                </td>
            {/if}
        </tr>
    {foreachelse}
        <tr><td class="center zebra" colspan=4>{l i='no_services' gid='social_networking'}</td></tr>
    {/foreach}
</table>

{include file="footer.tpl"}
