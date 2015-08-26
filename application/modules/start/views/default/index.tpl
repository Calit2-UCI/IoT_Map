{include file="header.tpl" header_type='index'}
{*start_search_form type='short'*}
{helper func_name='dynamic_blocks_area' module='dynamic_blocks' func_param=$color_scheme}
{block name='show_social_networks_like' module='social_networking'}
{block name='show_social_networks_share' module='social_networking'}
{block name='show_social_networks_comments' module='social_networking'}
{include file="footer.tpl"}