<?php

function open_flash_chart_object( $width, $height, $url )
{
$x='<object classid="clsid:d27cdb6e-ae6d-11cf-96b8-444553540000" codebase="http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0" width="400" height="300" id="graph-2" align="middle">';
$x .= '<param name="allowScriptAccess" value="sameDomain" />';
$x .= '<param name="movie" value="ofc-library/open-flash-chart.swf?width='. $width .'&height='. $height .'&data='. $url .'" /><param name="quality" value="high" /><param name="bgcolor" value="#FFFFFF" />';
$x .= '<embed                src="ofc-library/open-flash-chart.swf?width='. $width .'&height='. $height .'&data='. $url .'" quality="high" bgcolor="#FFFFFF" width="'. $width .'" height="'. $height .'" name="open-flash-chart" align="middle" allowScriptAccess="sameDomain" type="application/x-shockwave-flash" pluginspage="http://www.macromedia.com/go/getflashplayer" />';
$x .= '</object>';
return $x;
}

?>
