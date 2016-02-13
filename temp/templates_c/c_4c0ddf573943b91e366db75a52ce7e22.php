<?php /* V2.10 Template Lite 4 January 2007  (c) 2005-2007 Mark Dickenson. All rights reserved. Released LGPL. 2016-02-13 02:16:30 Pacific Standard Time */ ?>


<head>
<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>

<script>
var latitude = <?php echo $this->_vars['data']['lat']; ?>
;
var longitude = <?php echo $this->_vars['data']['lon']; ?>
;
var contentOnMarker = "<b>Irvine</b><br/>3801 Parkview Ln Apt 17C<br/> Irvine";
var address = "1676 Cayuga Ave, San Francisco, CA 94112";
</script>


<script type="text/javascript"><?php echo '
/*
	var geocoder = new google.maps.Geocoder();
	var contentOnMarker = "<b>Irvine</b><br/>3801 Parkview Ln Apt 17C<br/> Irvine";
	var address = "1676 Cayuga Ave, San Francisco, CA 94112";
	var latitude;
	var longitude;
	geocoder.geocode( { \'address\': address}, function(results, status) {

	  if (status == google.maps.GeocoderStatus.OK) {
		latitude = results[0].geometry.location.lat();
		longitude = results[0].geometry.location.lng();
		
	  }
	}); 
*/
</script>'; ?>




<script type="text/javascript"><?php echo '
  function initialize() {
    var position = new google.maps.LatLng(latitude, longitude);
	var myOptions = {zoom:17,center:position,mapTypeId: google.maps.MapTypeId.ROADMAP};
    var map = new google.maps.Map(document.getElementById("map_canvas"),myOptions);
 	var marker = new google.maps.Marker({map:map,position:position});
	var infowindow = new google.maps.InfoWindow({content: contentOnMarker});
    google.maps.event.addListener(marker, \'click\', function() {infowindow.open(map,marker);});
	infowindow.open(map,marker);
  }
</script>'; ?>


</head>
<body onload="initialize()">
  <div id="map_canvas" style="height:450px;width:550px;border:5px solid #089de3; border-radius:5px;"></div>
</body>
