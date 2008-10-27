<?php
/*
 * CakeMap -- a google maps integrated application built on CakePHP framework.
 * Copyright (c) 2005 Garrett J. Woodworth : gwoo@rd11.com
 * rd11,inc : http://rd11.com
 *
 * @author      gwoo <gwoo@rd11.com>
 * @version     0.10.1311_pre_beta
 * @license     OPPL
 *
 * Modified by 	Mahmoud Lababidi <lababidi@bearsontherun.com>
 * Date			Dec 16, 2006
 * 
 *
 */
class GoogleMapHelper extends Helper {

	var $errors = array();

	var $key = "ABQIAAAAvCbGW5EV-rEJpZP96-o0NxQ1SJ3zY01f5orEIl-iQQteZ6BtLxQEPgACixUBYnGKDDbCXFOnFTJ1JQ";

	function map($default, $style = 'width: 400px; height: 400px' )
	{
		//if (empty($default)){return "error: You have not specified an address to map"; exit();}
		$out = "<div id=\"map\"";
		$out .= isset($style) ? "style=\"".$style."\"" : null;
		$out .= " ></div>";
		$out .= "
		<script type=\"text/javascript\">
		//<![CDATA[

		if (GBrowserIsCompatible()) 
		{	
			var map = new GMap(document.getElementById(\"map\"));
			map.addControl(new GLargeMapControl());
			map.addControl(new GMapTypeControl());
			map.setMapType(map.getMapTypes()[".$default['type']."]);
			map.centerAndZoom(new GPoint(".$default['long'].", ".$default['lat']."), ".$default['zoom'].");
		}
		//]]>
		</script>";

		return $out;
	}

	function addMarkers(&$data, $icon=null)
	{
		$out = "
			<script type=\"text/javascript\">
			//<![CDATA[
			if (GBrowserIsCompatible()) 
			{
			";
			
			if(is_array($data))
			{
				if($icon)
				{
					$out .= $icon;		
				}
				else
				{
					$out .= 'var icon = new GIcon();
						icon.image = "http://labs.google.com/ridefinder/images/mm_20_red.png";
						icon.shadow = "http://labs.google.com/ridefinder/images/mm_20_shadow.png";
						icon.iconSize = new GSize(12, 20);
						icon.shadowSize = new GSize(22, 20);
						icon.iconAnchor = new GPoint(6, 20);
						icon.infoWindowAnchor = new GPoint(5, 1);
					';

				}
				$i = 0;
				foreach($data as $n=>$m){
					$keys = array_keys($m);
					$point = $m[$keys[0]];
					if(!preg_match('/[^0-9\\.\\-]+/',$point['longitude']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['longitude'])
						&& !preg_match('/[^0-9\\.\\-]+/',$point['latitude']) && preg_match('/^[-]?(?:180|(?:1[0-7]\\d)|(?:\\d?\\d))[.]{1,1}[0-9]{0,15}/',$point['latitude']))
					{
						$out .= "
							var point".$i." = new GPoint(".$point['longitude'].",".$point['latitude'].");
							var marker".$i." = new GMarker(point".$i.",icon);
							map.addOverlay(marker".$i.");
							marker$i.html = \"$point[title]$point[html]\";
							GEvent.addListener(marker".$i.", \"click\", 
							function() {
								marker$i.openInfoWindowHtml(marker$i.html);
							});";
						$data[$n][$keys[0]]['js']="marker$i.openInfoWindowHtml(marker$i.html);";
						$i++;
					}
				}
			}
		$out .=	"} 
				//]]>
			</script>";
		return $out;
	}
	
	function addClick($var, $script=null)
	{
		$out = "
			<script type=\"text/javascript\">
			//<![CDATA[
			if (GBrowserIsCompatible()) 
			{
			" 
			.$script
			.'GEvent.addListener(map, "click", '.$var.', true);'
			."} 
				//]]>
			</script>";
		return $out;
	}	
	
	function addMarkerOnClick($innerHtml = null)
	{
		$mapClick = '
			var mapClick = function (overlay, point) {
				var point = new GPoint(point.x,point.y);
				var marker = new GMarker(point,icon);
				map.addOverlay(marker)
				GEvent.addListener(marker, "click", 
				function() {
					marker.openInfoWindowHtml('.$innerHtml.');
				});
			}
		';
		return $this->addClick('mapClick', $mapClick);
		
	}	

}
?>
