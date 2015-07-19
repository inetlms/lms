<?php

// iNET LMS


$layout['pagetitle'] = 'Lista urządzeń NAS';

$naslist = $DB->GetAll('SELECT nas.id AS nodeid, nas.netdevid, nas.name,
	nn.name AS netdevname, nn.location, nn.networknodeid, 
	inet_ntoa(nodes.ipaddr) AS ipaddr,
	networknode.name AS wezel,
	(SELECT COUNT(n.id) FROM nodes n WHERE n.nasid = nas.id) AS connect 
	FROM nas 
	JOIN netdevices nn ON (nn.id = nas.netdevid) 
	JOIN nodes ON (nodes.id = nas.id) 
	LEFT JOIN networknode ON (networknode.id = nn.networknodeid) 
	ORDER BY nn.name ASC 
	;');

$SMARTY->assign('naslist',$naslist);

$SMARTY->display('naslist.html');
?>