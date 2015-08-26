<?php header("HTTP/1.1 404 Not Found"); ?>
<html>
<head>
<title>404 Page Not Found</title>
<style type="text/css">

body { background-color: #fff; margin: 40px; font-family: 'SegoeUINormal',Arial,'Lucida Grande','Lucida Sans Unicode',Verdana,sans-serif; font-size: 14px; color: #000;}
#content  { background-color: #fff; padding 20px;}
h1 {color: #000000; font-size: 18px; text-decoration: none; text-transform: uppercase; line-height: 1.5; margin: 0 0 4px 0;}
    
</style>
</head>
<body>
	<div id="content">
		<h1><?php echo $heading; ?></h1>
		<?php echo $message; ?>
	</div>
</body>
</html>