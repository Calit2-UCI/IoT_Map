<ul>
{counter start=0 print=false}
{foreach item=item from=$content_tree key=key}
<li{if $item.active} class="active"{/if}><a href="{seolink module='content' method='view' data=$item}">{$item.title}</a></li>
{/foreach}
</ul>
