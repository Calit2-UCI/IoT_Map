{if $auth_type eq 'module'}
	<div class="menu">
		<div class="t">
			<div class="b">
				<ul>
					<li{if $step eq 'modules'} class="active"{/if}><div class="r"><a href="{$site_url}admin/install/modules">Installed modules</a></div></li>
					<li{if $step eq 'enable_modules'} class="active"{/if}><div class="r">
						<a href="{$site_url}admin/install/enable_modules">Enable modules{if $enabled && $step ne 'enable_modules'}<span class="num">{$enabled}</span>{/if}</a>
					</div></li>
					<li{if $step eq 'updates'} class="active"{/if}><div class="r">
						<a href="{$site_url}admin/install/updates">Enable updates{if $updates && $step ne 'updates'}<span class="num">{$updates}</span>{/if}</a>
					</div></li>
					<li{if $step eq 'product_updates'} class="active"{/if}><div class="r">
						<a href="{$site_url}admin/install/product_updates">Enable product updates{if $product_updates && $step ne 'product_updates'}<span class="num">{$product_updates}</span>{/if}</a>
					</div></li>
					<li{if $step eq 'libraries'} class="active"{/if}><div class="r"><a href="{$site_url}admin/install/libraries">Installed libraries</a></div></li>
					<li{if $step eq 'enable_libraries'} class="active"{/if}><div class="r">
						<a href="{$site_url}admin/install/enable_libraries">Enable libraries{if $enabled_libraries && $step ne 'enable_libraries'}<span class="num">{$enabled_libraries}</span>{/if}</a>
					</div></li>
					<li{if $step eq 'langs'} class="active"{/if}><div class="r">
						<a href="{$site_url}admin/install/langs">Languages</a>
					</div></li>
					<li{if $step eq 'ftp'} class="active"{/if}><div class="r"><a href="{$site_url}admin/install/installer_settings">Panel settings</a></div></li>
				</ul>
			</div>
		</div>
	</div>
{/if}