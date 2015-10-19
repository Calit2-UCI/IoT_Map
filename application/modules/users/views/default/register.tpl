{include file="header.tpl" load_type='ui'}
	<div>
		<h1>{seotag tag='header_text'}</h1>
		<p class="header-comment">{l i='text_register' gid='users'}</p>
		<div class="edit_block">
			<form action="#" method="post" enctype="multipart/form-data">
				<div class="r">
					<div class="f">{l i='field_user_type' gid='users'}:</div>
					<div class="v">
						<select name="user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.user_type} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_looking_user_type' gid='users'}:</div>
					<div class="v">
						<select name="looking_user_type">
							{foreach item=item key=key from=$user_types.option}<option value="{$key}"{if $key eq $data.looking_user_type} selected{/if}>{$item}</option>{/foreach}
						</select>
					</div>
				</div>
				<div class="r">
					<div class="f">{l i='field_email' gid='users'}: </div>
					<div class="v"><input type="text" name="email" value="{$data.email|escape}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_nickname' gid='users'}: </div>
					<div class="v"><input type="text" name="nickname" value="{$data.nickname|escape}"></div>
				</div>
				<div class="r">
					<div class="f">{l i='field_password' gid='users'}: </div>
					<div class="v"><input type="password" name="password" value="{$data.password}"></div>
				</div>
				{if !empty($use_repassword)}
					<div class="r">
						<div class="f">{l i='field_repassword' gid='users'}: </div>
						<div class="v"><input type="password" name="repassword"></div>
					</div>
				{/if}
				<div class="r hide">
					<div class="f">{l i='birth_date' gid='users'}: </div>
					<div class="v"><input type='text' value='{$data.birth_date}' name="birth_date" id="datepicker" maxlength="10"></div>
				</div>
				
				<div class="r">
					<div class="f">Website: </div>
					<div class="v"><input type="text" name="website" value="{$data.website|escape}"></div>
				</div>
				
				<div class="r">
					<div class="f">{l i='field_location' gid='users'}: </div>
					<div class="v">
						{block name='location_select' 
							module='countries' 
							select_type='city' 
							id_country=$data.id_country 
							id_region=$data.id_region 
							id_city=$data.id_city
							var_country_name='id_country'
							var_region_name='id_region'
							var_city_name='id_city'
						}
					</div>
					<input type="hidden" name="lat" value="{$data.lat|escape}" id="lat">
					<input type="hidden" name="lon" value="{$data.lon|escape}" id="lon">
				</div>
				{depends module=geomap}
					{block name=geomap_load_geocoder module='geomap'}
				{/depends}	
				<script>{literal}
					$(function(){
						loadScripts(
							["{/literal}{js module='users' file='users-map.js' return='path'}{literal}"],
							function(){
								users_map = new usersMap({
									siteUrl: site_url,
								});
							},
							['users_map'],
							{async: true}
						);
					});
				{/literal}</script>
				<!--"{helper func_name='get_user_subscriptions_form' module='subscriptions' func_param='register'}"-->
				<!--br--> <!--remove-->
				<div class="r">
					<div class="f">{l i='field_captcha' gid='users'}: </div>
					<div class="v captcha">{$data.captcha_image} <input type="text" name="captcha_confirmation" value="" maxlength="{$data.captcha_word_length}" /></div>
				</div>
				<div class="r">
					<div class="v">
						<input id="confirmation" type='checkbox' value='1' name="confirmation" {if $data.confirmation}checked {/if}/>
						{capture assign='legal_terms_link'}{block name='get_page_link' module='content' page_gid='legal-terms'}{/capture}
						<label for="confirmation">{l i='field_confirmation' gid='users'}</label>
						{if !empty($legal_terms_link)}
							<a href="{$legal_terms_link}">{l i='terms_and_conditions' gid='users'}</a>
						{else}
							{l i='terms_and_conditions' gid='users'}
						{/if}
						<span class="pginfo msg confirmation"></span>
					</div>
				</div>
				<div class="r">
					<div class="f">&nbsp;</div>
					<div class="v"><input type="submit" value="{l i='btn_register' gid='start' type='button'}" name="btn_register"></div>
				</div>
			</form>

			<script type='text/javascript'>{literal}
				$(function(){
					var date_now = new Date();
					var date_min = new Date(date_now.getYear() - {/literal}{$age_max}{literal}, 0, 1);
					var date_max = new Date(date_now.getYear() - {/literal}{$age_min}{literal}, 0, 1);
					var yr =  (date_min.getFullYear()) + ':' + (date_max.getFullYear());
					$( "#datepicker" ).datepicker({
						dateFormat :'yy-mm-dd',
						changeYear: true,
						changeMonth: true,
						yearRange: yr,
						defaultDate: date_max
					});
				});
			</script>{/literal}
                        
                        {helper func_name='incomplete_signup_script' module='incomplete_signup'}
	</div>

		{block name='show_social_networks_like' module='social_networking'}
		{block name='show_social_networks_share' module='social_networking'}
		{block name='show_social_networks_comments' module='social_networking'}
	</div>
{include file="footer.tpl"}
