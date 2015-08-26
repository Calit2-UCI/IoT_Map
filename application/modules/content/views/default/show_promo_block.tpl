{if ($promo.content_type eq 't' && ($promo.promo_image || $promo.promo_text) ) || ($promo.content_type eq 'f' && $promo.promo_flash)}
<div class="promo-block" {if $promo.style_str}style="{$promo.style_str}"{/if}>
{if $promo.content_type eq 't'}<div class="inside">{$promo.promo_text}</div>{/if}
{if $promo.content_type eq 'f'}
<object width="100%" height="100%" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000">
<param value="Always" name="allowScriptAccess">
<param value="{$promo.media.promo_flash.file_url}" name="movie">
<param value="false" name="menu">
<param value="high" name="quality">
<param value="opaque" name="wmode">
<param value="" name="flashvars">
<embed width="100%" height="100%" pluginspage="http://www.macromedia.com/shockwave/download/index.cgi?P1_Prod_Version=ShockwaveFlash" type="application/x-shockwave-flash" swliveconnect="FALSE" menu="false" wmode="opaque" allowscriptaccess="Always" quality="high" flashvars="" src="{$promo.media.promo_flash.file_url}"> 
</object>
{/if}
</div>
{/if}