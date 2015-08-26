{include file="header.tpl"}
{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_social_networking_menu'}
{if $allow_add}
    <div class="actions">
        <ul>
            <li><div class="l"><a href="{$site_url}admin/social_networking/page_edit">{l i='link_add_page' gid='social_networking'}</a></div></li>
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
        <th class="first">{l i='page_name' gid='social_networking'}</th>
        <th class="w100">{l i='widgets_on_page' gid='social_networking'}</th>
        {if $allow_edit || $allow_delete}
            <th class="w100">{l i='actions' gid='social_networking'}</th>
        {/if}
    </tr>
    {foreach item=item from=$pages}
        {counter print=false assign=counter}
        <tr{if $counter is div by 2} class="zebra"{/if}>
            <td>{$item.name}</td>
            <td class="center"><a href="{$site_url}admin/social_networking/widgets/{$item.id}/">{l i='link_widgets' gid='social_networking'}</a></td>
            {if $allow_edit || $allow_delete}
                <td class="icons">
                    {if $allow_edit}
                        <a href="{$site_url}admin/social_networking/page_edit/{$item.id}"><img src="{$site_root}{$img_folder}icon-edit.png" width="16" height="16" border="0" alt="{l i='link_edit_page' gid='social_networking'}" title="{l i='link_edit_page' gid='social_networking'}"></a>
                        {/if}
                        {if $allow_delete}
                        <a href="{$site_url}admin/social_networking/page_delete/{$item.id}" onclick="javascript: if(!confirm('{l i='note_delete_page' gid='social_networking' type='js'}')) return false;"><img src="{$site_root}{$img_folder}icon-delete.png" width="16" height="16" border="0" alt="{l i='link_delete_page' gid='social_networking'}" title="{l i='link_delete_page' gid='social_networking'}"></a>
                        {/if}
                </td>
            {/if}
        </tr>
    {foreachelse}
        <tr><td class="center zebra" colspan=4>{l i='no_pages' gid='social_networking'}</td></tr>
    {/foreach}
</table>

{include file="footer.tpl"}
