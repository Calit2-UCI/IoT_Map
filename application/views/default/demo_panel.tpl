{if $DEMO_MODE}

{literal}<script>
    (function(i,s,o,g,r,a,m){
        i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){ 
            (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();
        a=s.createElement(o),m=s.getElementsByTagName(o)[0];
        a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-43414725-1', 'auto', {'allowLinker': true});
    ga('require', 'displayfeatures');
    ga('require', 'linker');
    ga('linker:autoLink', [
        'payproglobal.com', 'livechatinc.com', 'pilotgroup.zendesk.com',
        'paypal.com', 'yandex.ru', 'webmoney.ru', 'qiwi.com', 'mopay.com',
        'socialscript.ru', 'socialbiz.pro', 'datingpro.com', 'datingsoftware.ru', 
        'datingpro.fr', 'pgdatingsoftware.de', 'dating-soft.com', 'realtysoft.pro', 
        'pgrealestate.ru', 'realestatescript.de', 'realestatescript.es', 
        'emlakscripti.biz.tr', 'jobsoftpro.com', 'pgautopro.com', 'allsharevideo.com', 
        'elmspro.com', 'eventsoft.pro', 'pgeventsoft.ru', 'hotescort.pro', 
        'pghotescort.ru', 'newsletter.pro', 'pgwebportal.com', 'pilotgroup.net'
    ]);
    ga('send', 'pageview');
</script>{/literal}

{strip}{literal}<style>
    .clear-demo {
        clear: both;
    }

    .left-demo {
        float: left;
    }

    .right-demo {
        float: right;
    }

    .navbar-demo {
        position: relative;
        font-size: 13px;
        min-height: 30px;
        background-color: #6e6f71;
        border-bottom: 1px solid #505153;
        z-index: 10001;
    }

    .navbar-demo a {
        color: #fff;
        text-decoration: none; 
    }

    .navbar-demo a:hover {
        text-decoration: underline; 
    }

    .navbar-demo .nav-demo {
        margin: 0 auto; 
        max-width: 980px; 
    }

    .b-demo-choice { 
        margin: 5px; 
        padding: 0 15px; 
        line-height: 24px;
        height: 24px;
        border-radius: 5px;
        color: #fff;
        cursor: pointer;
        -webkit-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.56);
        -moz-box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.56);
        box-shadow: 0px 1px 1px 0px rgba(0,0,0,0.56);
    }

    .b-demo-choice.b-demo-default { 
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAZCAYAAADwkER/AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wQTCwQNjhgMswAAACxJREFUCFt9iLENACAMw6z8n+OAf5oObEhlsWxjO0qCAK6NqKpPju8BgNY+NNaRRAUm+MzQAAAAAElFTkSuQmCC') repeat-x;
    }

    .b-demo-choice.b-demo-mobile { 
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAZCAYAAADwkER/AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wQTCwQNjhgMswAAACxJREFUCFt9iLENACAMw6z8n+OAf5oObEhlsWxjO0qCAK6NqKpPju8BgNY+NNaRRAUm+MzQAAAAAElFTkSuQmCC') repeat-x;
    }

    .b-demo-choice.b-demo-green { 
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAZCAYAAADwkER/AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wQTBTMKiuUULgAAADlJREFUCFtti8ENwDAQwiy2yRAdodtmvnMfSS+f8EAYAWM+BiEAUdm4TGL9aXUlsRP2rbjgmTRuvR/M9SZsHXLygQAAAABJRU5ErkJggg==') repeat-x;
    }

    .b-demo-choice.b-demo-orange { 
        background: url('data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAAAEAAAAZCAYAAADwkER/AAAABmJLR0QA/wD/AP+gvaeTAAAACXBIWXMAAAsTAAALEwEAmpwYAAAAB3RJTUUH3wQTCi0x5POF6AAAADRJREFUCFtj+FTA8J/pPwMDA9P//zDiH5z7D8799x9V7D8DA9NfVC66Eiza4BYxuLl7/AcALDgtLBrz91sAAAAASUVORK5CYII=') repeat-x; 
    }

    .b-demo-choice a { 
        line-height: 25px;
        color: #fff;  
    }
    
    .b-demo-choice a:hover {
        text-decoration: none;
    }

    .b-demo-choice .caret-demo {
        border-left: 4px solid transparent;
        border-right: 4px solid transparent;
        border-top: 4px solid;
        display: inline-block;
        margin-left: 2px;
        vertical-align: middle;
        width: 0;
        height: 0;
    }
    
    .b-demo-mobile {
        width: 30px;
        text-align: center;
    }
    
    .b-demo-mobile a {
        display: inline-block;
        margin-top: -2px;
        vertical-align: middle;
    }
    
    .b-demo-mobile a:hover {
        text-decoration: none;
    }
    
    .b-demo-mobile a i,
    .b-demo-mobile a i:hover,
    .b-demo-mobile a i:focus,
    .b-demo-mobile a i:active {
        color: #fff !important;
    }

    .dropdown-demo {
        position: relative;
    }

    .dropdown-demo-menu { 
        position: absolute;
        top: 24px; 
        left: 0;
        min-width: 250px;
        max-width: 100%;
        display: none;
        margin: 0;
        padding: 0;
        border: 1px solid #464748;
        background: #fff;
        z-index: 100000;
        max-height: 300px;
        overflow: auto;
    }

    .dropdown-demo-menu li {
        padding-left: 10px; 
        color: #000; 
        padding: 5px 10px;
        list-style: none;
        border-bottom: 1px solid #d2d2d2;
    }

    .dropdown-demo-menu li:hover {
        background: #c2e6c6;
        color: #000;
    }

    .dropdown-demo-menu a, .dropdown-demo-menu a {
        color: #208B2F !important; 
        text-decoration: underline;
    }
    
    #livechat-eye-catcher a img {
        background: none;
    }

    @media (max-width: 420px) {
        .navbar-demo {
            padding: 5px 0;
        }
        
        .b-demo-choice {
            float: none; 
            margin-left: 10px; 
            margin-right: 10px; 
            text-align: center; 
            width: 250px; 
            margin: 5px auto;
        }
    }
    
    .index-page:before {
        margin-top: 40px;
    }
</style>{/literal}{/strip}

<div class="navbar-demo" id="demo-panel">
	<div class="nav-demo">	
        <div class="b-demo-choice b-demo-orange left-demo">
			<a href="http://www.datingpro.com/dating-software/pricing/">Buy now</a>
		</div>

		<div class="b-demo-choice b-demo-default right-demo" onclick="window.open('http://www.pilotgroup.net/questionnaire/feedback.php?fid=76&pid=2061&mode=light','feedback_questionnaire','width=600,resizable=yes,scrollbars=1'); return false;">
			<a href="javascript:void(0)" onclick="window.open('http://www.pilotgroup.net/questionnaire/feedback.php?fid=76&pid=2061&mode=light','feedback_questionnaire','width=600,resizable=yes,scrollbars=1'); return false;">Leave feedback</a>
		</div>
        
        <div class="b-demo-choice b-demo-default right-demo">
			<a href="http://www.datingpro.com/dating-software/">View features</a>
		</div>
		
        <div class="b-demo-choice b-demo-mobile right-demo">
            <a href="https://play.google.com/store/apps/details?id=com.pilotgroup.pgdatingcore">
                <i class="fa fa-android fa-lg"></i>
            </a>
        </div>
        
        <div class="b-demo-choice b-demo-mobile right-demo">
            <a href="https://itunes.apple.com/us/app/pg-socialbiz-mobile-app/id938689081">
                <i class="fa fa-apple fa-lg"></i>
            </a>
        </div>
        
		<div class="b-demo-choice b-demo-green left-demo">
			<a href="http://demo.datingpro.com/dating/admin">Switch to Admin Mode</a>
		</div>
        
        <div class="dropdown-demo b-demo-choice b-demo-green left-demo">
            <a href="javascript:void(0)">Select design theme</a> <span class="caret-demo"></span>
			<ul class="dropdown-demo-menu">
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/default">Default</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/mediumturquoise">MediumTurquoise</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/lavender">Lavender</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/girls">Girls</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/jewish">Jewish Singles</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/lovers">Hobby</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/neighbourhood">Neighbors</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/blackonwhite">Black on white</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/deepblue">Deep blue</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/companions">Companions</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/community">Community</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/christian">Christian singles</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/autumn_walk">Autumn walk</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/persimmon_red">Persimmon red</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/background_video">Video background (winter)</a></li>
                <li><a href="http://demo.datingpro.com/dating/themes/change_color_scheme/default/background_video_summer">Video background (summer)</a></li>
            </ul>
        </div>
        
        <div class="clear-demo"></div>
	</div>
</div>
<script>{literal}
    var demo = document.getElementById("demo-panel").getElementsByTagName('*');
    for (var i in demo) {
        if((' ' + demo[i].className + ' ').indexOf(' dropdown-demo ') > -1) {
            demo[i].addEventListener("click", function() {
                var demo_menu = this.getElementsByTagName('*');
                for (var i in demo_menu) {
                    if((' ' + demo_menu[i].className + ' ').indexOf(' dropdown-demo-menu ') > -1) {
                        if (demo_menu[i].style.display == 'block') {
                            demo_menu[i].style.display = 'none';
                        } else {
                            demo_menu[i].style.display = 'block';
                        }
                    }
                }
            });
        }
    }
{/literal}</script>

<script>{literal}
	var __lc = {};
	__lc.license = 1083102;
	(function() { var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true; lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s); })();
 
	var LC_API = LC_API || {};
	LC_API.on_chat_started = function(data)
	{
		var visitor_id = LC_API.get_visitor_id();
		
		$.get('http://demo.datingpro.com/dating/start/ajax_live_chat/'+visitor_id);
	};
{/literal}</script>

<script>{literal}
	/* kissmetrics */
	var _kmq = _kmq || [];
	 var _kmk = _kmk || '6d00fd30f5b61692ebe3ddeb42986d90ff05a6e7';
	 function _kms(u){
	 setTimeout(function()
	{ var d = document, f = d.getElementsByTagName('script')[0], s = d.createElement('script'); s.type = 'text/javascript'; s.async = true; s.src = u; f.parentNode.insertBefore(s, f); } 
	, 1);
	 }
	 _kms('//i.kissmetrics.com/i.js');
	 _kms('//doug1izaerwt3.cloudfront.net/' + _kmk + '.1.js');
{/literal}</script>

<script type="text/javascript" src="//use.typekit.net/rps2ais.js"></script>
<script type="text/javascript">{literal}try{Typekit.load();}catch(e){}{/literal}</script>
<style>{literal}html,body{font-family: 'proxima-nova' !important;}{/literal}</style>

{else}

<script>{literal}
    (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function()
    { (i[r].q=i[r].q||[]).push(arguments)}

    ,i[r].l=1*new Date();a=s.createElement(o),
    m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
    })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

    ga('create', 'UA-55856033-1', 'auto');
    ga('send', 'pageview');
{/literal}</script>

<!-- script>{literal}
    var __lc = {};
	__lc.license = 1083102;
    (function() { var lc = document.createElement('script'); lc.type = 'text/javascript'; lc.async = true; lc.src = ('https:' == document.location.protocol ? 'https://' : 'http://') + 'cdn.livechatinc.com/tracking.js'; var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(lc, s); })();
{/literal}</script -->

{/if}
