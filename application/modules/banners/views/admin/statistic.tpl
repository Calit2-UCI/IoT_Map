{include file="header.tpl"}

{helper func_name='get_admin_level1_menu' helper_name='menu' func_param='admin_banners_menu'}
<div class="actions">&nbsp;</div>

<div class='menu-level3'>
	<ul>
		<li{if $stat_type eq 'day'} class="active"{/if}><a href="{$site_url}admin/banners/statistic/{$banner_data.id}/day">{l i='stat_day' gid='banners'}</a></li>
		<li{if $stat_type eq 'week'} class="active"{/if}><a href="{$site_url}admin/banners/statistic/{$banner_data.id}/week">{l i='stat_week' gid='banners'}</a></li>
		<li{if $stat_type eq 'month'} class="active"{/if}><a href="{$site_url}admin/banners/statistic/{$banner_data.id}/month">{l i='stat_month' gid='banners'}</a></li>
		<li{if $stat_type eq 'year'} class="active"{/if}><a href="{$site_url}admin/banners/statistic/{$banner_data.id}/year">{l i='stat_year' gid='banners'}</a></li>
	</ul>
	&nbsp;
</div>

<link rel="stylesheet" type="text/css" href="{$site_root}application/modules/banners/js/jqplot/jquery.jqplot.min.css" />
<!--[if lt IE 9]{js module='banners' file='jqplot/excanvas.min.js'}<![endif]-->
{js module='banners' file='jqplot/jquery.jqplot.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.canvasTextRenderer.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.canvasAxisLabelRenderer.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.highlighter.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.cursor.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.dateAxisRenderer.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.canvasAxisTickRenderer.min.js'}
{js module='banners' file='jqplot/plugins/jqplot.categoryAxisRenderer.min.js'}

{if $stat_type eq 'day'}
<div class="filter-form">
	<div class="row">
		<div class="h">{l i='stat_day' gid='banners'}:</div>
		<div class="v"><a href="{$navigation.prev}">{l i="nav_prev" gid='start'}</a> <b>{$navigation.current}</b> <a href="{$navigation.next}">{l i="nav_next" gid='start'}</a></div>
	</div>
	<div class="row" id="view_links">
		<div class="h">{l i='stat_view_type' gid='banners'}:</div>
		<div class="v"><a href="#" class="active">{l i='stat_by_hours' gid='banners'}</a></div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_views' gid='banners'}:</div>
		<div class="v">{$statistic.all.view}</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_clicks' gid='banners'}:</div>
		<div class="v">{$statistic.all.click}</div>
	</div>
</div>


<div id="view_blocks">
	<div id="by_hours_div">
		<div id="jplot_div"></div>
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_hour' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=hour from=$statistic.hour}
		<tr class="stat">
			<td class="first hour">{$hour}</td>
			<td class="center views">{$item.view}</td>
			<td class="center clicks">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
	<script type='text/javascript'>{literal}
	$(document).ready(function(){
		var viewPoints = [];
		var clicksPoints = [];
		var ticks = [];
		$("#by_hours_div tr.stat").each(function(){
			var hour = parseInt($(this).find('td.hour').text());
			var views = parseInt($(this).find('td.views').text());
			var clicks = parseInt($(this).find('td.clicks').text());
			viewPoints.push([hour, views]);
			clicksPoints.push([hour, clicks]);
			ticks.push(hour);
		});

		var plot2 = $.jqplot ('jplot_div', [viewPoints, clicksPoints], {
			axesDefaults: { labelRenderer: $.jqplot.CanvasAxisLabelRenderer },
			seriesDefaults:{ renderer:$.jqplot.BarRenderer, rendererOptions: {fillToZero: true} },
			legend: { show: true, placement: 'insideGrid' },
			series:[
				{ lineWidth:5, markerOptions: { style:'dimaond' }, label: "{/literal}{l i='stat_views' gid='banners' type='js'}{literal}" },
				{ lineWidth:2, markerOptions: { style:"filledSquare", size:10 }, label: "{/literal}{l i='stat_clicks' gid='banners' type='js'}{literal}" }
			],
			axes: { xaxis: { label: "{/literal}{l i='stat_hour' gid='banners' type='js'}{literal}", tickOptions: {formatString: '%d h'}, ticks: ticks } },
			highlighter: { show: true, sizeAdjust: 7.5 },
			cursor: { show: false }
		});
	});
	{/literal}</script>
</div>

{/if}

{if $stat_type eq 'week'}
<div class="filter-form">
	<div class="row">
		<div class="h">{l i='stat_week' gid='banners'}:</div>
		<div class="v"><a href="{$navigation.prev}">{l i="nav_prev" gid='start'}</a> <b>{$navigation.current}</b> <a href="{$navigation.next}">{l i="nav_next" gid='start'}</a></div>
	</div>
	<div class="row" id="view_links">
		<div class="h">{l i='stat_view_type' gid='banners'}:</div>
		<div class="v"><a href="#" class="active">{l i='stat_by_days' gid='banners'}</a></div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_views' gid='banners'}:</div>
		<div class="v">{$statistic.all.view}</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_clicks' gid='banners'}:</div>
		<div class="v">{$statistic.all.click}</div>
	</div>
</div>

<div id="view_blocks">
	<div id="by_days_div">
		<div id="jplot_div"></div>
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_week' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=day from=$statistic.day}
		<tr class="stat">
			<td class="first date">{$item.date}</td>
			<td class="center views">{$item.view}</td>
			<td class="center clicks">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
	<script type='text/javascript'>{literal}
	$(document).ready(function(){
		var viewPoints = [];
		var clicksPoints = [];
		$("#by_days_div tr.stat").each(function(){
			var date = $(this).find('td.date').text();
			var views = parseInt($(this).find('td.views').text());
			var clicks = parseInt($(this).find('td.clicks').text());
			viewPoints.push([date, views]);
			clicksPoints.push([date, clicks]);
		});

		var plot2 = $.jqplot ('jplot_div', [viewPoints, clicksPoints], {
			axesDefaults: { labelRenderer: $.jqplot.CanvasAxisTickRenderer },
			seriesDefaults:{ renderer:$.jqplot.BarRenderer, rendererOptions: {fillToZero: true} },
			legend: { show: true, placement: 'insideGrid' },
			series:[
				{ lineWidth:5, markerOptions: { style:'dimaond' }, label: "{/literal}{l i='stat_views' gid='banners' type='js'}{literal}" },
				{ lineWidth:2, markerOptions: { style:"filledSquare", size:10 }, label: "{/literal}{l i='stat_clicks' gid='banners' type='js'}{literal}" }
			],
			axes: { xaxis: { label: "{/literal}{l i='stat_day' gid='banners' type='js'}{literal}", renderer: $.jqplot.CategoryAxisRenderer} },
			highlighter: { show: true, sizeAdjust: 7.5 },
			cursor: { show: false }
		});
	});
	{/literal}</script>
</div>
{/if}

{if $stat_type eq 'month'}
<div class="filter-form">
	<div class="row">
		<div class="h">{l i='stat_month' gid='banners'}:</div>
		<div class="v"><a href="{$navigation.prev}">{l i="nav_prev" gid='start'}</a> <b>{$navigation.current}</b> <a href="{$navigation.next}">{l i="nav_next" gid='start'}</a></div>
	</div>
	<div class="row" id="view_links">
		<div class="h">{l i='stat_view_type' gid='banners'}:</div>
		<div class="v">
			<a href="#" class="active" id="by_weeks" onclick="switchView('by_weeks'); return false;">{l i='stat_by_weeks' gid='banners'}</a>
			<a href="#" id="by_days" onclick="switchView('by_days'); return false;">{l i='stat_by_days' gid='banners'}</a>
		</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_views' gid='banners'}:</div>
		<div class="v">{$statistic.all.view}</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_clicks' gid='banners'}:</div>
		<div class="v">{$statistic.all.click}</div>
	</div>
</div>

<div id="view_blocks">
	<div id="by_weeks_div" >
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_week' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=day from=$statistic.week}
		<tr class="stat">
			<td class="first week">{$item.start_day} - {$item.end_day}</td>
			<td class="center views">{$item.view}</td>
			<td class="center clicks">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
	<div id="by_days_div" style="display: none;">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_week' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=day from=$statistic.day}
		<tr class="stat">
			<td class="first date">{$item.date}</td>
			<td class="center views">{$item.view}</td>
			<td class="center clicks">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>
{/if}

{if $stat_type eq 'year'}
<div class="filter-form">
	<div class="row">
		<div class="h">{l i='stat_year' gid='banners'}:</div>
		<div class="v"><a href="{$navigation.prev}">{l i="nav_prev" gid='start'}</a> <b>{$navigation.current}</b> <a href="{$navigation.next}">{l i="nav_next" gid='start'}</a></div>
	</div>
	<div class="row" id="view_links">
		<div class="h">{l i='stat_view_type' gid='banners'}:</div>
		<div class="v">
			<a href="#" class="active" id="by_month" onclick="switchView('by_month'); return false;">{l i='stat_by_month' gid='banners'}</a>
			<a href="#" id="by_weeks" onclick="switchView('by_weeks'); return false;">{l i='stat_by_weeks' gid='banners'}</a>
		</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_views' gid='banners'}:</div>
		<div class="v">{$statistic.all.view}</div>
	</div>
	<div class="row">
		<div class="h">{l i='stat_overall_clicks' gid='banners'}:</div>
		<div class="v">{$statistic.all.click}</div>
	</div>
</div>

<div id="view_blocks">
	<div id="by_month_div">
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_month' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=day from=$statistic.month}
		<tr>
			<td class="first">{$item.month}</td>
			<td class="center">{$item.view}</td>
			<td class="center">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
	<div id="by_weeks_div" style="display: none;" >
		<table cellspacing="0" cellpadding="0" class="data" width="100%">
		<tr>
			<th class="first">{l i='stat_week' gid='banners'}</th>
			<th class="w100">{l i='stat_views' gid='banners'}</th>
			<th class="w100">{l i='stat_clicks' gid='banners'}</th>
		</tr>
		{foreach item=item key=day from=$statistic.week}
		<tr>
			<td class="first">{$item.start_day} - {$item.end_day}</td>
			<td class="center">{$item.view}</td>
			<td class="center">{$item.click}</td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>
{/if}

<script>
{literal}
function switchView(id){
	$("#view_links a").removeClass('active');
	$("#view_blocks > div").hide();

	$("#"+id).addClass('active');
	$("#"+id+"_div").show();
}
{/literal}
</script>
{include file="footer.tpl"}