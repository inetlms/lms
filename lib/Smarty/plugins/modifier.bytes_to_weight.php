<?php
/*****************************************
*                                        *
* gg : 6164816                           *
* email : sylwester.kondracki@gmail.com  *
*                                        *
*****************************************/
function smarty_modifier_bytes_to_weight($waga)
{
    $result = "0 B";
    
    if ($waga <= 1024 )
    {
		$result = $waga." B";
    } else {
	$waga = ($waga / 1024);
	if ($waga <= 1024)
	    $result = sprintf('%.2f',$waga)." KB";
	else {
	    $waga = ($waga / 1024);
	    if ($waga <= 1024)
		$result = sprintf('%.2f',$waga)." MB";
	    else {
		$waga = ($waga / 1024);
		$result = sprintf('%.2f',$waga)." GB";
	    }
	}
    
    }
    
    return $result;
    

}
?>