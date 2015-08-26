			</div>
		</div>
		{helper func_name='show_banner_place' module='banners' func_param='bottom-banner'}
		<div class="footer">
			<div class="content">
				{menu gid='user_footer_menu'}
				<div class="copyright">{if $DEMO_MODE}{$demo_copyright}{else}&copy;&nbsp;2000-2015&nbsp;<a href="http://www.pilotgroup.net">PilotGroup.NET</a> Powered by <a href="http://www.datingpro.com/">PG Dating Pro</a>{/if}</div>
			</div>
		</div>
		{helper func_name='lang_editor' module='languages'}
		{helper func_name='seo_traker' helper_name='seo_advanced_helper' module='seo_advanced' func_param='footer'}
		{helper func_name='cookie_policy_block' module='cookie_policy' helper_name='cookie_policy'}
{if empty($is_pjax)}
</div>
</body>
</html>
{/if}
