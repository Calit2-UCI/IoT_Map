{include file="header.tpl"}

<div class="filter-form">
    <div class="tbl">
        {foreach item=item from=$requirements}
        <div class="row {if $item.result}green{else}red{/if}">
            <div class="value">{$item.value}</div>
            <div class="name">{$item.name}</div>
            <div class="clr"></div>
        </div>
        {/foreach}
    </div>
</div>

{include file="settings-form.tpl" module="network" theme=admin}
<div class="clr"></div>
    
<div class="edit-form network-content n250">
    <div class="row header">
        {l i='admin_client_status' gid='network'}
    </div>
    <div class="row">
        <div class="h activity">
            <div class="net_client_started{if !$clients_started} hide{/if}">
                <font class="net_client_status success">{l i='admin_client_started' gid='network'}</font>
                (<a class="net_client_stop" href="{$site_url}admin/network/stop">{l i='admin_client_stop' gid='network'}</a>)
            </div>
            <div class="net_client_stopped{if $clients_started} hide{/if}">
                <font class="net_client_status error">{l i='admin_client_stopped' gid='network'}</font>
                {if $data_is_correct}
                    (<a class="net_client_stop" href="{$site_url}admin/network/start">{l i='admin_client_start' gid='network'}</a>)
                {else}
                    ({l i='admin_error_data' gid='network'})
                {/if}
            </div>
        </div>
    </div>
    {if $net_show_log}
        <div class="row header">Log</div>
        <div class="row">
            <div class="header">Fast:</div>
            <pre><div class="wp100" id="fast">Empty</div></pre>
            <br>
            <div class="header">Slow:</div>
            <pre><div class="wp100" id="slow">Empty</div></pre>
        </div>
    {/if}
</div>
<script>{literal}
    $(function(){
        new adminNetwork({
            siteUrl: site_url,
            status: Boolean({/literal}{$clients_started}{literal}),
            showLog: Boolean({/literal}{$clients_started && $net_show_log}{literal})
        });
    });
</script>{/literal}

{include file="footer.tpl"}
