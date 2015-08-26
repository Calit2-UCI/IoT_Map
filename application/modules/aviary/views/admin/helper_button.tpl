<!-- Load widget code -->
{if preg_match('#^https://#i', $site_url)}
<script type="text/javascript" src="https://dme0ih8comzn4.cloudfront.net/js/feather.js"></script>
{else}
<script type="text/javascript" src="http://feather.aviary.com/js/feather.js"></script>
{/if}

<!-- Instantiate the widget -->
<script type="text/javascript">{literal}
	if(typeof(featherEditor{/literal}{$avaiary_button_rand}{literal}) === 'undefined'){
		try{
			var featherEditor{/literal}{$avaiary_button_rand}{literal} = new Aviary.Feather({
				apiKey: '{/literal}{$aviary_api_key}{literal}',
				apiVersion: 3,
				{/literal}{if $aviary_lang_code}language: '{$aviary_lang_code}',{/if}{literal}
				onLoad: function(){
					$('#btn_aviary_edit{/literal}{$avaiary_button_rand}{literal}').prop('disabled', false);
				},
				onSave: function(imageID, newURL) {
					new {/literal}{$aviary_save_callback}{literal}(imageID, newURL);
									
					featherEditor{/literal}{$avaiary_button_rand}{literal}.close();
				},
				onError: function(errorObj) {
					var error_obj = new Errors();
					error_obj.show_error_block(errorObj.message, 'error');
				},
				postUrl: '{/literal}{$site_url}aviary/save{literal}',
				postData: {
					module: '{/literal}{$aviary_module}{literal}', 
					data: {/literal}{$aviary_post_data}{literal},
					code: '{/literal}{$aviary_code}{literal}'
				},
			});	
		}catch(e){
			$('#m_aviary').hide();
		}
	}else{
		$('#btn_aviary_edit{/literal}{$avaiary_button_rand}{literal}').prop('disabled', false);
	}
					
	if(typeof(launchEditor{/literal}{$avaiary_button_rand}{literal}) === 'undefined'){
		function launchEditor{/literal}{$avaiary_button_rand}{literal}(id, src) {
			featherEditor{/literal}{$avaiary_button_rand}{literal}.launch({
				image: id,
				url: src
			});
			return false;
		}
	}
{/literal}</script>                         

<!-- Add an edit button, passing the HTML id of the image
	and the public URL to the image -->
<div class="btn"><div class="l">
	<input type="button" id="btn_aviary_edit{$avaiary_button_rand}"
		   value="{l i='btn_aviary' gid='aviary' type='button'}" name="btn_aviary_edit" 
		   onclick="return launchEditor{$avaiary_button_rand}('{$aviary_photo_id}', {$aviary_photo_source});">
</div></div>
