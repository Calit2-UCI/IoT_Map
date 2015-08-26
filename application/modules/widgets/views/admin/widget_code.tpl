<div class="pg-{$widget.gid}"{if $widget.size} data-size="{$widget.size}"{/if} data-lang="{$current_lang_code}"></div>
<script>{literal}
(function(d, s, id){
	js = d.getElementsByTagName(s)[0];
    var script = d.createElement(s);
    script.id = id;
    script.src = (document.location.protocol == "https:" ? "https:" : "http:") + "//{/literal}{$base_url|regex_replace:'#http(s)?://#i':''}{literal}application/modules/widgets/js/widgets.js";
    js.parentNode.insertBefore(script, js);
})(document, 'script', 'pg-widget');
{/literal}</script>
